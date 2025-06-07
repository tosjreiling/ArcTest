<?php

namespace ArcTest\Printer;

use ArcTest\Contracts\ResultPrinterInterface;
use ArcTest\Core\TestResult;
use ArcTest\Core\TestSummary;
use ArcTest\Enum\TestOutcome;
use Throwable;

class JsonPrinter implements ResultPrinterInterface {
    private array $results = [];

    /**
     * Start the process. This method initiates the process flow and triggers the beginning of the operation.
     * @return void
     */
    #[\Override] public function start(): void {}

    /**
     * Prints the details of the test result.
     * @param TestResult $result The test result object to be printed
     * @return void
     */
    #[\Override] public function printTestResult(TestResult $result): void {
        $this->results[] = [
            "class" => $result->className,
            "method" => $result->method,
            "outcome" => $result->outcome->value,
            "message" => $result->message,
            "exception" => $result->exception->getMessage(),
            "trace" => $result->exception->getTraceAsString()
        ];
    }

    /**
     * Prints the summary of the test result.
     * @param TestSummary $summary The test result object to be summarized
     * @return void
     */
    #[\Override] public function printSummary(TestSummary $summary): void {
        $summary = [
            "summary" => [
                "total" => $summary->getTotal(),
                "passed" => $summary->getPassed(),
                "failed" => $summary->getFailed(),
                "skipped" => $summary->getSkipped()
            ],
            "tests" => $this->results
        ];

        echo json_encode($summary, JSON_PRETTY_PRINT) . PHP_EOL;
    }
}