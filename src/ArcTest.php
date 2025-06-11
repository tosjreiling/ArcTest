<?php

namespace ArcTest;

use ArcTest\Contracts\ResultPrinterInterface;
use ArcTest\Contracts\TestListenerInterface;
use ArcTest\Core\TestDiscovery;
use ArcTest\Core\TestRunner;
use ArcTest\Printer\ConsolePrinter;

use ReflectionException;

/**
 * Handles the registration and execution of test classes within a test suite.
 */
class ArcTest {
    private static array $tests = [];
    private static array $listeners = [];

    /**
     * Registers a test class to be included in the test suite.
     * @param string $testClass The fully-qualified name of the test class to be registered.
     * @return void
     */
    public static function register(string $testClass): void {
        self::$tests[] = $testClass;
    }

    /**
     * Adds a new listener to the list of listeners.
     * @param TestListenerInterface $listener The listener to be added.
     * @return void
     */
    public static function addListener(TestListenerInterface $listener): void {
        self::$listeners[] = $listener;
    }

    /**
     * Executes the test suite by discovering and adding test cases from the provided directory
     * or using pre-defined tests, then runs them with the specified settings.
     * @param string $directory The directory to search for test cases.
     * @param bool $verbose Whether to run the tests in verbose mode. Defaults to false.
     * @param bool $failFast Whether to stop executing tests after the first failure. Defaults to false.
     * @param string $filter A filter pattern to match specific tests. Defaults to an empty string.
     * @param array $group An array of test groups to include. Defaults to an empty array.
     * @param ResultPrinterInterface $printer The result printer used for output. Defaults to ConsolePrinter.
     * @return int The number of failed tests.
     * @throws ReflectionException
     */
    public static function run(string $directory, bool $verbose = false, bool $failFast = false, string $filter = "", array $group = [], ResultPrinterInterface $printer = new ConsolePrinter()): int {
        $discovery = new TestDiscovery();
        $suite = $discovery->discover(self::$tests, $directory, $filter);

        $runner = new TestRunner($printer);
        foreach(self::$listeners as $listener) $runner->addListener($listener);

        return $runner->run($suite, $verbose, $failFast, $filter, $group);
    }
}