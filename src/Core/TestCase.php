<?php

namespace ArcTest\Core;

use ArcTest\Enum\AssertType;
use ArcTest\Exceptions\AssertionFailedException;
use ArcTest\Exceptions\SkipTestException;
use Countable;

/**
 * Abstract class representing a test case structure.
 * Provides functionality for managing the state of a test case,
 * handling test setup and teardown, and asserting conditions during tests.
 */
abstract class TestCase {
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
     * Executes setup tasks or operations that should occur before all test cases are run.
     * Typically used to initialize shared resources or configurations.
     * @return void
     */
    public static function beforeAll(): void {}

    /**
     * Executes a set of actions or cleanup tasks to run after all tests or operations have completed.
     * @return void
     */
    public static function afterAll(): void {}

    /**
     * Method to set up conditions or initialize resources required before executing each test case.
     * @return void
     */
    public function beforeEach(): void {}

    /**
     * Executes any finalization or cleanup logic after each test.
     * @return void
     */
    public function afterEach(): void {}

    /**
     * Triggers a failure with a specified message.
     * @param string $message An optional message describing the reason for the failure.
     * @return void
     * @throws AssertionFailedException Always thrown to indicate a failure.
     */
    protected function fail(string $message = "Test Failed"): void {
        throw new AssertionFailedException("Failure", "Triggered", $message);
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
            throw new AssertionFailedException(false, true, $message);
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
     * Asserts that two values are not equal.
     * @param mixed $expected The value that the actual value is expected not to equal.
     * @param mixed $actual The value to compare against the expected value.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the expected and actual values are equal.
     */
    protected function assertNotEquals(mixed $expected, mixed $actual, string $message = ""): void {
        if($expected == $actual) {
            throw new AssertionFailedException($expected, $actual, $message);
        }
    }

    /**
     * Asserts that a value is greater than an expected value.
     * @param mixed $expected The value that the actual value is expected to be greater than.
     * @param mixed $actual The value to be evaluated.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the actual value is not greater than the expected value.
     */
    protected function assertGreaterThan(mixed $expected, mixed $actual, string $message = ""): void {
        if($actual <= $expected) {
            throw new AssertionFailedException($expected, $actual, $message);
        }
    }

    /**
     * Asserts that a value is greater than or equal to another value.
     * @param mixed $expected The expected value to compare against.
     * @param mixed $actual The actual value to compare.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the actual value is less than the expected value.
     */
    protected function assertGreaterThanOrEqual(mixed $expected, mixed $actual, string $message = ""): void {
        if($actual < $expected) {
            throw new AssertionFailedException($expected, $actual, $message);
        }
    }

    /**
     * Asserts that a value is less than the expected value.
     * @param mixed $expected The value that the actual value should be less than.
     * @param mixed $actual The value to test.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the actual value is not less than the expected value.
     */
    protected function assertLessThan(mixed $expected, mixed $actual, string $message = ""): void {
        if($actual >= $expected) {
            throw new AssertionFailedException($expected, $actual, $message);
        }
    }

    /**
     * Asserts that a value is less than or equal to an expected value.
     * @param mixed $expected The expected value to compare against.
     * @param mixed $actual The actual value to be compared.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the actual value is greater than the expected value.
     */
    protected function assertLessThanOrEqual(mixed $expected, mixed $actual, string $message = ""): void {
        if($actual > $expected) {
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
     * Asserts that two values are not the same.
     * @param mixed $expected The expected value for comparison.
     * @param mixed $actual The actual value to compare against the expected value.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the expected value is the same as the actual value.
     */
    protected function assertNotSame(mixed $expected, mixed $actual, string $message = ""): void {
        if($expected === $actual) {
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
        if(!is_null($value)) {
            throw new AssertionFailedException(null, $value, $message);
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
        if(is_null($value)) {
            throw new AssertionFailedException("not null", $value, $message ?: "Failed asserting that value is not null.");
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
     * Asserts that an array contains a specific value.
     * @param mixed $needle The value to search for in the array.
     * @param array $hack The array in which to search for the value.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the array does not contain the specified value.
     */
    protected function assertArrayContains(mixed $needle, array $hack, string $message = ""): void {
        if(!in_array($needle, $hack, true)) {
            throw new AssertionFailedException($needle, $hack, $message ?: "Failed asserting that array contains [{$needle}].");
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

    protected function assertStringNotContains(string $needle, string $haystack, string $message = ""): void {
        if(str_contains($haystack, $needle)) {
            throw new AssertionFailedException($needle, $haystack, $message ?: "Failed asserting that string does not contain [{$needle}] in [{$haystack}].");
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
        $this->assertType(AssertType::IS_STRING, $value, $message);
    }

    /**
     * Asserts that the given value is an integer.
     * @param mixed $value The value to be checked if it is an integer.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided value is not an integer.
     */
    protected function assertIsInt(mixed $value, string $message = ""): void {
        $this->assertType(AssertType::IS_INT, $value, $message);
    }

    /**
     * Asserts that a value is of type float.
     * @param mixed $value The value to check if it is a float.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided value is not of type float.
     */
    protected function assertIsFloat(mixed $value, string $message = ""): void {
        $this->assertType(AssertType::IS_FLOAT, $value, $message);
    }

    /**
     * Asserts that a given value is an array.
     * @param mixed $value The value to be checked if it is an array.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the given value is not an array.
     */
    protected function assertIsArray(mixed $value, string $message = ""): void {
        $this->assertType(AssertType::IS_ARRAY, $value, $message);
    }

    /**
     * Asserts that a value is an object.
     * @param mixed $value The value to check if it is an object.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the given value is not an object.
     */
    protected function assertIsObject(mixed $value, string $message = ""): void {
        $this->assertType(AssertType::IS_OBJECT, $value, $message);
    }

    /**
     * Asserts that a value is of type boolean.
     * @param mixed $value The value to check for boolean type.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided value is not of type boolean.
     */
    protected function assertIsBool(mixed $value, string $message = ""): void {
        $this->assertType(AssertType::IS_BOOL, $value, $message);
    }

    /**
     * Asserts that a value is callable.
     * @param mixed $value The value to check if it is callable.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided value is not callable.
     */
    protected function assertIsCallable(mixed $value, string $message = ""): void {
        $this->assertType(AssertType::IS_CALLABLE, $value, $message);
    }

    /**
     * Asserts that a value is iterable.
     * @param mixed $value The value to check if it is iterable.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided value is not iterable.
     */
    protected function assertIsIterable(mixed $value, string $message = ""): void {
        $this->assertType(AssertType::IS_ITERABLE, $value, $message);
    }

    /**
     * Asserts that a given value is a resource.
     * @param mixed $value The value to check if it is a resource.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided value is not a resource.
     */
    protected function assertIsResource(mixed $value, string $message = ""): void {
        $this->assertType(AssertType::IS_RESOURCE, $value, $message);
    }

    /**
     * Asserts that an object is an instance of the specified class.
     * @param string $class The fully qualified name of the class to check against.
     * @param mixed $object The object to verify.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the object is not an instance of the specified class.
     */
    protected function assertInstanceOf(string $class, mixed $object, string $message = ""): void {
        if(!($object instanceof $class)) {
            $actual = is_object($object) ? get_class($object) : gettype($object);
            throw new AssertionFailedException($class, $actual, $message ?: "Failed asserting that object is an instance of [{$class}].");
        }
    }

    /**
     * Asserts that an array contains the specified key.
     * @param mixed $key The key to check for in the array.
     * @param array $array The array in which to search for the key.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the array does not contain the specified key.
     */
    protected function assertArrayHasKey(mixed $key, array $array, string $message = ""): void {
        if(!array_key_exists($key, $array)) {
            throw new AssertionFailedException($key, $array, $message ?: "Failed asserting that array has key [{$key}].");
        }
    }

    /**
     * Asserts that an array does not contain the specified key.
     * @param mixed $key The key to check for absence in the array.
     * @param array $array The array to be evaluated.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided key exists in the array.
     */
    protected function assertArrayNotHasKey(mixed $key, array $array, string $message = ""): void {
        if(array_key_exists($key, $array)) {
            throw new AssertionFailedException($key, $array, $message ?: "Failed asserting that array does not have key [{$key}].");
        }
    }

    /**
     * Asserts that the count of a given value matches the expected count.
     * @param int $expected The expected count.
     * @param int|array $value The value to check, either an integer or an array.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the count does not match the expected value.
     */
    protected function assertCount(int $expected, int|array $value, string $message = ""): void {
        $count = is_array($value) ? count($value) : $value;
        $this->assertSame($expected, $count, $message);
    }

    /**
     * Asserts that a value is of a specified type.
     * @param AssertType $type An object representing the type to check against.
     * @param mixed $value The value to check for type conformity.
     * @param string $message An optional message to display if the assertion fails.
     * @return void
     * @throws AssertionFailedException If the provided value does not match the specified type.
     */
    private function assertType(AssertType $type, mixed $value, string $message = ""): void {
        $function = $type->value;
        $type = str_replace("is_", "", $function);

        if(!function_exists($function) || !$function($value)) {
            throw new AssertionFailedException($type, gettype($value), $message ?: "Failed asserting that value is of type [{$type}].");
        }
    }
}