<?php

namespace ArcTest\Printer;

use ArcTest\Contracts\ResultPrinterInterface;
use ArcTest\Core\TestResult;
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
     * Print the test result for a specific test case.
     * @param string $className The name of the test class.
     * @param string $methodName The name of the test method.
     * @param TestOutcome $outcome The outcome of the test (e.g., Pass, Fail).
     * @param string $message Additional message to provide more details about the test result (optional).
     * @param Throwable|null $exception
     * @return void
     */
    #[\Override] public function printTestResult(string $className, string $methodName, TestOutcome $outcome, string $message = "", Throwable $exception = null): void {
        $this->results[] = [
            "class" => $className,
            "method" => $methodName,
            "outcome" => $outcome->value,
            "message" => $message,
            "exception" => $exception,
            "trace" => $exception?->getTraceAsString()
        ];
    }

    /**
     * Prints the summary of the test result.
     * @param TestResult $result The test result object to be summarized
     * @return void
     */
    #[\Override] public function printSummary(TestResult $result): void {
        $summary = [
            "summary" => [
                "total" => $result->getTotal(),
                "passed" => $result->getPassed(),
                "failed" => $result->getFailed(),
                "skipped" => $result->getSkipped()
            ],
            "tests" => $this->results
        ];

        echo json_encode($summary, JSON_PRETTY_PRINT) . PHP_EOL;
    }
}