<?php

namespace ArcTest\Core;

use ArcTest\Contracts\ResultPrinterInterface;
use ArcTest\Enum\TestOutcome;
use Throwable;

/**
 * Represents a test runner that is responsible for executing test suites
 * and displaying the results to the console.
 */
class TestRunner {
    private ResultPrinterInterface $printer;

    /**
     * Constructor for initializing the test suite with a ResultPrinterInterface instance
     * @param ResultPrinterInterface $printer The ResultPrinterInterface instance to handle result printing
     * @return void
     */
    public function __construct(ResultPrinterInterface $printer) {
        $this->printer = $printer;
    }

    /**
     * Run the test suite with specified options.
     * @param TestSuite $suite The test suite to run.
     * @param bool $verbose Whether to display detailed output.
     * @param bool $failFast Whether to stop on the first test failure.
     * @param string $filter A filter to limit which tests are executed.
     * @return int Returns 1 if there are failures, 0 otherwise.
     */
    public function run(TestSuite $suite, bool $verbose = false, bool $failFast = false, string $filter = ""): int {
        $summary = new TestSummary();
        $results = new TestResultCollection();

        $this->printer->start();

        foreach($suite->getClasses() as $className) {
            /* @var TestCase $testInstance */
            $testInstance = new $className();
            $methods = get_class_methods($testInstance);

            foreach($methods as $method) {
                if(!str_starts_with($method, "test")) continue;
                if(!empty($filter) && !str_contains($method, $filter)) continue;

                $summary->incrementTotal();

                try {
                    $testInstance->setUp();
                    $testInstance->$method();

                    if($testInstance->getExpectedException() !== null) {
                        $summary->incrementFailed();

                        $result = new TestResult($className, $method, TestOutcome::FAILED, "Expected exception {$testInstance->getExpectedException()} was not thrown");
                        $results->add($result);
                        $this->printer->printTestResult($result);

                        if($failFast){
                            $this->printer->printSummary($summary);
                            return 1;
                        }
                    } else if($testInstance->isSkipped()) {
                        $summary->incrementSkipped();

                        $result = new TestResult($className, $method, TestOutcome::SKIPPED, $testInstance->getSkipMessage());
                        $results->add($result);
                        $this->printer->printTestResult($result);
                    }  else {
                        $summary->incrementPassed();

                        $result = new TestResult($className, $method, TestOutcome::PASSED);
                        $results->add($result);
                        $this->printer->printTestResult($result);
                    }
                } catch (Throwable $e) {
                    if($testInstance->getExpectedException() !== null && is_a($e, $testInstance->getExpectedException())) {
                        $summary->incrementPassed();

                        $result = new TestResult($className, $method, TestOutcome::PASSED, "(expected exception {$testInstance->getExpectedException()})");
                        $results->add($result);
                        $this->printer->printTestResult($result);
                    } else {
                        $summary->incrementFailed();

                        $result = new TestResult($className, $method, TestOutcome::FAILED, $e->getMessage(), $e);
                        $results->add($result);
                        $this->printer->printTestResult($result);

                        if($failFast){
                            $this->printer->printSummary($summary);
                            return 1;
                        }
                    }
                } finally {
                    $testInstance->tearDown();
                }
            }
        }

        $this->printer->printSummary($summary);
        return $summary->hasFailures() ? 1 : 0;
    }
}