<?php

namespace FpDbTest\Mapper\Strategy;

final readonly class ArrayStrategy extends BaseStrategy
{
    public static function pattern(): string
    {
        return '?a';
    }

    protected function isCorrectValue(mixed $value): bool
    {
        return is_array($value);
    }

    /** @param array $value */
    protected function getSqlValue(mixed $value): string
    {
        $arr = array_is_list($value)
            ? $this->getListValues($value)
            : $this->getAssociativeArrayValues($value);

        return implode(', ', $arr);
    }

    private function getListValues(array $arr): array
    {
        $result = [];
        foreach ($arr as $item) {
            $result[] = SimpleValueConvertor::convert($item);
        }

        return $result;
    }

    private function getAssociativeArrayValues(array $arr): array
    {
        $result = [];
        foreach ($arr as $key => $value) {
            $result[] = $this->convertKeyAndValue($key, $value);
        }

        return $result;
    }

    private function convertKeyAndValue(string $key, mixed $value): string
    {
        return null === $value
            ? $this->getNullExpression($key)
            : $this->getDefaultExpression($key, $value);
    }

    private function getNullExpression($key): string
    {
        return sprintf('`%s` = NULL', $key);
    }

    private function getDefaultExpression(string $key, mixed $value): string
    {
        return sprintf(
            '`%s` = %s',
            $key,
            SimpleValueConvertor::convert($value),
        );
    }
}
