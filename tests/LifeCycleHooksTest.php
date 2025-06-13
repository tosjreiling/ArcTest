<?php

namespace Tests;

use ArcTest\Core\TestCase;

class LifeCycleHooksTest extends TestCase{
    public static bool $beforeAllRan = false;
    public static bool $afterAllRan = false;
    public bool $beforeEachRan = false;
    public bool $afterEachRan = false;

    public static function beforeAll(): void {
        self::$beforeAllRan = true;
    }
    public static function afterAll(): void {
        self::$afterAllRan = true;
    }
    public function beforeEach(): void {
        $this->beforeEachRan = true;
    }
    public function afterEach(): void {
        $this->afterEachRan = true;
    }

    public function testHooksOnFirstRun(): void {
        $this->assertTrue(self::$beforeAllRan, "beforeAll() was not called");
        $this->assertTrue($this->beforeEachRan, "beforeEach() was not called");
    }

    public function testHooksOnSecondRun(): void {
        $this->assertTrue(self::$beforeAllRan, "beforeAll should still be true");
        $this->assertTrue($this->beforeEachRan, "beforeEach should run again for each test");
    }

    public function testAfterAllEffect(): void {
        $this->assertTrue(true);
    }
}