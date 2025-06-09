<?php

namespace ArcTest\Core;

use ArcTest\Enum\TestOutcome;

class TestTracker {
    /**
     * Applies the test result to the test summary by updating the summary counts based on the result outcome.
     * @param TestSummary $summary The summary object to update with the test result.
     * @param TestResult $result The result of the test to apply to the summary.
     * @return void
     */
    public function apply(TestSummary $summary, TestResult $result): void {
        $summary->incrementTotal();

        match($result->outcome) {
            TestOutcome::PASSED => $summary->incrementPassed(),
            TestOutcome::FAILED => $summary->incrementFailed(),
            TestOutcome::SKIPPED => $summary->incrementSkipped()
        };
    }
}