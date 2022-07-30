<?php

namespace Jundayw\LaravelOAuth\Support;

use Illuminate\Support\Collection;

trait Signature
{
    /**
     * Encrypt Method
     *
     * @param array $plaintext
     * @return string
     */
    public static function encrypt(array $plaintext): string
    {
        $plaintext = collect(array_merge($plaintext, [
            'secret' => config('oauth.secret'),
        ]));

        $signature = hash(
            $plaintext->get('hash', 'md5'),
            http_build_query($plaintext->sortKeys()->toArray())
        );

        $plaintext = $plaintext->put('signature', $signature)
            ->forget('secret')
            ->toJson(JSON_UNESCAPED_UNICODE);

        return base64_encode($plaintext);
    }

    /**
     * Decrypt Method
     *
     * @param string $ciphertext
     * @return false|Collection
     */
    public static function decrypt(string $ciphertext)
    {
        $plaintext = base64_decode($ciphertext, true);

        if (!$plaintext) {
            return false;
        }

        $plaintext = json_decode($plaintext, true);

        if (json_last_error()) {
            return false;
        }

        $plaintext = collect($plaintext);

        if ($ciphertext == self::encrypt($plaintext->forget('signature')->toArray())) {
            return $plaintext;
        }

        return false;
    }

}
