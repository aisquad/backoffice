<?php

namespace Backoffice\Core\Jwt\Traits;

/**
 * Trait providing utility methods for Base64 URL encoding and decoding.
 */
trait Base64UrlTrait
{
    /**
     * Encodes a string using Base64 URL encoding.
     *
     * @param string $data The string to encode.
     * @return string The Base64 URL encoded string.
     */
    public function safeUrlEncode(string $data): string
    {
        // set string with safe url chars
        return rtrim(strtr($data, '+/', '-_'), '=');
    }

    /**
     * Restores standard Base64 encoding from a Base64 URL encoded string.
     *
     * @param string $data The Base64 URL encoded string.
     * @return string The standard Base64 encoded string.
     */
    public function restoreBase64Encoding(string $data): string
    {
        // method for retrieving standard base64 encoding
        $stdB64ToSafeUrlChars = strtr($data, '-_', '+/');
        $padding = (4 - (strlen($stdB64ToSafeUrlChars) % 4)) % 4;
        return $stdB64ToSafeUrlChars . str_repeat('=', $padding);
    }
}

