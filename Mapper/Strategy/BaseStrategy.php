<?php

namespace FpDbTest\Mapper\Strategy;

use FpDbTest\Mapper\StrategyInterface;
use InvalidArgumentException;

abstract readonly class BaseStrategy implements StrategyInterface
{
    public function convert(mixed $value): string
    {
        if (!$this->isCorrectValue($value)) {
            throw new InvalidArgumentException();
        }

        return null === $value
            ? 'NULL'
            : $this->getSqlValue($value);
    }

    abstract protected function isCorrectValue(mixed $value): bool;

    abstract protected function getSqlValue(mixed $value): string;
}
