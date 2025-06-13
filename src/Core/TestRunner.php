<?php

namespace ArcTest\Core;

use ArcTest\Contracts\ResultPrinterInterface;
use ArcTest\Contracts\TestListenerInterface;
use ArcTest\Enum\TestOutcome;
use ReflectionException;

/**
 * Represents a test runner that is responsible for executing test suites
 * and displaying the results to the console.
 */
class TestRunner {
    private ResultPrinterInterface $printer;
    private TestExecutor $executor;
    private TestSelector $selector;
    private TestTracker $tracker;
    private DependencyChecker $checker;
    private LifeCycleManager $lifecycle;
    private array $listeners = [];

    private const int FAIL_FAST_EXIT_CODE = 2;

    /**
     * Constructor for initializing the test suite with a ResultPrinterInterface instance
     * @param ResultPrinterInterface $printer The ResultPrinterInterface instance to handle result printing
     * @return void
     */
    public function __construct(ResultPrinterInterface $printer) {
        $this->printer = $printer;
        $this->lifecycle = new LifeCycleManager();
        $this->executor = new TestExecutor($this->lifecycle);
        $this->selector = new TestSelector();
        $this->tracker = new TestTracker();
        $this->checker = new DependencyChecker();
    }

    /**
     * Adds a listener to the list of listeners.
     * @param TestListenerInterface $listener The listener to be added.
     * @return void
     */
    public function addListener(TestListenerInterface $listener): void {
        $this->listeners[] = $listener;
    }

    /**
     * Run the test suite with specified options.
     * @param TestSuite $suite The test suite to run.
     * @param bool $verbose Whether to display detailed output.
     * @param bool $failFast Whether to stop on the first test failure.
     * @param string $filter A filter to limit which tests are executed.
     * @return int Returns 1 if there are failures, 0 otherwise.
     * @throws ReflectionException
     */
    public function run(TestSuite $suite, bool $verbose = false, bool $failFast = false, string $filter = "", array $groups = [], array $excludes = []): int {
        $summary = new TestSummary();
        $results = new TestResultCollection();

        $this->printer->start();

        foreach ($suite->getClasses() as $className) {
            $methods = $this->getMethods($className, $filter, $groups, $excludes);

            if(empty($methods)) continue;

            $this->lifecycle->beforeAll($className);

            try {
                foreach($methods as $method) {
                    $instance = new $className();

                    if($this->process($instance, $className, $method, $summary, $results, $failFast)) {
                        return self::FAIL_FAST_EXIT_CODE;
                    }
                }
            } finally {
                $this->lifecycle->afterAll($className);
            }
        }

        $this->printer->printSummary($summary);
        return $summary->hasFailures() ? 1 : 0;
    }

    /**
     * Processes a test method by executing it, recording results, and notifying listeners.
     * @param TestCase $instance The test case instance containing the method to be processed.
     * @param string $className The name of the class that contains the test method.
     * @param string $method The name of the test method to be executed.
     * @param TestSummary $summary The summary object for aggregating test results and metrics.
     * @param TestResultCollection $results A collection to store the outcome of the test execution.
     * @param bool $failFast Specifies whether to halt further tests upon the first failure.
     * @return bool Returns true if the test method was skipped or the fail-fast condition is triggered.
     */
    private function process(TestCase $instance, string $className, string $method, TestSummary $summary, TestResultCollection $results, bool $failFast): bool {
        $this->notifyStart($className, $method);

        $skipper = $this->checker->skip($instance, $method);
        if($skipper !== null) {
            $this->processResult($skipper, $summary, $results);
            $this->notifyEnd($skipper);
            return true;
        }

        $result = $this->executor->run($instance, $method);
        if($result->outcome === TestOutcome::PASSED) {
            $this->checker->record($method);
        }

        $this->processResult($result, $summary, $results);
        $this->notifyEnd($result);

        return $failFast && $result->outcome === TestOutcome::FAILED;
    }

    /**
     * Retrieves the methods of a specified class after applying filtering criteria.
     * @param string $class The name of the class to retrieve methods from.
     * @param string $filter The filter string used for method selection.
     * @param array $groups The array of groups to include in the filtering process.
     * @param array $excludes The array of methods or groups to be excluded from the selection.
     * @return array The filtered array of method names matching the criteria.
     * @throws ReflectionException
     */
    private function getMethods(string $class, string $filter, array $groups, array $excludes): array {
        return array_filter(get_class_methods($class), fn ($m) => $this->selector->check(new $class(), $m, $filter, $groups, $excludes));
    }

    /**
     * Processes the test result by updating the summary, notifying listeners, and adding the result to the collection.
     * @param TestResult $result The individual test result to process.
     * @param TestSummary $summary The summary object to update with the test result.
     * @param TestResultCollection $results The collection to which the test result will be added.
     * @return void
     */
    private function processResult(TestResult $result, TestSummary $summary, TestResultCollection $results): void {
        $this->tracker->apply($summary, $result);
        $summary->incrementDuration($result->duration);
        $this->printer->printTestResult($result);
        $results->add($result);
    }

    /**
     * Notifies all listeners about the start of a test.
     * @param string $className The name of the class where the test is located.
     * @param string $method The name of the test method being started.
     * @return void
     */
    private function notifyStart(string $className, string $method): void {
        foreach($this->listeners as $listener) {
            $listener->onTestStart($className, $method);
        }
    }

    /**
     * Notifies all registered listeners about the end of a test.
     * @param TestResult $result The result of the test that ended.
     * @return void
     */
    private function notifyEnd(TestResult $result): void {
        foreach($this->listeners as $listener) {
            $listener->onTestEnd($result);
        }
    }
}