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
     * Executes the test suite in the specified directory.
     * @param string $directory The directory to discover and execute tests.
     * @param bool $verbose Optional. Whether to enable verbose output. Default is false.
     * @param bool $failFast Optional. Whether to stop execution upon the first test failure. Default is false.
     * @param PrintFormat $format The format in which test results should be printed. Default is PrintFormat::CONSOLE.
     * @return int The number of test failures encountered during execution.
     * @throws ReflectionException
     */
    public static function run(string $directory, bool $verbose = false, bool $failFast = false, PrintFormat $format = PrintFormat::CONSOLE): int {
        $suite = new TestSuite();

        if (!empty(self::$tests)) {
            foreach (self::$tests as $test) {
                $suite->add($test);
            }
        } else {
            $suite->discover($directory);
        }

        $printer = self::resolvePrinter($format, $verbose);
        $runner = new TestRunner($printer);
        return $runner->run($suite, $verbose, $failFast);
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