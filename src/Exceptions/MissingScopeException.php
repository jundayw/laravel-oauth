<?php

namespace Jundayw\LaravelOAuth\Exceptions;

use Throwable;

class MissingScopeException extends OAuthAccessTokenException
{
    /**
     * The scopes that the user did not have.
     *
     * @var array
     */
    protected $scopes;

    /**
     * Create a new missing scope exception.
     *
     * @param array $scopes
     * @param string $message
     * @return void
     */
    public function __construct($scopes = [], $message = 'Invalid scope(s) provided.', $code = 43001, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->scopes = $scopes;
    }

    /**
     * Get the scopes that the user did not have.
     *
     * @return array
     */
    public function scopes(): array
    {
        return $this->scopes;
    }
}
