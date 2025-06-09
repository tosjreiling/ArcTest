<?php

namespace ArcTest\Enum;

enum AssertType: string {
    case IS_STRING = "is_string";
    case IS_INT = "is_int";
    case IS_FLOAT = "is_float";
    case IS_BOOL = "is_bool";
    case IS_ARRAY = "is_array";
    case IS_OBJECT = "is_object";
    case IS_CALLABLE = "is_callable";
    case IS_ITERABLE = "is_iterable";
    case IS_RESOURCE = "is_resource";
    case IS_NULL = "is_null";
}