<?php

namespace ArcTest\Core;

use ArcTest\Attributes\Depends;
use ArcTest\Enum\TestOutcome;
use ArcTest\Exceptions\SkipTestException;
use ReflectionException;
use ReflectionMethod;

/**
 * Handles the resolution of method dependencies during test execution.
 * Ensures that methods are executed in the correct order based on their dependencies,
 * and tracks the methods that have successfully passed.
 */
class DependencyChecker {
    private array $passed = [];

    /**
     * Records the provided method name as passed.
     * @param string $method The name of the method to record as passed.
     * @return void
     */
    public function record(string $method): void {
        $this->passed[] = $method;
    }

    /**
     * Determines if a test method should be skipped based on unmet dependencies.
     * If a required dependency has not been fulfilled, the test is marked as skipped.
     * @param TestCase $testInstance The test case instance containing the test method being evaluated.
     * @param string $method The name of the test method to be checked for dependency fulfillment.
     * @return TestResult|null A TestResult object representing the skipped state if dependencies are unmet, or null if all dependencies are satisfied.
     */
    public function skip(TestCase $testInstance, string $method): ?TestResult {
        $dependencies = $this->getDependencies($testInstance, $method);

        foreach($dependencies as $dependency) {
            if(!in_array($dependency, $this->passed, true)) {
                try {
                    $testInstance->skip("Dependency not met: '{$dependency}' for '{$method}'");
                } catch(SkipTestException $e) {
                    return new TestResult(get_class($testInstance), $method, TestOutcome::SKIPPED, $e->getMessage(), $e);
                }
            }
        }

        return null;
    }

    /**
     * Retrieves the list of dependencies for a given method from the specified test instance.
     * @param object $testInstance The instance of the class containing the method.
     * @param string $method The name of the method whose dependencies are to be retrieved.
     * @return array An array of dependent method names, or an empty array if none are found or an error occurs.
     */
    private function getDependencies(object $testInstance, string $method): array {
        try {
            $reflection = new ReflectionMethod($testInstance, $method);
            $attributes = $reflection->getAttributes(Depends::class);

            $all = [];
            foreach($attributes as $attr) {
                $instance = $attr->newInstance();
                $all = array_merge($all, $instance->methods);
            }

            return $all;
        } catch(ReflectionException $e) {
            return [];
        }
    }
}