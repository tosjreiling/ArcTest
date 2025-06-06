<?php

use ArcTest\Core\TestRunner;
use ArcTest\Core\TestSuite;

require_once __DIR__ . '/vendor/autoload.php';

// Build suite and discover tests
$suite = new TestSuite();
$suite->discover(__DIR__ . '/tests');

// Run tests
$runner = new TestRunner();
$runner->run($suite);
