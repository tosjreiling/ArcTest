<?php

use ArcTest\Core\TestCase;

class MyFirstTest extends TestCase {
    public function testHelloWorld(): void {
        $this->assertTrue(true);
    }

    public function testQuestion(): void {
        $item = null;
        $this->assertNotNull($item);
    }

    public function testAnswer(): void {
        $this->assertSame("Hello World", "Hello World");
    }

    public function testIdentical(): void {
        $a = 1;
        $b = 1;
        $this->assertSame($a, $b);
    }
}