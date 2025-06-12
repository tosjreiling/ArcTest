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
    private array $listeners = [];

    /**
     * Constructor for initializing the test suite with a ResultPrinterInterface instance
     * @param ResultPrinterInterface $printer The ResultPrinterInterface instance to handle result printing
     * @return void
     */
    public function __construct(ResultPrinterInterface $printer) {
        $this->printer = $printer;
        $this->executor = new TestExecutor();
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

        foreach($suite->getClasses() as $class) {
            $instance = new $class();
            $methods = get_class_methods($class);

            foreach($methods as $method) {
                if(!$this->selector->check($instance, $method, $filter, $groups, $excludes)) continue;

                foreach($this->listeners as $listener) $listener->onTestStart($class, $method);

                $skipper = $this->checker->skip($instance, $method);
                if($skipper !== null) {
                    $this->tracker->apply($summary, $skipper);
                    $summary->incrementDuration($skipper->duration);
                    $this->printer->printTestResult($skipper);
                    $results->add($skipper);

                    foreach($this->listeners as $listener) $listener->onTestEnd($skipper);

                    continue;
                }

                $result = $this->executor->run($instance, $method);

                if($result->outcome === TestOutcome::PASSED) $this->checker->record($method);

                foreach($this->listeners as $listener) $listener->onTestEnd($result);

                $this->tracker->apply($summary, $result);
                $summary->incrementDuration($result->duration);
                $this->printer->printTestResult($result);

                $results->add($result);

                if($failFast && $result->outcome === TestOutcome::FAILED) {
                    $this->printer->printSummary($summary);
                    return 1;
                }
            }

            if($failFast && $summary->hasFailures()) break;
        }

        $this->printer->printSummary($summary);
        return $summary->hasFailures() ? 1 : 0;
    }
}