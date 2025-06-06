<?php

namespace ArcTest\Core;

use JetBrains\PhpStorm\NoReturn;
use Throwable;

/**
 * Represents a test runner that is responsible for executing test suites
 * and displaying the results to the console.
 */
class TestRunner {
    /**
     * Run the test suite with optional parameters for verbosity and fast failure
     * @param TestSuite $suite The test suite to be run
     * @param bool $verbose Set to true for verbose output, defaults to false
     * @param bool $failFast Set to true to stop testing after first failure, defaults to false
     * @return int
     */
    public function run(TestSuite $suite, bool $verbose = false, bool $failFast = false): int {
        echo PHP_EOL;
        echo "Running tests..." . PHP_EOL;
        echo PHP_EOL;

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
                            echo "FAILED: {$className}::{$method} - Expected exception {$testInstance->getExpectedException()} was not thrown" . PHP_EOL;
                            if($failFast){
                                $this->print($result);
                                return 1;
                            }
                        } else if($testInstance->isSkipped()) {
                            $result->incrementSkipped();
                            echo "SKIPPED: {$className}::{$method} ({$testInstance->getSkipMessage()})" . PHP_EOL;
                        }  else {
                            $result->incrementPassed();
                            echo "PASSED: {$className}::{$method}" . PHP_EOL;
                        }
                    } catch (Throwable $e) {
                        if($testInstance->getExpectedException() !== null && is_a($e, $testInstance->getExpectedException())) {
                            $result->incrementPassed();
                            echo "PASSED: {$className}::{$method} (expected exception {$testInstance->getExpectedException()})" . PHP_EOL;
                        } else {
                            $result->incrementFailed();
                            echo "FAILED: {$className}::{$method} - {$e->getMessage()}" . PHP_EOL;

                            if($verbose) echo $e->getTraceAsString() . PHP_EOL;
                            if($failFast){
                                $this->print($result);
                                return 1;
                            }
                        }
                    } finally {
                        $testInstance->tearDown();
                    }
                }
            }
        }

        $this->print($result);
        return $result->hasFailures() ? 1 : 0;
    }

    /**
     * Print the summary of the test run with total, passed, failed, and skipped counts
     * @param TestResult $result The TestResult object containing test run data
     */
    private function print(TestResult $result): void {
        echo PHP_EOL;
        echo "Test run completed!" . PHP_EOL;
        echo PHP_EOL;
        echo "Summary:" . PHP_EOL;
        echo "Total: {$result->getTotal()} | Passed: {$result->getPassed()} | Failed: {$result->getFailed()} | Skipped: {$result->getSkipped()}" . PHP_EOL;
    }
}