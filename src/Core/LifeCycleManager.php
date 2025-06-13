<?php

namespace ArcTest\Core;

class LifeCycleManager {
    /**
     * Executes the `beforeAll` method of the specified class if it exists.
     * @param string $class The name of the class to check for a `beforeAll` method.
     * @return void
     */
    public function beforeAll(string $class): void {
        if(method_exists($class, 'beforeAll')) {
            $class::beforeAll();
        }
    }

    /**
     * Executes the `afterAll` method of the specified class if it exists.
     * @param string $class The name of the class to check for an `afterAll` method.
     * @return void
     */
    public function afterAll(string $class): void {
        if(method_exists($class, 'afterAll')) {
            $class::afterAll();
        }
    }

    /**
     * Executes the `beforeEach` method of the provided test case instance if it exists.
     * @param TestCase $instance The instance of the test case to check for a `beforeEach` method.
     * @return void
     */
    public function beforeEach(TestCase $instance): void {
        if(method_exists($instance, 'beforeEach')) {
            $instance->beforeEach();
        }
    }

    /**
     * Executes the afterEach method on the provided TestCase instance if it exists.
     * @param TestCase $instance The test case instance which may have an afterEach method.
     * @return void
     */
    public function afterEach(TestCase $instance): void {
        if(method_exists($instance, 'afterEach')) {
            $instance->afterEach();
        }
    }
}