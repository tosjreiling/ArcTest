<?php

namespace ArcTest\Listener;

use ArcTest\Contracts\TestListenerInterface;
use ArcTest\Core\TestResult;
use ArcTest\Core\TestSuite;
use ArcTest\Core\TestSummary;
use Override;

class TestLoggerListener implements TestListenerInterface {

    /**
     * Triggered when the start action is performed.
     * @param string $class The name of the class where the start action is initiated.
     * @param string $method The name of the method being executed during the start action.
     * @return void
     */
    #[Override] public function onTestStart(string $class, string $method): void {
        echo "[LOGGER] Start test: {$class}::{$method}" . PHP_EOL;
    }

    /**
     * Handles the completion of a test and performs any necessary finalization steps.
     * @param TestResult $result The result object containing the outcome of the test.
     * @return void
     */
    #[Override] public function onTestEnd(TestResult $result): void {
        $duration = number_format($result->duration, 3);
        echo "[LOGGER] Finished test: {$result->className}::{$result->method} - {$result->outcome->name} ({$duration}s)" . PHP_EOL;
    }

    /**
     * Handles the actions to be performed when a test suite starts.
     * @param TestSuite $suite The test suite that is starting.
     * @return void
     */
    #[Override] public function onSuiteStart(TestSuite $suite): void {
        echo "[LOGGER] Test suite started." . PHP_EOL;
    }

    /**
     * Handles the end of a test suite execution.
     * @param TestSummary $summary Summary containing details about the test suite execution results.
     */
    #[Override] public function onSuiteEnd(TestSummary $summary): void {
        echo "[LOGGER] Test suite completed in {$summary->getDuration()}s" . PHP_EOL;
    }
}