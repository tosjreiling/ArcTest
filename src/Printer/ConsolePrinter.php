<?php

namespace ArcTest\Printer;

use ArcTest\Contracts\ResultPrinterInterface;
use ArcTest\Core\TestResult;
use ArcTest\Core\TestSummary;
use ArcTest\Enum\TestOutcome;
use ArcTest\Exceptions\AssertionFailedException;
use ArcTest\Utils\ConsoleFormatter;
use Override;
use Throwable;

class ConsolePrinter implements ResultPrinterInterface {
    private bool $verbose;
    private ConsoleFormatter $formatter;

    public function __construct(bool $verbose = false) {
        $this->verbose = $verbose;
        $this->formatter = new ConsoleFormatter();
    }

    /**
     * Start the process. This method initiates the process flow and triggers the beginning of the operation.
     * @return void
     */
    #[Override] public function start(): void {
        date_default_timezone_set("Europe/Brussels");
        echo "####################################################" . PHP_EOL;
        echo "Running tests on " . date("d-m-Y H:i:s") . "..." . PHP_EOL . PHP_EOL;
    }

    /**
     * Prints the details of the test result.
     * @param TestResult $result The test result object to be printed
     * @return void
     */
    #[Override] public function printTestResult(TestResult $result): void {
        switch($result->outcome) {
            case TestOutcome::PASSED:
                echo $this->formatter->green("PASSED:") . " {$result->className}::{$result->method} (" . number_format($result->duration, 3) . "s)" . PHP_EOL;
                break;
            case TestOutcome::SKIPPED:
                echo $this->formatter->yellow("SKIPPED:") . " {$result->className}::{$result->method}";
                if(!empty($result->message)) echo " ({$result->message})";
                echo "(" . number_format($result->duration, 3) . "s)";
                echo PHP_EOL;
                break;
            case TestOutcome::FAILED:
                echo $this->formatter->red("FAILED:") . " {$result->className}::{$result->method}";

                if($result->exception instanceof AssertionFailedException) {
                    echo " Expected: " . var_export($result->exception->getExpected(), true) . " => Actual: " . var_export($result->exception->getActual(), true);
                } else if(!empty($result->message)) {
                    echo " Message: {$result->message}";
                }

                if($this->verbose && $result->exception instanceof Throwable) {
                    echo $result->exception->getTraceAsString();
                }

                echo " (" . number_format($result->duration, 3) . "s)" . PHP_EOL;

                break;
        }
    }

    /**
     * Prints the summary of the test result.
     * @param TestSummary $summary The test result object to be summarized
     * @return void
     */
    #[Override] public function printSummary(TestSummary $summary): void {
        echo PHP_EOL;
        echo "Test run completed!" . PHP_EOL;
        echo PHP_EOL;
        echo "Summary:" . PHP_EOL;
        echo "Total: {$summary->getTotal()} | " .
            $this->formatter->green("Passed: {$summary->getPassed()}") . " | " .
            $this->formatter->red("Failed: {$summary->getFailed()}") . " | " .
            $this->formatter->yellow("Skipped: {$summary->getSkipped()}") . " | " .
            "Duration: " . number_format($summary->getDuration(), 3) . "s" . PHP_EOL;
        echo "####################################################" . PHP_EOL;
    }
}