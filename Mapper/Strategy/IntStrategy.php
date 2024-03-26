<?php

namespace FpDbTest\Mapper\Strategy;

final readonly class IntStrategy extends BaseStrategy
{
    public static function pattern(): string
    {
        return '?d';
    }

    protected function isCorrectValue(mixed $value): bool
    {
        return is_int($value) || is_bool($value);
    }

    /** @param int|bool $value */
    protected function getSqlValue(mixed $value): string
    {
        return SimpleValueConvertor::convert($value);
    }
}
