<?php

namespace ArcTest\Core;

use ArcTest\Enum\RecordKey;
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
            RecordKey::CLASSNAME->value => $this->className,
            RecordKey::METHOD->value => $this->method,
            RecordKey::OUTCOME->value => $this->outcome->value,
            RecordKey::MESSAGE->value => $this->message,
            RecordKey::EXCEPTION->value => null,
            RecordKey::TRACE->value => null,
            RecordKey::EXPECTED->value => null,
            RecordKey::ACTUAL->value => null
        ];

        if($this->exception instanceof AssertionFailedException) {
            $record[RecordKey::EXPECTED->value ] = $this->exception->getExpected();
            $record[RecordKey::ACTUAL->value ] = $this->exception->getActual();
            $record[RecordKey::MESSAGE->value ] = $this->exception->getMessage();
        }

        if($this->exception instanceof Throwable) {
            $record[RecordKey::EXCEPTION->value ] = $this->exception->getMessage();
            $record[RecordKey::TRACE->value ] = $this->exception->getTraceAsString();
        }

        return $record;
    }
}