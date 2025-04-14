<?php

namespace Backoffice\Core\Jwt;

use Backoffice\Core\Jwt\Traits\JwtTrait;

/**
 * Represents the header of a JWT (JSON Web Token).
 */
class JwtHeader
{
    use JwtTrait;

    private array $implementedAlgoritms = ['ES256' => false, 'HS256' => false, 'RS256' => true];

    /* 
    
    private array $implementedAlgorithms = ['RS256' => 'openssl'];
    public function getSigner() {
        return new $this->implementedAlgorithms[$this->alg]();
    }

    */


    /**
     * @var string The algorithm used for signing the JWT. Defaults to RS256.
     */
    private string $alg = 'RS256';

    /**
     * @var string The type of the JWT. Defaults to JWT.
     */
    private string $typ = 'JWT';

    /**
     * @var string|null The content type of the JWT. Null by default.
     */
    private ?string $ctp = null;

    /**
     * Sets the algorithm used for signing the JWT.
     *
     * @param string $algorithm The algorithm to use (e.g., HS256, RS256).
     * @throws RuntimeException If an unknown algorithm is provided.
     * @return void
     */
    public function setAlgorithm(string $algorithm): void
    {
        $know_algos = array_keys(array_filter($this->implementedAlgoritms));
        if (in_array($algorithm, $know_algos)) {
            $this->alg = $algorithm;
        } else {
            throw new \RuntimeException("Unknown algorithm ($algorithm). Available algorithms are: " . join(', ', $known_algos));
        }
    }

    /**
     * Sets the content type of the JWT.
     *
     * @param string $content_type The content type to set.
     * @return void
     */
    public function setContentType($content_type): void
    {
        $this->ctp = $content_type;
    }
}

