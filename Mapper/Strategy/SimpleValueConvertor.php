<?php

namespace FpDbTest\Mapper\Strategy;

final readonly class SimpleValueConvertor
{
    public static function convert(mixed $value): string
    {
        return match (true) {
            (null === $value) => 'NULL',
            is_string($value) => "'$value'",
            is_bool($value) => $value ? '1' : '0',
            default => (string)$value,
        };
    }
}
