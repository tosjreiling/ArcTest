<?php

namespace ArcTest\Contracts;

use ArcTest\Core\TestResult;
use ArcTest\Core\TestSummary;

interface ResultPrinterInterface {
    /**
     * Start the process. This method initiates the process flow and triggers the beginning of the operation.
     * @return void
     */
    public function start(): void;

    /**
     * Prints the details of the test result.
     * @param TestResult $result The test result object to be printed
     * @return void
     */
    public function printTestResult(TestResult $result): void;

    /**
     * Prints the summary of the test result.
     * @param TestSummary $summary The test result object to be summarized
     * @return void
     */
    public function printSummary(TestSummary $summary): void;
}