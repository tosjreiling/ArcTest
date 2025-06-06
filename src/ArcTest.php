<?php

namespace ArcTest;

use ArcTest\Core\TestRunner;
use ArcTest\Core\TestSuite;

use ArcTest\Printer\ConsolePrinter;
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
     * Runs the test suite in the specified directory.
     * @param string $directory The directory containing the tests to run.
     * @param bool $verbose (optional) Whether to display detailed output. Default is false.
     * @param bool $failFast (optional) Whether to stop running tests on the first failure. Default is false.
     * @return int
     * @throws ReflectionException
     */
    public static function run(string $directory, bool $verbose = false, bool $failFast = false): int {
        $suite = new TestSuite();

        if (!empty(self::$tests)) {
            foreach (self::$tests as $test) {
                $suite->add($test);
            }
        } else {
            $suite->discover($directory);
        }

        $printer = new ConsolePrinter($verbose);
        $runner = new TestRunner($printer);
        return $runner->run($suite, $verbose, $failFast);
    }
}