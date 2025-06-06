<?php

namespace ArcTest\Printer;

use ArcTest\Contracts\ResultPrinterInterface;
use ArcTest\Core\TestResult;
use ArcTest\Enum\TestOutcome;
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
     * Print the test result for a specific test case.
     * @param string $className
     * @param string $methodName
     * @param TestOutcome $outcome
     * @param string $message
     * @param Throwable|null $exception
     * @return void
     */
    #[\Override] public function printTestResult(string $className, string $methodName, TestOutcome $outcome, string $message = "", Throwable $exception = null): void {
        switch($outcome) {
            case TestOutcome::PASSED:
                echo "PASSED: {$className}::{$methodName}" . PHP_EOL;
                break;
            case TestOutcome::SKIPPED:
                echo "SKIPPED: {$className}::{$methodName}";
                if(!empty($message)) echo "({$message})";
                echo PHP_EOL;
                break;
            case TestOutcome::FAILED:
                echo "FAILED: {$className}::{$methodName}";
                if(!empty($message)) echo " - {$message}";
                echo PHP_EOL;
                if($this->verbose && !is_null($exception)) echo $exception->getTraceAsString() . PHP_EOL;
                break;
        }
    }

    /**
     * Prints the summary of the test result.
     * @param TestResult $result The test result object to be summarized
     * @return void
     */
    #[\Override] public function printSummary(TestResult $result): void {
        echo PHP_EOL;
        echo "Test run completed!" . PHP_EOL;
        echo PHP_EOL;
        echo "Summary:" . PHP_EOL;
        echo "Total: {$result->getTotal()} | Passed: {$result->getPassed()} | Failed: {$result->getFailed()} | Skipped: {$result->getSkipped()}" . PHP_EOL;
    }
}