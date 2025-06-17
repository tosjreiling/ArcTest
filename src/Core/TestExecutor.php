<?php

namespace ArcTest\Core;

use ArcTest\Enum\TestOutcome;
use ArcTest\Exceptions\AssertionFailedException;
use ArcTest\Exceptions\SkipTestException;
use Throwable;

/**
 * Handles the execution of test cases, managing their lifecycle and producing results.
 */
class TestExecutor {
    private LifeCycleManager $lifecycle;

    /**
     * Constructs a new instance and initializes it with the given lifecycle manager.
     * @param LifeCycleManager $lifecycle The lifecycle manager instance to manage the object's lifecycle
     * @return void
     */
    public function __construct(LifeCycleManager $lifecycle) {
        $this->lifecycle = $lifecycle;
    }

    /**
     * Runs a test case method and returns the result.
     * @param TestCase $test The test case object to run the method on
     * @param string $method The name of the method to run on the test case
     * @return TestResult The result of running the test method, indicating pass, fail, or skip
     */
    public function run(TestCase $test, string $method): TestResult {
        $className = get_class($test);
        $startTime = microtime(true);

        try {
            $this->lifecycle->beforeEach($test);
            $test->$method();

            if($test->getExpectedException() !== null) {
                return $this->createTestResult($className, $method, TestOutCome::FAILED, "Expected exception {$test->getExpectedException()} was not thrown", null, microtime(true) - $startTime);
            }

            return $this->createTestResult($className, $method, TestOutCome::PASSED, "Test passed", null, microtime(true) - $startTime);
        } catch(Throwable $e) {
            return $this->handleException($test, $method, $className, $e, $startTime);
        } finally {
            $this->lifecycle->afterEach($test);
        }
    }

    /**
     * Processes an exception during test case execution and generates a TestResult.
     * @param TestCase $test The test case instance in which the exception occurred.
     * @param string $method The name of the method being executed.
     * @param string $className The name of the class containing the test method.
     * @param Throwable $e The exception that was thrown during execution.
     * @param float $startTime The start time of the test execution in microseconds.
     * @return TestResult The result of the test execution, including the handled exception's outcome.
     */
    private function handleException(TestCase $test, string $method, string $className, Throwable $e, float $startTime): TestResult {
        $executionTime = microtime(true) - $startTime;

        if($e instanceof SkipTestException) return $this->createTestResult($className, $method, TestOutcome::SKIPPED, $e->getMessage(), $e, $executionTime);
        if($e instanceof AssertionFailedException) return $this->createTestResult($className, $method, TestOutcome::FAILED, $e->getMessage(), $e, $executionTime);

        $expectedException = $test->getExpectedException();
        if($expectedException !== null && is_a($e, $expectedException)) return $this->createTestResult($className, $method, TestOutcome::PASSED, "(expected exception {$expectedException})", null, $executionTime);

        return $this->createTestResult($className, $method, TestOutcome::FAILED, $e->getMessage(), $e, $executionTime);
    }

    /**
     * Creates a test result instance based on the provided parameters.
     * @param string $className The name of the class where the test is defined
     * @param string $method The name of the test method
     * @param TestOutcome $outcome The outcome of the test (e.g., passed, failed, skipped)
     * @param string $message A message providing details about the test result
     * @param Throwable|null $exception An optional exception thrown during the test
     * @param float $duration The start time of the test execution
     * @return TestResult An instance representing the test result, including outcome and execution details
     */
    private function createTestResult(string $className, string $method, TestOutcome $outcome, string $message, ?Throwable $exception, float $duration): Testresult {
        return new TestResult($className, $method, $outcome, $message, $exception, $duration);
    }
}