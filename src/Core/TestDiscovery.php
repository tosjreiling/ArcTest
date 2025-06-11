<?php

namespace ArcTest\Core;

use ReflectionException;

class TestDiscovery {
    /**
     * Discovers and adds test cases to a test suite.
     * @param array $tests An array of tests to manually add to the test suite. If this array is not empty, these tests are added directly.
     * @param string $directory The directory to search for tests if the $tests array is empty.
     * @param string $filter An optional filter to limit discovered tests when searching in the specified directory.
     * @return TestSuite The test suite containing either the manually added tests or the discovered tests from the specified directory.
     * @throws ReflectionException
     */
    public function discover(array $tests, string $directory, string $filter = ""): TestSuite {
        $suite = new TestSuite();

        if (!empty($tests)) {
            foreach ($tests as $test) {
                $suite->add($test);
            }
        } else {
            $suite->discover($directory, $filter);
        }

        return $suite;
    }
}