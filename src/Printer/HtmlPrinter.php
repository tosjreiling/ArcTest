<?php

namespace ArcTest\Printer;

use ArcTest\Contracts\ResultPrinterInterface;
use ArcTest\Core\TestResult;
use ArcTest\Core\TestSummary;
use ArcTest\Enum\TestOutcome;
use Override;

class HtmlPrinter implements ResultPrinterInterface {
    private string $output;
    private array $results;

    public function __construct(string $output = "output/report.html") {
        $this->output = $output;
    }

    /**
     * Start the process. This method initiates the process flow and triggers the beginning of the operation.
     * @return void
     */
    #[Override] public function start(): void {
        $this->results = [];
    }

    /**
     * Prints the details of the test result.
     * @param TestResult $result The test result object to be printed
     * @return void
     */
    #[Override] public function printTestResult(TestResult $result): void {
        $this->results[] = $result;
    }

    /**
     * Prints the summary of the test result.
     * @param TestSummary $summary The test result object to be summarized
     * @return void
     */
    #[Override] public function printSummary(TestSummary $summary): void {
        $html = $this->buildHtml($summary);
        file_put_contents($this->output, $html);
    }

    /**
     * Generates an HTML report for a given test summary and its associated results.
     * @param TestSummary $summary The summary data of the test run, including totals for passed, failed, and skipped tests.
     * @return string The complete HTML string representation of the test report.
     */
    private function buildHtml(TestSummary $summary): string {
        $rows = '';
        foreach ($this->results as $result) {
            $class = match ($result->outcome) {
                TestOutcome::PASSED => 'passed',
                TestOutcome::FAILED => 'failed',
                TestOutcome::SKIPPED => 'skipped'
            };
            $time = number_format($result->duration, 3);
            $rows .= <<<HTML
            <tr class="$class">
                <td>{$result->className}</td>
                <td>{$result->method}</td>
                <td>{$result->outcome->value}</td>
                <td>{$result->message}</td>
                <td>{$time}s</td>
            </tr>
            HTML;
                    }

                    return <<<HTML
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>ArcTest Report</title>
                <style>
                    body { font-family: Arial,serif; padding: 20px; }
                    table { border-collapse: collapse; width: 100%; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    tr.passed { background-color: #e6ffe6; }
                    tr.failed { background-color: #ffe6e6; }
                    tr.skipped { background-color: #fffce6; }
                    th { background-color: #333; color: white; }
                </style>
            </head>
            <body>
                <h1>ArcTest Report</h1>
                <p><strong>Total:</strong> {$summary->getTotal()}, <strong>Passed:</strong> {$summary->getPassed()}, <strong>Failed:</strong> {$summary->getFailed()}, <strong>Skipped:</strong> {$summary->getSkipped()}</p>
                <table>
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Method</th>
                            <th>Outcome</th>
                            <th>Message</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        $rows
                    </tbody>
                </table>
            </body>
            </html>
            HTML;
    }
}