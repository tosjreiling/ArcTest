<?php

namespace ArcTest\Runner;

class Helper {
    public static function show(string $path = __DIR__ . "/../../resources/help.txt"): void {
        if(file_exists($path)) {
            echo file_get_contents($path);
        } else {
            echo "No help file found at: {$path}" . PHP_EOL;
        }
    }
}