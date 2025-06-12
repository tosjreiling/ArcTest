<?php

namespace Tests;

use ArcTest\Attributes\Depends;
use ArcTest\Attributes\Group;
use ArcTest\Core\TestCase;

class MultipleDependsTest extends TestCase {
    private static bool $initA = false;
    private static bool $initB = false;

    public function testInitA(): void {
        self::$initA = true;
        $this->assertTrue(true);
    }

    #[Group("experimental")]
    public function testInitB(): void {
        self::$initB = true;
        $this->assertTrue(false);
    }

    #[Depends(["testInitA", "testInitB"])]
    public function testRunMultipleDependencies(): void {
        $this->assertTrue(self::$initA, "Expected initA to have run");
        $this->assertTrue(self::$initB, "Expected initB to have run");
    }

    #[Depends(["testMissingA", "testMissingB"])]
    public function testSkippedMissingMultipleDependencies(): void {
        $this->fail("This test should be skipped due to missing dependencies");
    }
}