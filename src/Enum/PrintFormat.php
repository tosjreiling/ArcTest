<?php

namespace ArcTest\Enum;

enum PrintFormat: string {
    case CONSOLE = "console";
    case JSON = "json";
    case JUNIT = "junit";
    case HTML = "html";

    public static function fromString(string $value): self {
        return self::tryFrom(strtolower($value)) ?? self::CONSOLE;
    }
}
