<?php

namespace ArcTest;

use ArcTest\Contracts\ResultPrinterInterface;
use ArcTest\Core\TestRunner;
use ArcTest\Core\TestSuite;

use ArcTest\Enum\PrintFormat;
use ArcTest\Printer\ConsolePrinter;
use ArcTest\Printer\JsonPrinter;
use Exception;
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
     * Run the test suite.
     * @param string $directory The directory to search for tests.
     * @param bool $verbose (optional) Whether to display verbose output. Default is false.
     * @param bool $failFast (optional) Whether to stop execution on first failure. Default is false.
     * @param PrintFormat $format (optional) The format to use for printing test results. Default is PrintFormat::CONSOLE.
     * @param string $filter (optional) A filter to apply on the discovered tests. Default is an empty string.
     * @param array $groups (optional) Groups to run tests from. Default is an empty array.
     * @return int The exit code of the test execution.
     * @throws ReflectionException
     */
    public static function run(string $directory, bool $verbose = false, bool $failFast = false, PrintFormat $format = PrintFormat::CONSOLE, string $filter = "", array $groups = []): int {
        $suite = new TestSuite();

        if (!empty(self::$tests)) {
            foreach (self::$tests as $test) {
                $suite->add($test);
            }
        } else {
            $suite->discover($directory, $filter);
        }

        $printer = self::resolvePrinter($format, $verbose);
        $runner = new TestRunner($printer);
        return $runner->run($suite, $verbose, $failFast, $filter, $groups);
    }

    /**
     * Resolves and creates the appropriate printer instance based on the specified format.
     * @param PrintFormat $format The format in which test results should be printed.
     * @param bool $verbose Whether to enable verbose output in the console printer.
     * @return ResultPrinterInterface An instance of the printer corresponding to the specified format.
     */
    private static function resolvePrinter(PrintFormat $format, bool $verbose): ResultPrinterInterface {
        return match($format) {
            PrintFormat::CONSOLE => new ConsolePrinter($verbose),
            PrintFormat::JSON => new JsonPrinter()
        };
    }
}