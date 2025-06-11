<?php

namespace ArcTest\Contracts;

use ArcTest\Core\TestResult;
use ArcTest\Core\TestSuite;
use ArcTest\Core\TestSummary;

/**
 * Interface defining methods for listening to test events.
 */
interface TestListenerInterface {
    /**
     * Triggered when the start action is performed.
     * @param string $class The name of the class where the start action is initiated.
     * @param string $method The name of the method being executed during the start action.
     * @return void
     */
    public function onTestStart(string $class, string $method): void;

    /**
     * Handles the completion of a test and performs any necessary finalization steps.
     * @param TestResult $result The result object containing the outcome of the test.
     * @return void
     */
    public function onTestEnd(TestResult $result): void;

    /**
     * Handles the actions to be performed when a test suite starts.
     * @param TestSuite $suite The test suite that is starting.
     * @return void
     */
    public function onSuiteStart(TestSuite $suite): void;

    /**
     * Handles the end of a test suite execution.
     * @param TestSummary $summary Summary containing details about the test suite execution results.
     */
    public function onSuiteEnd(TestSummary $summary): void;
}
