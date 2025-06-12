<?php

namespace ArcTest\Runner;

use ArcTest\ArcTest;
use ArcTest\Contracts\ResultPrinterInterface;
use ArcTest\Enum\PrintFormat;
use ArcTest\Printer\ConsolePrinter;
use ArcTest\Printer\JsonPrinter;
use ArcTest\Printer\JUnitXmlPrinter;
use ReflectionException;

/**
 * Class CliRunner
 */
class CliRunner {
    private Arguments $args;

    /**
     * Constructor for initializing the object with command line arguments.
     * @param array $argv The array of command line arguments.
     * @return void
     */
    public function __construct(array $argv) {
        $this->args = Arguments::fromArgv($argv);
    }

    /**
     * Run the test suite with optional parameters.
     * @param string $directory The directory to run tests in. Defaults to "tests".
     * @return int Returns the exit status code after running the test suite.
     * @throws ReflectionException
     */
    public function run(string $directory = "tests"): int {
        if($this->args->help) {
            Helper::show();
            return 0;
        }

        return ArcTest::run(
            directory: $directory,
            verbose: $this->args->verbose,
            failFast: $this->args->failFast,
            filter: $this->args->filter,
            group: $this->args->groups,
            exclude: $this->args->excludes,
            printer: $this->resolvePrinter($this->args->format)
        );
    }

    /**
     * Resolves and returns the appropriate printer based on the specified format.
     * @param PrintFormat $format The output format for the printer.
     * @return ResultPrinterInterface Returns an instance of the printer matching the specified format.
     */
    private function resolvePrinter(PrintFormat $format): ResultPrinterInterface {
        return match($format) {
            PrintFormat::JSON => new JsonPrinter(),
            PrintFormat::JUNIT => new JUnitXmlPrinter(),
            default => new ConsolePrinter()
        };
    }
}