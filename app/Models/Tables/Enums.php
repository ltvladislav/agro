<?php

namespace App\Models\Tables;


class Enums
{
    const ExpressionType = [
        "AND" => 1,
        "OR" => 2
    ];
    const DataValueType = [
        "Integer" => 1,
        "Decimal" => 2,
        "Boolean" => 3,
        "Text" => 4,
        "Varchar" => 5,
        "Lookup" => 6,
        "Guid" => 7,
        "Double" => 8,
        "Date" => 9,

        "Undefined" => -1
    ];
    const ConditionType = [
        "Equal" => 1,
        "NotEqual" => 2,
        "More" => 3,
        "MoreEqual" => 4,
        "Less" => 5,
        "LessEqual" => 6
    ];
    const OrderDirection = [
        "ASC" => 1,
        "DESC" => 2
    ];
}
