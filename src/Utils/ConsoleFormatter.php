<?php

namespace ArcTest\Utils;

/**
 * Class ConsoleFormatter
 * Provides methods to format text with colors for console output.
 */
class ConsoleFormatter {
    private const string RESET = "\033[0m";
    private const string GREEN = "\033[32m";
    private const string RED = "\033[31m";
    private const string YELLOW = "\033[33m";

    /**
     * Changes the color of the given text to green.
     * @param string $text The text to colorize in green.
     * @return string The green colorized text.
     */
    public function green(string $text): string {
        return self::GREEN . $text . self::RESET;
    }

    /**
     * Changes the color of the given text to red.
     * @param string $text The text to colorize in red.
     * @return string The red colorized text.
     */
    public function red(string $text): string {
        return self::RED . $text . self::RESET;
    }

    /**
     * Changes the color of the given text to yellow.
     * @param string $text The text to colorize.
     * @return string The yellow colorized text.
     */
    public function yellow(string $text): string {
        return self::YELLOW . $text . self::RESET;
    }
}