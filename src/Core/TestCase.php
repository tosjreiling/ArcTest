<?php

namespace ArcTest\Core;

use ArcTest\Exceptions\AssertionFailedException;
use ArcTest\Exceptions\SkipTestException;
use Exception;

/**
 * Abstract class representing a test case structure.
 * Provides functionality for managing the state of a test case,
 * handling test setup and teardown, and asserting conditions during tests.
 */
abstract class TestCase {
    private bool $skipped = false;
    private string $skipMessage = "";
    private ?string $expectedException = null;

    /**
     * Skips the test.
     * @param string $message An optional message to display when skipping the test
     * @return void
     * @throws SkipTestException indicating that the test has been skipped
     */
    public function skip(string $message = "Test skipped"): void {
        throw new SkipTestException($message);
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
     * Sets the expected exception for the next test execution.
     * @param string $exception The name of the exception class that is expected to be thrown.
     * @return void
     */
    public function expectException(string $exception): void {
        $this->expectedException = $exception;
    }

    /**
     * Retrieves the expected exception message.
     * @return string|null The expected exception message.
     */
    public function getExpectedException(): ?string {
        return $this->expectedException;
    }

    /**
     * Asserts that a given condition is true. If the condition is false, an exception is thrown with the provided message.
     * @param bool $condition The condition to evaluate as true or false.
     * @param string $message An optional message to include in the exception if the assertion fails.
     * @return void
     * @throws AssertionFailedException Thrown if the condition evaluates to false.
     */
    protected function assertTrue(bool $condition, string $message = ""): void {
        if(!$condition) {
            throw new AssertionFailedException(true, false, $message ?: "Failed asserting that condition is true.");
        }
    }

    /**
     * Asserts that a given condition is false. If the condition evaluates to true, an exception
     * is thrown with the specified message or a default failure message.
     * @param bool $condition The condition to be evaluated.
     * @param string $message Optional custom message to include in the exception if the assertion fails.
     * @return void
     * @throws AssertionFailedException
     */
    protected function assertFalse(bool $condition, string $message = ""): void {
        if($condition) {
            throw new AssertionFailedException(false, true, $message ?: "Failed asserting that condition is false.");
        }
    }

    /**
     * Asserts that two values are equal. Throws an exception if the values are not equal.
     * @param mixed $expected The expected value.
     * @param mixed $actual The actual value to test against the expected value.
     * @param string $message The message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException
     */
    protected function assertEquals(mixed $expected, mixed $actual, string $message = ""): void {
        if($expected != $actual) {
            throw new AssertionFailedException($expected, $actual, $message);
        }
    }

    /**
     * Asserts that two values are identical.
     * @param mixed $expected The expected value.
     * @param mixed $actual The actual value to compare against the expected value.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the expected value is not the same as the actual value.
     */
    protected function assertSame(mixed $expected, mixed $actual, string $message = ""): void {
        if($expected !== $actual) {
            throw new AssertionFailedException($expected, $actual, $message);
        }
    }

    /**
     * Asserts that a value is null.
     * @param mixed $value The value to check.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the value is not null.
     */
    protected function assertNull(mixed $value, string $message = ""): void {
        if(is_null($value)) {
            throw new AssertionFailedException(null, $value, $message ?: "Failed asserting that value is null.");
        }
    }

    /**
     * Asserts that a value is not null.
     * @param mixed $value The value to check for non-nullness.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the given value is null.
     */
    protected function assertNotNull(mixed $value, string $message = ""): void {
        if(!is_null($value)) {
            throw new AssertionFailedException(null, $value, $message ?: "Failed asserting that value is not null.");
        }
    }

    /**
     * Asserts that a given value is empty.
     * @param mixed $value The value to check for emptiness.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided value is not empty.
     */
    protected function assertEmpty(mixed $value, string $message = ""): void {
        if(!empty($value)) {
            throw new AssertionFailedException("empty", $value, $message ?: "Failed asserting that value is empty.");
        }
    }

    /**
     * Asserts that a value is not empty.
     * @param mixed $value The value to check for emptiness.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided value is empty.
     */
    protected function assertNotEmpty(mixed $value, string $message = ""): void {
        if(empty($value)) {
            throw new AssertionFailedException("not empty", $value, $message ?: "Failed asserting that value is not empty.");
        }
    }

    /**
     * Asserts that a string contains another string.
     * @param string $needle The substring to search for within the haystack.
     * @param string $haystack The string to search within.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the needle string is not found within the haystack string.
     */
    protected function assertStringContains(string $needle, string $haystack, string $message = ""): void {
        if(!str_contains($haystack, $needle)) {
            throw new AssertionFailedException($needle, $haystack, $message ?: "Failed asserting that string contains [{$needle}] in [{$haystack}].");
        }
    }

    /**
     * Asserts that a string starts with a specific prefix.
     * @param string $prefix The prefix to check for at the start of the string.
     * @param string $string The string to check against for the specified prefix.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the string does not start with the specified prefix.
     */
    protected function assertStringStartsWith(string $prefix, string $string, string $message = ""): void {
        if(!str_starts_with($string, $prefix)) {
            throw new AssertionFailedException($prefix, $string, $message ?: "Failed asserting that string contains [{$prefix}] in [{$string}].");
        }
    }

    /**
     * Asserts that a string ends with a specified suffix.
     * @param string $suffix The expected suffix to check at the end of the string.
     * @param string $string The actual string value to check for the suffix.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the string does not end with the specified suffix.
     */
    protected function assertStringEndsWith(string $suffix, string $string, string $message = ""): void {
        if(!str_ends_with($string, $suffix)) {
            throw new AssertionFailedException($suffix, $string, $message ?: "Failed asserting that string ends [{$suffix}] in [{$string}].");
        }
    }

    /**
     * Asserts that a value is a string.
     * @param mixed $value The value to check if it is a string.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the value is not a string.
     */
    protected function assertIsString(mixed $value, string $message = ""): void {
        if(!is_string($value)) {
            throw new AssertionFailedException("string", gettype($value), $message ?: "Failed asserting that value is not string.");
        }
    }

    /**
     * Asserts that the given value is an integer.
     * @param mixed $value The value to be checked if it is an integer.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided value is not an integer.
     */
    protected function assertIsInt(mixed $value, string $message = ""): void {
        if(!is_int($value)) {
            throw new AssertionFailedException("int", gettype($value), $message ?: "Failed asserting that value is not int.");
        }
    }

    /**
     * Asserts that a value is of type float.
     * @param mixed $value The value to check if it is a float.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided value is not of type float.
     */
    protected function assertIsFloat(mixed $value, string $message = ""): void {
        if(!is_float($value)) {
            throw new AssertionFailedException("float", gettype($value), $message ?: "Failed asserting that value is float.");
        }
    }

    /**
     * Asserts that a given value is an array.
     * @param mixed $value The value to be checked if it is an array.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the given value is not an array.
     */
    protected function assertIsArray(mixed $value, string $message = ""): void {
        if(!is_array($value)) {
            throw new AssertionFailedException("array", gettype($value), $message ?: "Failed asserting that value is not array.");
        }
    }

    /**
     * Asserts that a value is an object.
     * @param mixed $value The value to check if it is an object.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the given value is not an object.
     */
    protected function assertIsObject(mixed $value, string $message = ""): void {
        if(!is_object($value)) {
            throw new AssertionFailedException("object", gettype($value), $message ?: "Failed asserting that value is not object.");
        }
    }

    /**
     * Asserts that a value is of type boolean.
     * @param mixed $value The value to check for boolean type.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided value is not of type boolean.
     */
    protected function assertIsBool(mixed $value, string $message = ""): void {
        if(!is_bool($value)) {
            throw new AssertionFailedException("bool", gettype($value), $message ?: "Failed asserting that value is not bool.");
        }
    }

    /**
     * Asserts that a value is callable.
     * @param mixed $value The value to check if it is callable.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided value is not callable.
     */
    protected function assertIsCallable(mixed $value, string $message = ""): void {
        if(!is_callable($value)) {
            throw new AssertionFailedException("callable", gettype($value), $message ?: "Failed asserting that value is callable.");
        }
    }

    /**
     * Asserts that a value is iterable.
     * @param mixed $value The value to check if it is iterable.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided value is not iterable.
     */
    protected function assertIsIterable(mixed $value, string $message = ""): void {
        if(!is_iterable($value)) {
            throw new AssertionFailedException("iterable", gettype($value), $message ?: "Failed asserting that value is not iterable.");
        }
    }

    /**
     * Asserts that a given value is a resource.
     * @param mixed $value The value to check if it is a resource.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided value is not a resource.
     */
    protected function assertIsResource(mixed $value, string $message = ""): void {
        if(!is_resource($value)) {
            throw new AssertionFailedException("resource", gettype($value), $message ?: "Failed asserting that value is not resource.");
        }
    }
}