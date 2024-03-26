<?php

namespace FpDbTest;

interface MapperInterface
{
    public function map(string $patern, mixed $value): string;

    public function regex(): string;
}
