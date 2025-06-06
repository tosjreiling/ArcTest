<?php

namespace ArcTest;

use ArcTest\Core\TestRunner;
use ArcTest\Core\TestSuite;

use ReflectionException;

/**
 * Handles the registration and execution of test classes within a test suite.
 */
class ArcTest {
    private static array $tests = [];

    /**
     * Registers a test class to be included in the test suite.
     * @param string $testClass The fully-qualified name of the test class to be registered.
     * @return void
     */
    public static function register(string $testClass): void {
        self::$tests[] = $testClass;
    }

    /**
     * Executes the test runner with a suite of tests.
     * @param string $directory The directory to discover tests from if no tests are explicitly added. Defaults to ../../tests relative to the script.
     * @return void
     * @throws ReflectionException
     */
    public static function run(string $directory = __DIR__ . "/../../tests"): void {
        $suite = new TestSuite();

        if(!empty(self::$tests)) {
            foreach(self::$tests as $test) {
                $suite->add($test);
            }
        } else {
            $suite->discover($directory);
        }

        $runner = new TestRunner();
        $runner->run($suite);
    }
}