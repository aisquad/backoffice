<?php
namespace Backoffice\Utils;

use Backoffice\Core\Config;

class Base64Obfuscator {
    private Config $config;
    private string $alphabet;
    private string $base65 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789/+=';
    
    public function __construct()
    {
        $this->config = Config::getInstance();
        $this->dispatch();
    }
    
    private function dispatch() : void
    {
        if ($this->config->obfuscated_base64_alphabet !== null) {
            $this->alphabet = $this->config->obfuscated_base64_alphabet;
        } else {
            $this->setAlphabet();
        }
    }

    function getAlphabet(): string
    {
        return $this->alphabet;
    }

    function setAlphabet() : void
    {
        // Alphanumerical chars
        $base62 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $alphanum = str_split($base62);
        
        // Shuffle alphanum chars
        shuffle($alphanum);
        
        // Extended chars will be three corresponding to base64 '+', '/' and '='.
        $special_chars = '~_-.';

        // Shuffle special chars.
        $special_chars = str_split($special_chars);
        shuffle($special_chars);

        // Choose among
        $chosen_special_chars = array_slice($special_chars, 0, 3);
        
        // Concatenate all chars
        $this->alphabet = implode('', $alphanum) . implode('', $chosen_special_chars);

        $this->config->set('OBFUSCATED_BASE64_ALPHABET', $this->alphabet);
        $this->config->save();
    }
    
    function obfuscate($base64_encoded): string
    {
        $obfuscated = strtr($base64_encoded, $this->base65, $this->alphabet);

        return $obfuscated;
    }

    function deobfuscate($obfuscated) {
        $deobfuscated = strtr($obfuscated, $this->alphabet, $this->base65);

        return $deobfuscated;
    }

    public function encode($alphanumerical) : string
    {
        $encoded = base64_encode($alphanumerical);
        $obfuscated = $this->obfuscate($encoded);
        return $obfuscated;
    }
}