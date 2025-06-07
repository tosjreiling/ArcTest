<?php

namespace ArcTest\Core;

class TestResultCollection {
    private array $results = [];

    public function add(TestResult $result): void {
        $this->results[] = $result;
    }

    public function all(): array {
        return $this->results;
    }
}