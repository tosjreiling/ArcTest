<?php

namespace Tests;

use ArcTest\Core\TestCase;
use ArcTest\Enum\PrintFormat;

class PrintFormatTest extends TestCase {
    public function testFromStringConsole(): void {
        $format = PrintFormat::fromString("console");
        $this->assertSame(PrintFormat::CONSOLE, $format);
    }

    public function testFromStringJson(): void {
        $format = PrintFormat::fromString("json");
        $this->assertSame(PrintFormat::JSON, $format);
    }

    public function testFromStringJUnit(): void {
        $format = PrintFormat::fromString("junit");
        $this->assertSame(PrintFormat::JUNIT, $format);
    }

    public function testFromStringFallback(): void {
        $format = PrintFormat::fromString("invalid");
        $this->assertSame(PrintFormat::CONSOLE, $format, "Expected fallback to CONSOLE for unknown format");
    }
}