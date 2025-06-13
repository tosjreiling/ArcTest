<?php

namespace ArcTest\Core;

use ArcTest\Attributes\DataProvider;
use ReflectionAttribute;
use ReflectionException;
use ReflectionMethod;
use RuntimeException;

class DataProviderHandler {
    /**
     * Retrieves data from a data provider method annotated by the specified attributes in a test case.
     * @param TestCase $test The test case instance containing the method to retrieve attributes from.
     * @param string $method The name of the method in the test case to inspect for attributes.
     * @return array The data provided by the data provider method, or an empty array if no applicable attributes are found.
     * @throws RuntimeException If the data provider method specified in the attributes does not exist.
     * @throws ReflectionException
     */
    public function get(TestCase $test, string $method): array {
        $reflection = new ReflectionMethod($test, $method);
        $attributes = $reflection->getAttributes(DataProvider::class, ReflectionAttribute::IS_INSTANCEOF);

        if(empty($attributes)) return [];

        $providerName = $attributes[0]->newInstance()->method;
        if(!method_exists($test, $providerName)) {
            throw new RuntimeException("Data provider method '{$providerName}' does not exist");
        }

        return $test->$providerName();
    }
}