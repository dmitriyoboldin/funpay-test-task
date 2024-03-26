<?php

namespace FpDbTest\Mapper;

use Exception;

final class StrategyNotFoundException extends Exception
{
    public function __construct(string $pattern)
    {
        parent::__construct(
            sprintf('Strategy for pattern `%s` not found', $pattern),
        );
    }
}
