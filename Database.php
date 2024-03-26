<?php

namespace FpDbTest;

use FpDbTest\Mapper\Mapper;
use FpDbTest\Mapper\Strategy\ArrayStrategy;
use FpDbTest\Mapper\Strategy\DefaultStrategy;
use FpDbTest\Mapper\Strategy\FloatStrategy;
use FpDbTest\Mapper\Strategy\IdStrategy;
use FpDbTest\Mapper\Strategy\IntStrategy;
use mysqli;

final class Database implements DatabaseInterface
{
    private object $skipValue;
    private MapperInterface $mapper;

    public function __construct(private mysqli $mysqli)
    {
        $this->skipValue = new class() {};
        $this->mapper = new Mapper(
            new ArrayStrategy(),
            new DefaultStrategy(),
            new FloatStrategy(),
            new IdStrategy(),
            new IntStrategy(),
        );
    }

    public function buildQuery(string $query, array $args = []): string
    {
        return [] === $args
            ? $query
            : $this->replaceMatches($query, $args);
    }

    public function skip(): object
    {
        return $this->skipValue;
    }

    private function getMatches(string $query): array
    {
        preg_match_all(
            pattern: $this->mapper->regex(),
            subject: $query,
            matches: $matches,
            flags: PREG_OFFSET_CAPTURE,
        );

        return array_reverse(
            @array_unique($matches)[0],
        );
    }

    private function replaceMatches(string $query, array $args): string
    {
        foreach ($this->getMatches($query) as $match) {
            $value = array_pop($args);
            $query = $this->replaceMatch($query, $match, $value);
        }

        return $this->replaceBracers($query);
    }

    private function replaceMatch(
        string $query,
        array $match,
        mixed $value,
    ): string {
        return $this->skip() === $value
            ? $this->skipBlock($match[0], $query)
            : $this->replacePattern($query, $match, $value);
    }

    private function skipBlock(string $pattern, string $query): string
    {
        return preg_replace(
            pattern: $this->getSkipRegex($pattern),
            replacement: '',
            subject: $query,
        );
    }

    private function replacePattern(
        string $query,
        array $match,
        mixed $value,
    ): string {
        $pattern = $match[0];
        $offset = $match[1];

        return substr_replace(
            string: $query,
            replace: $this->mapper->map($pattern, $value),
            offset: $offset,
            length: strlen($pattern),
        );
    }

    private function getSkipRegex(string $pattern): string
    {
        return sprintf('/\{.*%s.*\}/', preg_quote($pattern));
    }

    private function replaceBracers(string $query): string
    {
        return str_replace(['{', '}'], '', $query);
    }
}
