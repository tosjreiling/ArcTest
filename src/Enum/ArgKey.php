<?php

namespace ArcTest\Enum;

enum ArgKey: string {
    case FILTER = "filter";
    case GROUP = "group";
    case FORMAT = "format";
    case VERBOSE = "verbose";
    case FAIL_FAST = "fail-fast";
    case HELP = "help";
    case OUTPUT = "output";
    case EXCLUDE = "exclude";
}
