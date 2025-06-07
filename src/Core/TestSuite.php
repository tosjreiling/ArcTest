<?php

namespace ArcTest\Core;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * Represents a test suite that manages a collection of classes and provides functionality
 * to discover PHP files within a directory structure.
 */
class TestSuite {
    private array $classes = [];

    /**
     * Adds a class name to the internal list of classes.
     * @param string $className The fully qualified class name to add to the list.
     * @return void
     */
    public function add(string $className): void {
        $this->classes[] = $className;
    }

    /**
     * Discovers test classes within a directory and adds them to the test suite.
     * @param string $directory The directory to search for test classes.
     * @param string $filter A filter to restrict the test classes to specific names.
     * @return void
     * @throws InvalidArgumentException If the provided directory does not exist.
     * @throws ReflectionException
     */
    public function discover(string $directory, string $filter = ""): void {
        $files = $this->scan($directory);

        foreach($files as $file) require_once $file;

        foreach(get_declared_classes() as $class) {
            if(!is_subclass_of($class, TestCase::class)) continue;

            $reflection = new ReflectionClass($class);
            if($reflection->isAbstract() && !$reflection->isInterface()) continue;

            if(!empty($filter)) {
                if(str_contains($class, $filter)) {
                    $this->add($class);
                    continue;
                }

                $methods = get_class_methods($class);
                $match = false;

                foreach ($methods as $method) {
                    if (str_starts_with($method, "test") && str_contains($method, $filter)) {
                        $match = true;
                        break;
                    }
                }

                if ($match) $this->add($class);
            } else {
                $this->add($class);
            }
        }
    }

    /**
     * Retrieves the list of classes.
     * @return array An array containing the names of all classes.
     */
    public function getClasses(): array {
        return $this->classes;
    }

    /**
     * Scans a directory recursively and retrieves a list of all PHP files within it.
     * @param string $directory The directory to scan.
     * @return array An array containing the paths of all PHP files found in the directory and its subdirectories.
     * @throws InvalidArgumentException If the provided directory does not exist.
     */
    private function scan(string $directory): array {
        if(!is_dir($directory)) throw new InvalidArgumentException("Directory not found: {$directory}");

        $files = scandir($directory);
        $phpFiles = [];

        foreach($files as $file) {
            $path = $directory . DIRECTORY_SEPARATOR . $file;

            if(is_dir($path) && $file !== '.' && $file !== '..') {
                $phpFiles = array_merge($phpFiles, $this->scan($path));
                continue;
            }

            if(is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
                $phpFiles[] = $path;
            }
        }

        return $phpFiles;
    }
}