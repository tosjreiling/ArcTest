<?php

namespace ArcTest\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Depends {
    public array $methods;

    public function __construct(string|array $methods) {
        $this->methods = is_array($methods) ? $methods : [$methods];
    }
}