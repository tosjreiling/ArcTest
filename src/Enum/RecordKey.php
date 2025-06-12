<?php

namespace ArcTest\Enum;

enum RecordKey: string {
    case CLASSNAME = "className";
    case METHOD = "method";
    case OUTCOME = "outcome";
    case MESSAGE = "message";
    case EXCEPTION = "exception";
    case TRACE = "trace";
    case EXPECTED = "expected";
    case ACTUAL = "actual";
}