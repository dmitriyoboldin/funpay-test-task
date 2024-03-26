<?php

namespace FpDbTest\Mapper\Strategy;

final readonly class IdStrategy extends BaseStrategy
{
    public static function pattern(): string
    {
        return '?#';
    }

    protected function isCorrectValue(mixed $value): bool
    {
        return is_array($value)
            ? $this->isCorrectArrayValue($value)
            : (is_string($value) || is_bool($value));
    }

    private function isCorrectArrayValue(array $value): bool
    {
        foreach ($value as $item) {
            if (!(is_string($item) || is_bool($value))) {
                return false;
            }
        }

        return true;
    }

    protected function getSqlValue(mixed $value): string
    {
        return is_array($value)
            ? $this->getArrayValue($value)
            : self::prepareValue($value);
    }

    private function getArrayValue(array $arr): string
    {
        $values = [];
        foreach ($arr as $value) {
            $values[] = self::prepareValue($value);
        }

        return implode(', ', $values);
    }

    private static function prepareValue(mixed $value): string
    {
        $str = is_string($value)
            ? $value
            : SimpleValueConvertor::convert($value);

        return "`$str`";
    }
}
