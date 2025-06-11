<?php

namespace ArcTest\Core;

/**
 * Class TestSummary
 */
class TestSummary {
    private int $total = 0;
    private int $passed = 0;
    private int $failed = 0;
    private int $skipped = 0;
    private float $duration = 0.0;

    /**
     * Increments the total count by 1.
     * @return void
     */
    public function incrementTotal(): void { $this->total++; }

    /**
     * Increments the number of passed items by 1.
     * @return void
     */
    public function incrementPassed(): void { $this->passed++; }

    /**
     * Increments the count of failed attempts by 1.
     * @return void
     */
    public function incrementFailed(): void { $this->failed++; }

    /**
     * Increments the skipped count by 1.
     * @return void
     */
    public function incrementSkipped(): void { $this->skipped++; }

    /**
     * Increments the current duration by the specified amount.
     * @param float $duration The duration to add.
     * @return void
     */
    public function incrementDuration(float $duration): void { $this->duration += $duration; }

    /**
     * Returns the total count.
     * @return int The total count.
     */
    public function getTotal(): int { return $this->total; }

    /**
     * Returns the number of times the method has passed.
     * @return int The number of times the method has passed.
     */
    public function getPassed(): int { return $this->passed; }

    /**
     * Retrieves the number of failed attempts.
     * @return int The number of failed attempts.
     */
    public function getFailed(): int { return $this->failed; }

    /**
     * Retrieves the number of skipped items.
     * @return int The number of skipped items.
     */
    public function getSkipped(): int { return $this->skipped; }

    /**
     * Retrieves the duration value.
     * @return float The duration value.
     */
    public function getDuration(): float { return $this->duration; }

    /**
     * Checks if there are any failures.
     * @return bool Returns true if there are failures, false otherwise.
     */
    public function hasFailures(): bool { return $this->failed > 0; }
}