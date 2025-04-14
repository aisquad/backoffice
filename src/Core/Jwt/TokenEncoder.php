<?php

namespace Backoffice\Core\Jwt;

use Backoffice\Core\Jwt\Traits\Base64UrlTrait;
use Backoffice\Core\Jwt\JWTHeader;
use Backoffice\Core\Jwt\Payload;

/**
 * Encodes JWT headers and payloads into a JWT token string.
 */
class TokenEncoder
{
    use Base64UrlTrait;

    /**
     * @var string|null The complete JWT token.
     */
    private ?string $token = null;

    /**
     * @var string|null The encoded unsigned part of the JWT (header and payload).
     */
    private ?string $encodedUnsignedPart = null;

    /**
     * @var JWTHeader|null The JWT header object.
     */
    private ?JWTHeader $header = null;

    /**
     * @var Payload|null The JWT payload object.
     */
    private ?Payload $payload = null;

    /**
     * @var string|null The signature bytes of the JWT.
     */
    private ?string $signatureBytes = null;

    /**
     * Appends the signature to the encoded unsigned part to complete the JWT.
     *
     * @param string $signatureBytes The signature bytes to append.
     * @return void
     */
    public function appendSignature(string $signatureBytes): void
    {
        $this->signatureBytes = $signatureBytes;
        $base64Signature = base64_encode($signatureBytes);
        $encodedSignature = $this->safeUrlEncode($base64Signature);
        $this->token = "{$this->encodedUnsignedPart}.$encodedSignature";
    }

    /**
     * Encodes the header and payload into a Base64 URL encoded string.
     *
     * @param JWTHeader $header The JWT header object.
     * @param PayLoad $payload The JWT payload object.
     * @return void
     */
    public function encodeUnsignedPart(JWTHeader $header, PayLoad $payload): void
    {
        $this->header = $header;
        $this->payload = $payload;

        $encodedHeader = $header->toBase64();
        $encodedPayload = $payload->toBase64();

        $this->encodedUnsignedPart = $this->safeUrlEncode("$encodedHeader.$encodedPayload");
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
     * Gets the JSON representation of the payload.
     *
     * @return string The JSON representation of the payload.
     */
    public function getPayload(): string
    {
        return $this->payload->toJson();
    }

    /**
     * Gets the signature bytes of the JWT.
     *
     * @return string The signature bytes.
     */
    public function getSignatureBytes() : string
    {
        return $this->signatureBytes;
    }

    public function getExpireTime() : int
    {
        return $this->payload->getExp();
    }

    /**
     * Gets the complete JWT token.
     *
     * @return string The complete JWT token.
     */
    public function value() : string
    {
        return $this->token;
    }
}

