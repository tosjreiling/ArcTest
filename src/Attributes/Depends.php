<?php

namespace ArcTest\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Depends {
    public string $method;

    public function __construct(string $method) {
        $this->method = $method;
    }
}