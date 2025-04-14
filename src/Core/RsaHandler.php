<?php
namespace Backoffice\Core;

use Backoffice\Core\Config;

class RsaHandler {
    private string $passphraseKey;
    private string $secretPath;
    private string $privateKeyPath;
    private string $publicKeyPath;
    private $privateKey;
    private $publicKey;

    public function __construct()
    {
        $config = Config::getInstance();
        $this->passphraseKey = $config->privkey_passphrase;
        $this->secretPath = $config->secret_path;
        $secretFilename = $config->secret_filename;
        $this->privateKeyPath = "{$this->secretPath}/priv_$secret_filename.pem";
        $this->publicKeyPath = "{$this->secretPath}/publ_$secret_filename.pem";
    }

    /**
     * Generate RSA key pair with optional passphrase encryption
     * 
     * @param string|null $passphrase Passphrase to encrypt private key
     * @param int $bits Key length (2048 or 4096 recommended)
     * @throws RuntimeException If key generation fails
     */
    public function generateKeyPair(int $bits = 2048): void {
        $config = [
            "digest_alg" => "sha256",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        $keyResource = openssl_pkey_new($config);
        if (!$keyResource) {
            throw new RuntimeException("Key generation failed: " . openssl_error_string());
        }

        // Export private key with optional passphrase
        $exported = openssl_pkey_export($keyResource, $this->privateKey, $this->passphraseKey, $config);
        if (!$exported) {
            throw new RuntimeException("Private key export failed: " . openssl_error_string());
        }
        
        // Get public key
        $keyDetails = openssl_pkey_get_details($keyResource);
        if (!$keyDetails) {
            throw new RuntimeException("Public key extraction failed: " . openssl_error_string());
        }
        $this->publicKey = $keyDetails['key'];
    }

    /**
     * Write keys to filesystem
     * 
     * @param string $privateKeyPath
     * @param string $publicKeyPath
     * @throws RuntimeException If file write fails
     */
    public function writeKeys(): void {
        if (!file_put_contents($this->privateKeyPath, $this->privateKey)) {
            throw new RuntimeException("Failed to write private key to: $this->privateKeyPath");
        }

        if (!file_put_contents($this->publicKeyPath, $this->publicKey)) {
            throw new RuntimeException("Failed to write public key to: $this->publicKeyPath");
        }
    }

    /**
     * Load keys from filesystem
     * 
     * @param string $privateKeyPath
     * @param string $publicKeyPath
     * @param string|null $passphrase For encrypted private keys
     * @throws RuntimeException If file read fails
     */
    public function loadKeys(): void {
        if (!file_exists($this->privateKeyPath) || !is_readable($this->privateKeyPath)) {
            throw new RuntimeException("Private key file not found or unreadable: {$this->privateKeyPath}");
        }

        if (!file_exists($this->publicKeyPath) || !is_readable($this->publicKeyPath)) {
            throw new RuntimeException("Public key file not found or unreadable: {$this->publicKeyPath}");
        }

        $this->privateKey = openssl_pkey_get_private(file_get_contents($this->privateKeyPath), $this->passphraseKey);
        $this->publicKey = openssl_pkey_get_public(file_get_contents($this->publicKeyPath));

        if (!$this->privateKey) {
            throw new RuntimeException("Invalid private key or passphrase");
        }

        if (!$this->publicKey) {
            throw new RuntimeException("Invalid public key");
        }
    }

    /**
     * Encrypt data using hybrid approach (RSA + AES)
     * 
     * @param string $data
     * @return string Format: encrypted_aes_key:iv:ciphertext
     */
    public function encrypt(string $data): string {
        $this->validatePublicKeyLoaded();

        // Generate random AES key and IV
        $aesKey = random_bytes(32);
        $iv = random_bytes(16);

        // Encrypt data with AES
        $ciphertext = openssl_encrypt($data, 'aes-256-cbc', $aesKey, OPENSSL_RAW_DATA, $iv);
        if ($ciphertext === false) {
            throw new RuntimeException("AES encryption failed: " . openssl_error_string());
        }

        // Encrypt AES key with RSA
        if (!openssl_public_encrypt($aesKey, $encryptedAesKey, $this->publicKey)) {
            throw new RuntimeException("RSA encryption failed: " . openssl_error_string());
        }

        return base64_encode($encryptedAesKey) . ':' . 
               base64_encode($iv) . ':' . 
               base64_encode($ciphertext);
    }

    /**
     * Decrypt hybrid-encrypted data
     */
    public function decrypt(string $encryptedData): string {
        $this->validatePrivateKeyLoaded();

        // Parse components
        $parts = explode(':', $encryptedData);
        if (count($parts) !== 3) {
            throw new InvalidArgumentException("Invalid encrypted data format");
        }

        [$encryptedAesKey, $iv, $ciphertext] = array_map('base64_decode', $parts);

        // Decrypt AES key
        if (!openssl_private_decrypt($encryptedAesKey, $aesKey, $this->privateKey)) {
            throw new RuntimeException("RSA decryption failed: " . openssl_error_string());
        }

        // Decrypt data
        $plaintext = openssl_decrypt($ciphertext, 'aes-256-cbc', $aesKey, OPENSSL_RAW_DATA, $iv);
        if ($plaintext === false) {
            throw new RuntimeException("AES decryption failed: " . openssl_error_string());
        }

        return $plaintext;
    }

    /**
     * Create digital signature
     */
    public function sign(string $data): string {
        $this->validatePrivateKeyLoaded();

        if (!openssl_sign($data, $signature, $this->privateKey, OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException("Signing failed: " . openssl_error_string());
        }

        return base64_encode($signature);
    }

    /**
     * Verify digital signature
     */
    public function verify(string $data, string $signature): bool {
        $this->validatePublicKeyLoaded();

        $result = openssl_verify($data, base64_decode($signature), $this->publicKey, OPENSSL_ALGO_SHA256);
        if ($result === -1) {
            throw new RuntimeException("Signature verification error: " . openssl_error_string());
        }

        return $result === 1;
    }

    private function validatePrivateKeyLoaded(): void {
        if (!$this->privateKey) {
            throw new RuntimeException("Private key not loaded");
        }
    }

    private function validatePublicKeyLoaded(): void {
        if (!$this->publicKey) {
            throw new RuntimeException("Public key not loaded");
        }
    }

    /**
     * Get public key in PEM format
     */
    public function getPublicKey(): string {
        return $this->publicKey;
    }
}