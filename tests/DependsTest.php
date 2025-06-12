<?php

namespace Tests;

use ArcTest\Attributes\Depends;
use ArcTest\Attributes\Group;
use ArcTest\Core\TestCase;

class DependsTest extends TestCase {
    private static bool $init = false;

    public function testInit(): void {
        self::$init = true;
        $this->assertTrue(true);
    }

    #[Depends("testInit")]
    public function testShouldRun(): void {
        $this->assertTrue(self::$init, "Expected init to have run");
    }

    #[Depends("testFails")]
    public function testShouldSkip(): void {
        $this->fail("This test should have been skipped");
    }

    #[Group("experimental")]
    public function testFails(): void {
        $this->assertTrue(false, "Intentional failure to test dependency skip");
    }

    #[Depends("testNeverRun")]
    public function testSkipped(): void {
        $this->fail("Should be skipped because testNeverRun does not run");
    }
}