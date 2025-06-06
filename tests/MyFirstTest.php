<?php

namespace Tests;

use ArcTest\Core\TestCase;
use InvalidArgumentException;

class MyFirstTest extends TestCase {
    public function testTrueAssertions(): void {
        $this->assertTrue(true);
    }

    public function testNullAssertions(): void {
        $this->assertNull("abc");
        $this->assertNotNull(null);
    }

    public function testEqualAssertions(): void {
        $this->assertEquals("a", "a");
        $this->assertEquals(1, "1");
    }

    public function testSameAssertions(): void {
        $this->assertSame("a", "a");
        $this->assertSame(1, 1);
        $this->assertSame([1, 2, 3], [1, 2, 3]);
    }

    public function testEmptyAssertions(): void {
        $this->assertEmpty([]);
        $this->assertEmpty("");
        $this->assertNotEmpty([1, 2, 3]);
        $this->assertNotEmpty("abc");
    }

    public function testStringAssertions(): void {
        $this->assertStringContains("world", "hello world");
        $this->assertStringStartsWith("hello", "hello world");
        $this->assertStringEndsWith("world", "hello world");
    }

    public function testTypeAssertions(): void {
        $this->assertIsString("ArcTest");
        $this->assertIsInt(42);
        $this->assertIsFloat(3.14);
        $this->assertIsArray([1, 2, 3]);
        $this->assertIsObject(new \stdClass());
        $this->assertIsBool(true);
        $this->assertIsCallable(fn() => true);
        $this->assertIsIterable([1, 2, 3]);
    }

    public function testExceptionAssertions(): void {
        $this->expectException(InvalidArgumentException::class);
        throw new InvalidArgumentException("Something went wrong");
    }

    public function testFailure(): void {
        $this->assertTrue(false);
    }

    public function testShouldNotRun(): void {
        $this->assertTrue(true);
    }
}