<?php

namespace Bauhaus;

use Bauhaus\Uri\Parser;

final class Uri
{
    public function __construct(
        public string $scheme,
        public ?string $user,
        public ?string $password,
        public ?string $host,
        public ?int $port,
        public string $path,
        public array $query,
        public ?string $fragment,
    ) {
    }

    public static function fromString(string $string): self
    {
        $parser = new Parser($string);
        $parsed = $parser->result();

        return new self(
            scheme: $parsed['scheme'],
            user: $parsed['user'],
            password: $parsed['password'],
            host: $parsed['host'],
            port: $parsed['port'],
            path: $parsed['path'],
            query: $parsed['query'],
            fragment: $parsed['fragment'],
        );
    }
}
