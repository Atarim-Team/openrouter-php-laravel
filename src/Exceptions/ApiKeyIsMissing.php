<?php

declare(strict_types=1);

namespace OpenRouter\Laravel\Exceptions;

use InvalidArgumentException;

/**
 * @internal
 */
final class ApiKeyIsMissing extends InvalidArgumentException
{
    /**
     * Create a new exception instance.
     */
    public static function create(): self
    {
        return new self(
            'The OpenRouter API Key is missing. Please publish the [openrouter.php] configuration file and set the [api_key].'
        );
    }
}
