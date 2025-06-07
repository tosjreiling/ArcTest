<?php

namespace ArcTest\Core;

use ArcTest\Enum\TestOutcome;
use Throwable;

readonly class TestResult {
    public string $className;
    public string $method;
    public TestOutcome $outcome;
    public string $message;
    public ?Throwable $exception;
    public function __construct(string $className, string $method, TestOutcome $outcome, string $message = "", ?Throwable $exception = null) {
        $this->className = $className;
        $this->method = $method;
        $this->outcome = $outcome;
        $this->message = $message;
        $this->exception = $exception;
    }
}