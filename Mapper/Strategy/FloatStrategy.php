<?php

namespace FpDbTest\Mapper\Strategy;

final readonly class FloatStrategy extends BaseStrategy
{
    public static function pattern(): string
    {
        return '?f';
    }

    protected function isCorrectValue(mixed $value): bool
    {
        return is_float($value);
    }

    /** @param float $value */
    protected function getSqlValue(mixed $value): string
    {
        return sprintf('%g', $value);
    }
}
