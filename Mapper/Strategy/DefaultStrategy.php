<?php

namespace FpDbTest\Mapper\Strategy;

final readonly class DefaultStrategy extends BaseStrategy
{
    public static function pattern(): string
    {
        return '?';
    }

    protected function isCorrectValue(mixed $value): bool
    {
        return
            null === $value
            || is_string($value)
            || is_float($value)
            || is_int($value)
            || is_bool($value);
    }

    /** @param string|float|int|bool|null $value */
    protected function getSqlValue(mixed $value): string
    {
        return SimpleValueConvertor::convert($value);
    }
}
