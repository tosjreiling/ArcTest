<?php

namespace ArcTest\Core;

use ArcTest\Attributes\Group;
use ReflectionException;
use ReflectionMethod;

class TestSelector {
    /**
     * Check if a method belongs to any of the specified groups based on certain conditions.
     * @param object $testInstance The instance of the test class.
     * @param string $method The method to check.
     * @param string $filter (optional) A filter string to narrow down the method to check.
     * @param array $groups (optional) The groups to check against.
     * @return bool Returns true if the method meets all conditions and belongs to any of the specified groups, false otherwise.
     * @throws ReflectionException
     */
    public function check(object $testInstance, string $method, string $filter = "", array $groups = [], array $excludes = []): bool {
        if(!str_starts_with($method, "test")) return false;
        if(!empty($filter) && !str_contains($method, $filter) && !str_contains(get_class($testInstance), $filter)) return false;
        if(!empty($excludes) && $this->methodInGroup($testInstance, $method, $excludes)) return false;
        if(!empty($groups) && !$this->methodInGroup($testInstance, $method, $groups)) return false;

        return true;
    }

    /**
     * Check if a method belongs to any of the specified groups.
     * @param object $testInstance The instance of the test class.
     * @param string $method The method to check.
     * @param array $groups The groups to check against.
     * @return bool Returns true if the method belongs to any of the specified groups, false otherwise.
     * @throws ReflectionException
     */
    private function methodInGroup(object $testInstance, string $method, array $groups): bool {
        $reflection = new ReflectionMethod($testInstance, $method);
        $attributes = $reflection->getAttributes(Group::class);

        foreach($attributes as $attribute) {
            /* @var Group $group */
            $group = $attribute->newInstance();
            if(in_array($group->name, $groups, true)) return true;
        }

        return false;
    }
}