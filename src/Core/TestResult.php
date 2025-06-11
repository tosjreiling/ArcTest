<?php

namespace ArcTest\Core;

use ArcTest\Enum\TestOutcome;
use ArcTest\Exceptions\AssertionFailedException;
use Throwable;

readonly class TestResult {
    public string $className;
    public string $method;
    public TestOutcome $outcome;
    public string $message;
    public ?Throwable $exception;
    public float $duration;

    /**
     * Constructor for the class.
     * @param string $className Name of the class.
     * @param string $method Name of the method.
     * @param TestOutcome $outcome Outcome of the test.
     * @param string $message Optional message related to the test.
     * @param Throwable|null $exception Optional exception related to the test.
     * @param float $duration Duration of the test in seconds.
     * @return void
     */
    public function __construct(string $className, string $method, TestOutcome $outcome, string $message = "", ?Throwable $exception = null, float $duration = 0.0) {
        $this->className = $className;
        $this->method = $method;
        $this->outcome = $outcome;
        $this->message = $message;
        $this->exception = $exception;
        $this->duration = $duration;
    }

    /**
     * Converts the current object state to an associative array.
     * @param bool $verbose If true, includes additional information in the array.
     * @return array The object state represented as an associative array.
     */
    public function toArray(bool $verbose = false): array {
        $record = [
            "class" => $this->className,
            "method" => $this->method,
            "outcome" => $this->outcome->value,
            "message" => $this->message,
            "exception" => null,
            "trace" => null,
            "expected" => null,
            "actual" => null
        ];

        if($this->exception instanceof AssertionFailedException) {
            $record["expected"] = $this->exception->getExpected();
            $record["actual"] = $this->exception->getActual();
            $record["message"] = $this->exception->getMessage();
        }

        if($this->exception instanceof Throwable) {
            $record["exception"] = $this->exception->getMessage();
            $record["trace"] = $this->exception->getTraceAsString();
        }

        return $record;
    }
}