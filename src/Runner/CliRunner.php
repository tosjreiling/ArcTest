<?php

namespace ArcTest\Runner;

use ArcTest\ArcTest;
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
            format: $this->args->format,
            filter: $this->args->filter,
            groups: $this->args->groups
        );
    }
}