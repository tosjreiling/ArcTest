<?php

namespace ArcTest\Core;

use ArcTest\Enum\TestOutcome;
use ArcTest\Exceptions\AssertionFailedException;
use ArcTest\Exceptions\SkipTestException;
use Throwable;

class TestExecutor {
    /**
     * Runs a test case method and returns the result.
     * @param TestCase $test The test case object to run the method on
     * @param string $method The name of the method to run on the test case
     * @return TestResult The result of running the test method, indicating pass, fail, or skip
     */
    public function run(TestCase $test, string $method): TestResult {
        $class = get_class($test);

        try {
            $test->setUp();
            $test->$method();

            if($test->getExpectedException() !== null) {
                return new TestResult($class, $method, TestOutcome::FAILED, "Expected exception {$test->getExpectedException()} was not thrown");
            }

            return new TestResult($class, $method, TestOutcome::PASSED);
        } catch (SkipTestException $e) {
            return new TestResult($class, $method, TestOutcome::SKIPPED, $e->getMessage(), $e);
        } catch (AssertionFailedException $e) {
            return new TestResult($class, $method, TestOutcome::FAILED, $e->getMessage(), $e);
        } catch (Throwable $e) {
            if($test->getExpectedException() !== null && is_a($e, $test->getExpectedException())) {
                return new TestResult($class, $method, TestOutcome::PASSED, "(expected exception {$test->getExpectedException()})");
            }

            return new TestResult($class, $method, TestOutcome::FAILED, $e->getMessage(), $e);
        } finally {
            $test->tearDown();
        }
    }
}