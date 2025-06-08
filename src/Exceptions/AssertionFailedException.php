<?php

namespace ArcTest\Exceptions;

use Exception;

class AssertionFailedException extends Exception {
    private mixed $expected;
    private mixed $actual;

    public function __construct(mixed $expected, mixed $actual, string $message = "") {
        $this->expected = $expected;
        $this->actual = $actual;

        $default = "Failed asserting that [" . var_export($actual, true) . "] equals [" . var_export($expected, true) . "]";
        parent::__construct($message ?: $default);
    }

    public function getExpected(): mixed {
        return $this->expected;
    }

    public function getActual(): mixed {
        return $this->actual;
    }
}