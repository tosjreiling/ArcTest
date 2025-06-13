<?php

namespace Tests;

use ArcTest\Attributes\Group;
use ArcTest\Core\TestCase;
use ArcTest\Core\TestSelector;

class TestSelectorTest extends TestCase {
    private TestSelector $selector;

    public function beforeEach(): void {
        $this->selector = new TestSelector();
    }

    public function testMethodIsIncludedByFilter(): void {
        $class = new class extends TestCase {
            public function testSomethingImportant(): void {}
            public function helperMethod(): void {}
        };

        $this->assertTrue($this->selector->check($class, "testSomethingImportant", "Important", [], []));
        $this->assertFalse($this->selector->check($class, "helperMethod", "Important", [], []));
    }

    public function testMethodIsIncludedByGroup(): void {
        $class = new #[Group("core")] class extends TestCase {
            #[Group("auth")]
            public function testAuthMethod(): void {}

            #[Group("core")]
            public function testCoreMethod(): void {}
        };

        $this->assertTrue($this->selector->check($class, "testCoreMethod", "", ["core"], []));
        $this->assertFalse($this->selector->check($class, "testAuthMethod", "", ["core"], []));
    }

    public function testMethodIsExcludedByGroup(): void {
        $class = new class extends TestCase {
            #[Group("slow")]
            public function testSlowFeature(): void {}

            #[Group("fast")]
            public function testFastFeature(): void {}
        };

        $this->assertFalse($this->selector->check($class, "testSlowFeature", "", [], ["slow"]));
        $this->assertTrue($this->selector->check($class, "testFastFeature", "", [], ["slow"]));
    }

    public function testGroupExclusionOverridesInclusion(): void {
        $class = new class extends TestCase {
            #[Group("db")]
            public function testWithDb(): void {}
        };

        $this->assertFalse($this->selector->check($class, "testWithDb", "", ["db"], ["db"]));
    }

    public function testNoGroupsAndNoFilterAllowsAll(): void {
        $class = new class extends TestCase {
            public function testOne(): void {}
            public function testTwo(): void {}
        };

        $this->assertTrue($this->selector->check($class, "testOne", "", [], []));
        $this->assertTrue($this->selector->check($class, "testTwo", "", [], []));
    }
}