<?php

namespace ArcTest\Core;

use Exception;

/**
 * Abstract class representing a test case structure.
 * Provides functionality for managing the state of a test case,
 * handling test setup and teardown, and asserting conditions during tests.
 */
abstract class TestCase {
    private bool $skipped = false;
    private string $skipMessage = "";

    /**
     * Marks the current test as skipped and sets a skip message.
     * @param string $message The message explaining why the test is skipped. Defaults to "Test skipped".
     * @return void
     */
    public function skip(string $message = "Test skipped"): void {
        $this->skipped = true;
        $this->skipMessage = $message;
    }

    /**
     * Determines whether the current test is marked as skipped.
     * @return bool True if the test is skipped, otherwise false.
     */
    public function isSkipped(): bool {
        return $this->skipped;
    }

    /**
     * Retrieves the skip message for the current test.
     * @return string The message explaining why the test was skipped.
     */
    public function getSkipMessage(): string {
        return $this->skipMessage;
    }

    /**
     * Prepares the environment before executing each test.
     * @return void
     */
    public function setUp(): void {}

    /**
     * Cleans up the environment after executing each test.
     * @return void
     */
    public function tearDown(): void {}

    /**
     * Asserts that a given condition is true. If the condition is false, an exception is thrown with the provided message.
     * @param bool $condition The condition to evaluate as true or false.
     * @param string $message An optional message to include in the exception if the assertion fails.
     * @return void
     * @throws Exception Thrown if the condition evaluates to false.
     */
    protected function assertTrue(bool $condition, string $message = ""): void {
        if(!$condition) {
            throw new Exception($message ?: "Failed asserting that condition is true.");
        }
    }

    /**
     * Asserts that a given condition is false. If the condition evaluates to true, an exception
     * is thrown with the specified message or a default failure message.
     * @param bool $condition The condition to be evaluated.
     * @param string $message Optional custom message to include in the exception if the assertion fails.
     * @return void
     * @throws Exception
     */
    protected function assertFalse(bool $condition, string $message = ""): void {
        if($condition) {
            throw new Exception($message ?: "Failed asserting that condition is false.");
        }
    }

    /**
     * Asserts that two values are equal. Throws an exception if the values are not equal.
     * @param mixed $expected The expected value.
     * @param mixed $actual The actual value to test against the expected value.
     * @param string $message The message to display if the assertion fails.
     * @return void
     * @throws Exception
     */
    protected function assertEquals(mixed $expected, mixed $actual, string $message = ""): void {
        if($expected != $actual) {
            throw new Exception($message ?: "Failed asserting that [{$actual}] is equal to [{$expected}].");
        }
    }

    /**
     * Asserts that two values are identical.
     * @param mixed $expected The expected value.
     * @param mixed $actual The actual value to compare against the expected value.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws Exception If the expected value is not the same as the actual value.
     */
    protected function assertSame(mixed $expected, mixed $actual, string $message = ""): void {
        if($expected !== $actual) {
            throw new Exception($message ?: "Failed asserting that [{$actual}] is identical as [{$expected}].");
        }
    }

    /**
     * Asserts that a value is null.
     * @param mixed $value The value to check.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws Exception If the value is not null.
     */
    protected function assertNull(mixed $value, string $message = ""): void {
        if(is_null($value)) {
            throw new Exception($message ?: "Failed asserting that value is null.");
        }
    }

    /**
     * Asserts that a value is not null.
     * @param mixed $value The value to check for non-nullness.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws Exception If the given value is null.
     */
    protected function assertNotNull(mixed $value, string $message = ""): void {
        if(!is_null($value)) {
            throw new Exception($message ?: "Failed asserting that value is not null.");
        }
    }
}