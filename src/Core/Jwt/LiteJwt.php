<?php
namespace Backoffice\Core\Jwt;

use Backoffice\Core\Config;
use Backoffice\Core\Logger;
use Backoffice\Core\Jwt\JwtHeader;
use Backoffice\Core\Jwt\Payload;

$logger = new Logger();

 /*
 * @security
 * - Empêche les attaques de rejeu via jti
 * - Protection contre les attaques CSRF via SameSite cookie
 * - Clés stockées hors webroot
 * - Surveillance des cas échoués
 */



/**
 * A lightweight JWT (JSON Web Token) handler for encoding and verifying tokens.
 */
class LiteJwt
{
    /**
     * @var string|null The passphrase for the private key.
     */
    private $passphraseKey;

    /**
     * @var resource|null The private key resource.
     */
    private $privateKey;

    /**
     * @var resource|null The public key resource.
     */
    private $publicKey;

    /**
     * @var int The maximum age of the token in seconds.
     */
    private int $maxAge;

    private string $value;

    private int $expire;

    private const CLOCK_SKEW = 300;

    private string $tokenKey = '';

    /**
     * Constructor for the LiteJWT class. Loads the keys from file.
     */
    public function __construct()
    {
        $config = Config::getInstance();
        $this->maxAge = $config->jwt_default_expiration;
        $this->tokenKey = $config->token_key;
        $this->loadKeys($config);
    }

    /**
     * Loads the private and public keys from file.
     *
     * @throws RuntimeException If the key files are not found, unreadable, or invalid.
     * @return void
     */
    private function loadKeys(Config $config)
    {
        $passphrase = $config->privkey_passphrase;
        $secretPath = $config->secret_path;
        $filename = $config->secret_filename;

        $privateKeyPath = implode(DIRECTORY_SEPARATOR, [$secretPath, "priv_$filename.pem"]);
        $publicKeyPath = implode(DIRECTORY_SEPARATOR, [$secretPath, "publ_$filename.pem"]);

        if (!is_readable($privateKeyPath) || !is_readable($publicKeyPath)) {
            throw new \RuntimeException("Private & public keys not found or unreadable: $privateKeyPath / $publicKeyPath");
        }

        $this->privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath), $passphrase);
        $this->publicKey = openssl_pkey_get_public(file_get_contents($publicKeyPath));

        if (!$this->privateKey  || !$this->publicKey) {
            throw new \RuntimeException("Invalid key or passphrase");
        }
    }

    /**
     * Encodes the JWT header and payload into a JWT token.
     *
     * @param JwtHeader $header The JWT header object.
     * @param Payload $payload The JWT payload object.
     * @return string The encoded JWT token.
     */
    public function encode(JwtHeader $header, Payload $payload): string
    {
        $token = new TokenEncoder();
        $token->encodeUnsignedPart($header, $payload);
        $this->maxAge = $payload->defaultExp;
        $signatureBytes = $this->sign($token->getEncodedUnsignedPart());
        $token->appendSignature($signatureBytes);
        $this->expire = $token->getExpireTime();
        $this->value = $token->value();

        return $this->value;
    }

    /**
     * Signs the encoded unsigned part of the JWT using the private key.
     *
     * @param string $encodedUnsignedPart The encoded unsigned part of the JWT.
     * @return string The signature bytes.
     */
    private function sign(string $encodedUnsignedPart): string
    {
        $signed = openssl_sign($encodedUnsignedPart, $signatureBytes, $this->privateKey, OPENSSL_ALGO_SHA256);
        if(!$signed)
            throw new \RuntimeException("Signature generation failed");
        return $signatureBytes;
    }

    /**
     * Verifies the signature of the JWT using the public key.
     *
     * @param TokenDecoder $token The token decoder instance.
     * @throws RuntimeException If signature is invalid or openssl_verify return some error (-1).
     */
    private function verifySignature(TokenDecoder $token): void
    {
        $encodedUnsignedPart = $token->getEncodedUnsignedPart();
        $signatureBytes = $token->getSignatureBytes();

        $result = openssl_verify($encodedUnsignedPart, $signatureBytes, $this->publicKey, OPENSSL_ALGO_SHA256);
        if ($result < 1) {
            error_log("JWT Error: invalid signature (signature result: $result)");
            throw new \RuntimeException("Invalid token format");
        }
    }

   
    /**
     * Verifies the claims in the JWT header.
     *
     * @param array $header The JWT header.
     * @throws RuntimeException If the algorithm in the header is invalid.
     */
    private function verifyHeaderClaims(TokenDecoder $token): void
    {
        $header = $token->getJsonHeader();
        $know_algos = ['RS256']; // See JWTHeader class, attribute implementedAlgoritms, list will be update according implementations.
        if (!in_array($header['alg'], ['RS256']) ) {
            error_log("JWT Error: invalid header algorithm");
            throw new \RuntimeException('Invalid algorithm in JWT header');
        }
    }

    /**
     * Verifies the claims in the JWT payload.
     *
     * @param array $payload The JWT payload.
     * @throws RuntimeException If the algorithm in the header is invalid.
     */
    private function verifyPayload(TokenDecoder $token): void
    {
        $payload = $token->getJsonPayload();
        $now = time();

        // Check expiration time
        if (!isset($payload['exp']) || $payload['exp'] < ($now - self::CLOCK_SKEW)) {
            // Expired or invalid format
            error_log("JWT Error: invalid expiration time (exp)");
            throw new \RuntimeException("Invalid token format");
        }

        // Check not-before time
        if (isset($payload['nbf']) && $payload['nbf'] > $now) {
            // Token not yet valid
            error_log("JWT Error: invalid not-before (nbf)");
            throw new \RuntimeException("Invalid token format");
        }

        // Check issued-at time with max age
        if (isset($payload['iat']) && $this->maxAge > 0 && ($now - $payload['iat']) > $this->maxAge) {
            // Token too old
            error_log("JWT Error: invalid issue-at (iat)");
            throw new \RuntimeException("Invalid token format");
        }

        // TODO: valid iss, sub, aud claim
    }

    /**
     * Verifies the JWT token.
     *
     * @param string $incomingToken The JWT token to verify.
     * @throws RuntimeException If the signature, header, or payload is invalid.
     * @return bool True if the token is valid, false otherwise.
     */
    public function verify(string $incomingToken): bool
    {
        try {
            $token = new TokenDecoder();
            $token->split($incomingToken);

            $this->verifySignature($token);
            $this->verifyHeaderClaims($token);
            $this->verifyPayload($token);

            return true;
        } catch (\RuntimeException $e) {
            return false;
        }
    }

    public function save() {
        setcookie(
            'jwt-bckoff',
            $this->value,
            [
                'expires' => $this->expire,
                'path' => '/',
                'domain' => '',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]
        );
    }
}
