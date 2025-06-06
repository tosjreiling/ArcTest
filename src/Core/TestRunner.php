<?php

namespace ArcTest\Core;

use Throwable;

/**
 * Represents a test runner that is responsible for executing test suites
 * and displaying the results to the console.
 */
class TestRunner {
    /**
     * Executes the provided test suite and outputs the progress and results to the console.
     * @param TestSuite $suite The test suite containing the classes to be executed.
     * @return void
     */
    public function run(TestSuite $suite): void {
        echo "Running tests..." . PHP_EOL;

        foreach($suite->getClasses() as $className) {
            $testInstance = new $className();
            $methods = get_class_methods($testInstance);

            foreach($methods as $method) {
                if(str_starts_with($method, "test")) {
                    try {
                        $testInstance->setUp();
                        $testInstance->$method();

                        if($testInstance->isSkipped()) {
                            echo "SKIPPED: {$className}::{$method} ({$testInstance->getSkipMessage()})" . PHP_EOL;
                        }  else {
                            echo "PASSED: {$className}::{$method}" . PHP_EOL;
                        }
                    } catch (Throwable $e) {
                        echo "FAILED: {$className}::{$method} - {$e->getMessage()}" . PHP_EOL;
                    } finally {
                        $testInstance->tearDown();
                    }
                }
            }
        }

        echo "Test run completed!" . PHP_EOL;
    }
}