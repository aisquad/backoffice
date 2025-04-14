<?php

namespace Backoffice\Core\Jwt;


use Backoffice\Core\Config;
use Backoffice\Core\Jwt\Traits\JwtTrait;


/**
 * Represents the payload of a JWT (JSON Web Token).
 */
class Payload
{
    use JwtTrait;

    /**
     * @var string|null The issuer of the JWT.
     */
    private ?string $iss = null;

    /**
     * @var string|null The subject of the JWT.
     */
    private ?int $sub = null;

    /**
     * @var string|null The audience of the JWT.
     */
    private ?string $aud = null;

    /**
     * @var int|null The expiration time of the JWT (Unix timestamp).
     */
    private ?int $exp = null;

    /**
     * @var int|null The issued-at time of the JWT (Unix timestamp).
     */
    private ?int $iat = null;

    /**
     * @var int|null The not-before time of the JWT (Unix timestamp).
     */
    private ?int $nbf = null;

    /**
     * @var string|null The JWT ID.
     */
    private ?string $jti = null;

    /**
     * @var int|null The default expiration time in seconds.
     */
    public ?int $defaultExp = null;

    /**
     * Constructor for the PayLoad class.
     *
     * @param string $issuer The issuer of the JWT.
     * @param string $subject The subject of the JWT.
     */
    public function __construct(string $issuer, int $subject, ?int $expirationMinutes=null)
    {
        $config = Config::getInstance();
        $this->defaultExp = $config->jwt_default_expiration;
        $this->iss = $issuer;
        $this->sub = $subject;
        $this->iat = time();
        if (isset($expitationMinutes) && $expitationMinutes > 0) {
            $this->setExpirationTime(minutes: $expitationMinutes);
        }
    }

    /**
     * Sets the expiration time for the JWT.
     *
     * @param int $days Number of days until expiration.
     * @param int $hours Number of hours until expiration.
     * @param int $minutes Number of minutes until expiration.
     * @param int $seconds Number of seconds until expiration.
     * @return void
     */
    public function setExpirationTime(int $days = 0, int $hours = 0, int $minutes = 0, int $seconds = 0): void
    {
        $days = abs($days); $hours = abs($hours); $minutes = abs($minutes); $seconds = abs($seconds);
        $time = $days * 86400 + $hours * 3600 + $minutes * 60 + $seconds;
        if ($time === 0) {
            $time += $this->defaultExp;
        }
        $this->exp = $this->iat + $time;
    }

    /**
     * Sets the audience for the JWT.
     *
     * @param string $audience The audience to set.
     * @return void
     */
    public function setAudience(string $audience): void
    {
        $this->aud = $audience;
    }

    /**
     * Sets the not-before time for the JWT.
     *
     * @param int|null $timestamp The not-before timestamp. If null, defaults to the current time.
     * @return void
     */
    public function setNotBefore(int $timestamp = null): void
    {
        $this->nbf = $timestamp ?? time();
    }

    /**
     * Generates a unique JWT ID (JTI).
     *
     * @return void
     */
    public function generateJti(): void
    {
        $this->jti = $this->generateUuid4();
    }

    /**
     * Validates the payload to ensure that expiration time is set correctly.
     *
     * @throws RuntimeException If the expiration time is not set, is in the past, or is before the not-before time.
     * @return void
     */
    private function validatePayload(): void
    {
        // Ensure expiration time is set correctly.
        if ($this->exp === null) {
            throw new \RuntimeException("Expiration time (exp) must be set.");
        }

        if ($this->exp <= $this->iat) {
            throw new \RuntimeException("Expiration time (exp) must be in the future.");
        }

        if ($this->nbf !== null && $this->exp <= $this->nbf) {
            throw new \RuntimeException("Expiration time (exp) must be after not-before claim (nbf).");
        }
    }

    /**
     * Converts the payload to a JSON string after validation.
     *
     * @return string A JSON string representing the payload.
     */
    public function toJson(): string
    {
        $this->validatePayload();

        return json_encode($this->toArray());
    }

    /**
     * Generates a JTI (JWT ID) using a UUID v4.
     *
     * @dev This is a temporary workaround for development purposes.
     * TODO: Replace with a proper UUID library (e.g., Ramsey\Uuid) in production.
     * @return string
     */
    public function generateUuid4(): void
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr(ord($bytes[6]) & 0x0F | 0x40);
        $bytes[8] = chr(ord($bytes[8]) & 0x3F | 0x80);

        $this->jti = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }

    /**
     * Generates a JTI (JWT ID) using a UUID v4.
     *
     * @return string
     * @codeCoverageIgnore
     */
    private function generateUuid4_prod(): string
    {
        // use Ramsey\Uuid\Uuid;

        //return Uuid::uuid4()->toString();
        return "";
    }

    public function getExp() : int
    {
        return $this->exp;
    }
}
