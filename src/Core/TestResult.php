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

    /**
     * Initializes a new instance of the class.
     * @param string $className The name of the class being tested.
     * @param string $method The name of the method being tested.
     * @param TestOutcome $outcome The outcome of the test.
     * @param string $message A message related to the test (default is an empty string).
     * @param Throwable|null $exception An optional exception that occurred during the test (default is null).
     * @return void
     */
    public function __construct(string $className, string $method, TestOutcome $outcome, string $message = "", ?Throwable $exception = null) {
        $this->className = $className;
        $this->method = $method;
        $this->outcome = $outcome;
        $this->message = $message;
        $this->exception = $exception;
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