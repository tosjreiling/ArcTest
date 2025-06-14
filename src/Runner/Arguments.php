<?php

namespace ArcTest\Runner;

use ArcTest\Enum\ArgKey;
use ArcTest\Enum\PrintFormat;

/**
 * Class Arguments
 */
readonly class Arguments {
    public string $filter;
    public array $groups;
    public array $excludes;
    public PrintFormat $format;
    public bool $verbose;
    public bool $failFast;
    public bool $help;
    public string $output;

    /**
     * Constructor for initializing a new instance with optional parameters.
     * @param string $filter The filter value to set.
     * @param array $groups The groups array to set.
     * @param array $excludes The excludes array to set.
     * @param PrintFormat $format The PrintFormat enum value to set, defaults to PrintFormat::CONSOLE.
     * @param bool $verbose The verbose flag, defaults to false.
     * @param bool $failFast The failFast flag, defaults to false.
     * @param bool $help The help flag, defaults to false.
     * @return void
     */
    public function __construct(string $filter = "", array $groups = [], array $excludes = [], PrintFormat $format = PrintFormat::CONSOLE, bool $verbose = false, bool $failFast = false, bool $help = false, string $output = "php://stdout") {
        $this->filter = $filter;
        $this->groups = $groups;
        $this->excludes = $excludes;
        $this->format = $format;
        $this->verbose = $verbose;
        $this->failFast = $failFast;
        $this->help = $help;
        $this->output = $output;
    }

    /**
     * Parses input arguments to create an instance of the current class.
     * @param array $argv An array of command-line arguments
     * @return self An instance of the class constructed based on the parsed arguments
     */
    public static function fromArgv(array $argv): self {
        $parsed = [];

        foreach(array_slice($argv, 1) as $arg) {
            if(str_starts_with($arg, "--")) {
                $arg = substr($arg, 2);

                if(str_contains($arg, "=")) {
                    [$key, $value] = explode("=", $arg, 2);
                    $parsed[$key] = $value;
                } else {
                    $parsed[$arg] = true;
                }
            }
        }

        return new self(
            filter: $parsed[ArgKey::FILTER->value] ?? "",
            groups: isset($parsed[ArgKey::GROUP->value]) ? explode(",", $parsed[ArgKey::GROUP->value]) : [],
            excludes: isset($parsed[ArgKey::EXCLUDE->value]) ? explode(",", $parsed[ArgKey::EXCLUDE->value]) : [],
            format: PrintFormat::fromString($parsed[ArgKey::FORMAT->value] ?? ""),
            verbose: isset($parsed[ArgKey::VERBOSE->value]),
            failFast: isset($parsed[ArgKey::FAIL_FAST->value]),
            help: isset($parsed[ArgKey::HELP->value]),
            output: $parsed[ArgKey::OUTPUT->value] ?? "php://stdout"
        );
    }
}