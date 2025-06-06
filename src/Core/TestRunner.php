<?php

namespace ArcTest\Core;

use ArcTest\Contracts\ResultPrinterInterface;
use ArcTest\Enum\TestOutcome;
use JetBrains\PhpStorm\NoReturn;
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
     * Run the test suite with optional parameters for verbosity and fast failure
     * @param TestSuite $suite The test suite to be run
     * @param bool $verbose Set to true for verbose output, defaults to false
     * @param bool $failFast Set to true to stop testing after first failure, defaults to false
     * @return int
     */
    public function run(TestSuite $suite, bool $verbose = false, bool $failFast = false): int {
        $this->printer->start();
        $result = new TestResult();

        foreach($suite->getClasses() as $className) {
            /* @var TestCase $testInstance */
            $testInstance = new $className();
            $methods = get_class_methods($testInstance);

            foreach($methods as $method) {
                if(str_starts_with($method, "test")) {
                    $result->incrementTotal();
                    try {
                        $testInstance->setUp();
                        $testInstance->$method();

                        if($testInstance->getExpectedException() !== null) {
                            $result->incrementFailed();
                            $this->printer->printTestResult($className, $method, TestOutcome::FAILED, "Expected exception {$testInstance->getExpectedException()} was not thrown");
                            if($failFast){
                                $this->printer->printSummary($result);
                                return 1;
                            }
                        } else if($testInstance->isSkipped()) {
                            $result->incrementSkipped();
                            $this->printer->printTestResult($className, $method, TestOutcome::SKIPPED, $testInstance->getSkipMessage());
                        }  else {
                            $result->incrementPassed();
                            $this->printer->printTestResult($className, $method, TestOutcome::PASSED);
                        }
                    } catch (Throwable $e) {
                        if($testInstance->getExpectedException() !== null && is_a($e, $testInstance->getExpectedException())) {
                            $result->incrementPassed();
                            $this->printer->printTestResult($className, $method, TestOutcome::PASSED, "(expected exception {$testInstance->getExpectedException()})");
                        } else {
                            $result->incrementFailed();
                            $this->printer->printTestResult($className, $method, TestOutcome::FAILED, $e->getMessage(), $e);

                            if($failFast){
                                $this->printer->printSummary($result);
                                return 1;
                            }
                        }
                    } finally {
                        $testInstance->tearDown();
                    }
                }
            }
        }

        $this->printer->printSummary($result);
        return $result->hasFailures() ? 1 : 0;
    }
}