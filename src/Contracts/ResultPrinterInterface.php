<?php

namespace ArcTest\Contracts;

use ArcTest\Core\TestResult;
use ArcTest\Enum\TestOutcome;
use Throwable;

interface ResultPrinterInterface {
    /**
     * Start the process. This method initiates the process flow and triggers the beginning of the operation.
     * @return void
     */
    public function start(): void;

    /**
     * Print the test result for a specific test case.
     * @param string $className The name of the test class.
     * @param string $methodName The name of the test method.
     * @param TestOutcome $outcome The outcome of the test (e.g., Pass, Fail).
     * @param string $message Additional message to provide more details about the test result (optional).
     * @param Throwable|null $exception
     * @return void
     */
    public function printTestResult(string $className, string $methodName, TestOutcome $outcome, string $message = "", Throwable $exception = null): void;

    /**
     * Prints the summary of the test result.
     * @param TestResult $result The test result object to be summarized
     * @return void
     */
    public function printSummary(TestResult $result): void;
}