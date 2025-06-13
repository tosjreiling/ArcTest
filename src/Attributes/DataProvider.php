<?php

namespace ArcTest\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class DataProvider {
    public string $method;

    public function __construct(string $method) {
        $this->method = $method;
    }
}