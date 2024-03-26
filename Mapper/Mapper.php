<?php

namespace FpDbTest\Mapper;

use FpDbTest\MapperInterface;
use InvalidArgumentException;

final class Mapper implements MapperInterface
{
    /** @var StrategyInterface[] */
    private array $strategies;

    /** @param StrategyInterface ...$strategies */
    public function __construct(...$strategies)
    {
        $this->strategies = [];
        foreach ($strategies as $strategy) {
            if (!($strategy instanceof StrategyInterface)) {
                throw new InvalidArgumentException();
            }
            $this->strategies[] = $strategy;
        }
    }

    public function map(string $pattern, mixed $value): string
    {
        return $this->getStrategy($pattern)->convert($value);
    }

    private function getStrategy(string $pattern): StrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->pattern() === $pattern) {
                return $strategy;
            }
        }

        throw new StrategyNotFoundException($pattern);
    }

    public function regex(): string
    {
        $exps = $this->getPatternExps();

        return sprintf(
            '/(?=|\()(%s)(?=\s|$|\}|\))/',
            implode('|', $exps),
        );
    }

    /** @return string[] */
    private function getPatternExps(): array
    {
        $exps = [];
        foreach ($this->strategies as $strategy) {
            $exps[] = preg_quote($strategy->pattern());
        }

        return $exps;
    }
}
