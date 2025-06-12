<?php

namespace ArcTest\Enum;

enum ArgKey: string {
    public const string FILTER = "filter";
    public const string GROUP = "group";
    public const string FORMAT = "format";
    public const string VERBOSE = "verbose";
    public const string FAIL_FAST = "fail-fast";
    public const string HELP = "help";
    public const string OUTPUT = "output";
}
