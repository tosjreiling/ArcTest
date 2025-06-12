<?php

namespace ArcTest\Printer;

use ArcTest\Contracts\ResultPrinterInterface;
use ArcTest\Core\TestResult;
use ArcTest\Core\TestSummary;
use ArcTest\Enum\TestOutcome;
use DOMDocument;
use Override;
use RuntimeException;
use SimpleXMLElement;
use Throwable;

class JUnitXmlPrinter implements ResultPrinterInterface {
    private array $results;
    private string $output;

    public function __construct(string $output = "php://stdout") {
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
        $xml = new SimpleXMLElement("<testsuites></testsuites>");
        $suite = $xml->addChild("testsuite");
        $suite->addAttribute("name", "ArcTest");
        $suite->addAttribute("tests", $summary->getTotal());
        $suite->addAttribute("failures", $summary->getFailed());
        $suite->addAttribute("skipped", $summary->getSkipped());
        $suite->addAttribute("time", number_format($summary->getDuration(), 3));

        foreach($this->results as $result) {
            /* @var TestResult $result */
            $testCase = $suite->addChild("testcase");
            $testCase->addAttribute("classname", $result->className);
            $testCase->addAttribute("name", $result->method);
            $testCase->addAttribute("time", number_format($result->duration, 3));

            if($result->outcome === TestOutcome::FAILED) {
                $failure = $testCase->addChild("failure", htmlspecialchars($result->message));
                $failure->addAttribute("type", "AssertionFailed");

                if($result->exception instanceof Throwable) {
                    $failure->addChild("stacktrace", htmlspecialchars($result->exception->getTraceAsString()));
                }
            }

            if($result->outcome === TestOutcome::SKIPPED) {
                $skipped = $testCase->addChild("skipped");
                $skipped->addAttribute("message", htmlspecialchars($result->message));
            }
        }

        if (str_starts_with($this->output, 'php://') === false) {
            $dir = dirname($this->output);
            if (!is_dir($dir)) {
                if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
                    throw new RuntimeException("Failed to create output directory: $dir");
                }
            }
        }

        $dom = new DomDocument("1.0");
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        file_put_contents($this->output, $dom->saveXML());
    }
}