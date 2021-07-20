<?php

namespace Bauhaus\Uri;

use InvalidArgumentException;

final class InvalidArgument extends InvalidArgumentException
{
    public function __construct(string $invalidUri)
    {
        parent::__construct("Invalid URI provided: $invalidUri");
    }
}
