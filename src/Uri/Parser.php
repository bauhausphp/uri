<?php

namespace Bauhaus\Uri;

/**
 * @internal
 */
final class Parser
{
    private const PREG_MATCH_SUCCESS = 1;

    private array $result;

    public function __construct(
        private string $originalUri,
    ) {
        $this->parse();
        $this->ensureFields();
        $this->parseQueryString();
    }

    public function result(): array
    {
        return $this->result;
    }

    private function parse(): void
    {
        $uri = strtolower($this->originalUri);

        $this->result = $this->match($this->matchPattern(), $uri);
    }

    private function match(string $pattern, string $subject): array
    {
        $matches = [];
        $matchResult = preg_match($pattern, $subject, $matches);

        if (self::PREG_MATCH_SUCCESS !== $matchResult) {
            throw new InvalidArgument($this->originalUri);
        }

        return array_filter(
            $matches,
            fn ($v, int|string $k): bool => !is_int($k) && '' !== $v,
            ARRAY_FILTER_USE_BOTH,
        );
    }

    private function matchPattern(): string
    {
        $unreserved = 'a-z\d\-\.\~\_';
        $subDelims = "!'\$\&\()\*\+,;=";

        $scheme = "(?<scheme>[a-z][$unreserved\+]*)";

        $user = "(?<user>[$unreserved$subDelims]*)";
        $pass = "(?<password>[$unreserved$subDelims:]*)";
        $host = "(?<host>[$unreserved]*)";
        $port = '(?<port>[\d]*)';
        $authority = "($user(:$pass)?@)?$host(:$port)?";

        $path = "(?<path>[a-z\d\/+-]*)";
        $query = "(?<query>[a-z\d\/\+\-\=\&]*)";
        $fragment = '(?<fragment>[a-z]*)';

        return "%^$scheme:(//$authority)?$path?(\?$query)?(#$fragment)?$%";
    }

    private function ensureFields(): void
    {
        $this->result['user'] ??= null;
        $this->result['password'] ??= null;
        $this->result['host'] ??= null;
        $this->result['port'] ??= null;
        $this->result['path'] ??= '';
        $this->result['query'] ??= [];
        $this->result['fragment'] ??= null;
        $this->result['path'] ??= '';
    }

    private function parseQueryString(): void
    {
        if ([] === $this->result['query']) {
            return;
        }

        $parts = explode('&', $this->result['query']);
        $this->result['query'] = [];

        foreach ($parts as $part) {
            [$key, $value] = explode('=', $part);
            $this->result['query'][$key] = $value;
        }
    }
}
