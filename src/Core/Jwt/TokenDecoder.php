<?php

namespace Backoffice\Core\Jwt;

use Backoffice\Core\Jwt\Traits\Base64UrlTrait;


/**
 * Decodes a JWT token string into its header, payload, and signature components.
 */
class TokenDecoder
{
    use Base64UrlTrait;

    /**
     * @var string The incoming JWT token.
     */
    private string $incomingToken;

    /**
     * @var array The JSON decoded header of the JWT.
     */
    private array $jsonHeader;

    /**
     * @var array The JSON decoded payload of the JWT.
     */
    private array $jsonPayload;

    /**
     * @var string The encoded unsigned part of the JWT (header and payload).
     */
    private string $encodedUnsignedPart;

    /**
     * @var string The signature bytes of the JWT.
     */
    private string $signatureBytes;

    /**
     * Gets the signature bytes of the JWT.
     *
     * @return string The signature bytes.
     */
    public function getSignatureBytes(): string
    {
        return $this->signatureBytes;
    }

    /**
     * Gets the encoded unsigned part of the JWT.
     *
     * @return string The encoded unsigned part.
     */
    public function getEncodedUnsignedPart(): string
    {
        return $this->encodedUnsignedPart;
    }

    /**
     * Gets the JSON decoded header of the JWT.
     *
     * @return array The JSON decoded header.
     */
    public function getJsonHeader(): array
    {
        return $this->jsonHeader;
    }

    /**
     * Gets the JSON decoded payload of the JWT.
     *
     * @return array The JSON decoded payload.
     */
    public function getJsonPayload(): array
    {
        return $this->jsonPayload;
    }

    /**
     * Splits the JWT into its header, payload, and signature components.
     *
     * @param string $incomingToken The JWT token to split.
     * @throws RuntimeException If the token is invalid.
     * @return void
     */
    public function split(string $incomingToken): void
    {
        // method to verify token.
        $parts = explode('.', $incomingToken);
        if (count($parts) !== 3)
            throw new \RuntimeException("Invalid token");

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;
        $this->encodedUnsignedPart = "$encodedHeader.$encodedPayload";
        $base64Header = $this->restoreBase64Encoding($encodedHeader);
        $this->jsonHeader = json_decode(base64_decode($base64Header), true);
        $base64Payload = $this->restoreBase64Encoding($encodedPayload);
        $this->jsonPayload = json_decode(base64_decode($base64Payload), true);
        $base64Signature = $this->restoreBase64Encoding($encodedSignature);
        $this->signatureBytes = base64_decode($base64Signature);
    }
}

