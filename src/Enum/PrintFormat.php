<?php

namespace ArcTest\Enum;

enum PrintFormat: string {
    case CONSOLE = "console";
    case JSON = "json";

    public static function fromString(string $value): self {
        return self::tryFrom(strtolower($value)) ?? self::CONSOLE;
    }
}
