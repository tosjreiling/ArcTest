<?php

namespace Tests;

use ArcTest\Attributes\Group;
use ArcTest\Core\TestCase;
use ArcTest\Enum\PrintFormat;
use ArcTest\Runner\Arguments;

class ArgumentsTest extends TestCase {
    public function testDefaults(): void {
        $args = Arguments::fromArgv(["bin/arctest"]);
        $this->assertSame("", $args->filter);
        $this->assertSame([], $args->groups);
        $this->assertSame(PrintFormat::CONSOLE, $args->format);
        $this->assertFalse($args->verbose);
        $this->assertFalse($args->failFast);
        $this->assertFalse($args->help);
    }

    public function testFilterAndGroups(): void {
        $args = Arguments::fromArgv(["bin/arctest", "--filter=test", "--group=unit,integration"]);
        $this->assertSame("test", $args->filter);
        $this->assertSame(["unit", "integration"], $args->groups);
    }

    #[Group("unit")]
    public function testBooleanFlags(): void {
        $args = Arguments::fromArgv(["bin/arctest", "--verbose", "--fail-fast"]);
        $this->assertTrue($args->verbose);
        $this->assertTrue($args->failFast);
    }

    public function testFormatJson(): void {
        $args = Arguments::fromArgv(["bin/arctest", "--format=json"]);
        $this->assertSame(PrintFormat::JSON, $args->format);
    }

    public function testHelpFlag(): void {
        $args = Arguments::fromArgv(["bin/arctest", "--help"]);
        $this->assertTrue($args->help);
    }
}