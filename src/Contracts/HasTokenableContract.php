<?php

namespace Jundayw\LaravelOAuth\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphTo;

interface HasTokenableContract
{
    /**
     * Get the tokenable model that the access token belongs to.
     *
     * @return MorphTo
     */
    public function tokenable(): MorphTo;
}
