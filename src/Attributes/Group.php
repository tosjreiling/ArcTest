<?php

namespace ArcTest\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
readonly class Group {
    public string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }
}