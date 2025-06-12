<?php

namespace Tests;

use ArcTest\Core\TestCase;
use ArcTest\Enum\PrintFormat;
use ArcTest\Runner\Arguments;

class ArgumentsTest extends TestCase {
    public function testDefaultsWithoutArguments(): void {
        $args = new Arguments();

        $this->assertFalse($args->verbose, "Expected verbose to be false by default");
        $this->assertFalse($args->failFast, "Expected fail to be false by default");
        $this->assertFalse($args->help, "Expected help to be false by default");

        $this->assertNull($args->filter, "Expected filter to be null by default");
        $this->assertNull($args->groups, "Expected groups to be null by default");
        $this->assertNull($args->output, "Expected output to be null by default");
        $this->assertNull($args->excludes, "Expected excludes to be null by default");

        $this->assertSame(PrintFormat::CONSOLE, $args->format, "Expected print format to be console value)");
    }

    public function testVerboseFlag(): void {
        $args = Arguments::fromArgv(["script.php", "--verbose"]);
        $this->assertTrue($args->verbose, "Expected verbose to be true when --verbose is passed");
    }

    public function testFailFastFlag(): void {
        $args = Arguments::fromArgv(["script.php", "--fail-fast"]);
        $this->assertTrue($args->failFast, "Expected failFast to be true when --fail-fast is passed");
    }

    public function testHelpFlag(): void {
        $args = Arguments::fromArgv(["script.php", "--help"]);
        $this->assertTrue($args->help, "Expected help to be true when --help is passed");
    }

    public function testFormatJson(): void {
        $args = Arguments::fromArgv(["script.php", "--format=json"]);
        $this->assertEquals(PrintFormat::JSON, $args->format, "Expected format to be PrintFormat::JSON");
    }

    public function testOutputPath(): void {
        $args = Arguments::fromArgv(["script.php", "--output=some/path.xml"]);
        $this->assertEquals("some/path.xml", $args->output, "Expected output path to be 'some/path.xml");
    }

    public function testFilterFlag(): void {
        $args = Arguments::fromArgv(["script.php", "--filter=MyTestFilter"]);
        $this->assertEquals("MyTestFilter", $args->filter, "Expected filter to be MyTestFilter");
    }

    public function testGroupParsing(): void {
        $args = Arguments::fromArgv(["script.php", "--group=core,auth"]);
        $this->assertEquals(["core", "auth"], $args->groups, "Expected groups to be ['core', 'auth']");
    }

    public function testExcludeParsing(): void {
        $args = Arguments::fromArgv(["script.php", "--exclude=slow,integration"]);
        $this->assertEquals(["slow", "integration"], $args->excludes, "Expected excludes to be ['slow', 'integration']");
    }

    public function testUnknownFormatFallbackToConsole(): void {
        $args = Arguments::fromArgv(["script.php", "--format=unknown"]);
        $this->assertSame(PrintFormat::CONSOLE, $args->format, "Expected unknown format to fallback to PrintFormat::CONSOLE");
    }
}