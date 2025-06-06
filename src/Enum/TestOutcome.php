<?php

namespace ArcTest\Enum;

enum TestOutcome: string {
    case PASSED = "passed";
    case FAILED = "failed";
    case SKIPPED = "skipped";
}
