<?php

namespace ArcTest\Printer;

use ArcTest\Contracts\ResultPrinterInterface;
use ArcTest\Core\TestResult;
use ArcTest\Core\TestSummary;
use ArcTest\Enum\TestOutcome;
use ArcTest\Exceptions\AssertionFailedException;
use Throwable;

class ConsolePrinter implements ResultPrinterInterface {
    private bool $verbose;

    public function __construct(bool $verbose = false) {
        $this->verbose = $verbose;
    }

    /**
     * Start the process. This method initiates the process flow and triggers the beginning of the operation.
     * @return void
     */
    #[\Override] public function start(): void {
        echo "Running tests..." . PHP_EOL;
    }

    /**
     * Prints the details of the test result.
     * @param TestResult $result The test result object to be printed
     * @return void
     */
    #[\Override] public function printTestResult(TestResult $result): void {
        switch($result->outcome) {
            case TestOutcome::PASSED:
                echo "PASSED: {$result->className}::{$result->method}" . PHP_EOL;
                break;
            case TestOutcome::SKIPPED:
                echo "SKIPPED: {$result->className}::{$result->method}";
                if(!empty($result->message)) echo " ({$result->message})";
                echo PHP_EOL;
                break;
            case TestOutcome::FAILED:
                echo "FAILED: {$result->className}::{$result->method}";

                if($result->exception instanceof AssertionFailedException) {
                    echo " Expected: " . var_export($result->exception->getExpected(), true) . " => Actual: " . var_export($result->exception->getActual(), true) . PHP_EOL;
                } else if(!empty($result->message)) {
                    echo " Message: {$result->message}" . PHP_EOL;
                }

                if($this->verbose && $result->exception instanceof Throwable) {
                    echo $result->exception->getTraceAsString() . PHP_EOL;
                }

                break;
        }
    }

    /**
     * Prints the summary of the test result.
     * @param TestSummary $summary The test result object to be summarized
     * @return void
     */
    #[\Override] public function printSummary(TestSummary $summary): void {
        echo PHP_EOL;
        echo "Test run completed!" . PHP_EOL;
        echo PHP_EOL;
        echo "Summary:" . PHP_EOL;
        echo "Total: {$summary->getTotal()} | Passed: {$summary->getPassed()} | Failed: {$summary->getFailed()} | Skipped: {$summary->getSkipped()}" . PHP_EOL;
    }
}