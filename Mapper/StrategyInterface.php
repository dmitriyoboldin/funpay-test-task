<?php

namespace FpDbTest\Mapper;

interface StrategyInterface
{
    public static function pattern(): string;

    public function convert(mixed $value): string;
}
