<?php
namespace Backoffice\Core;

use \stdClass;

/**
 * A singleton class for managing configuration settings from a .env file.
 * This class allows dynamic access to configuration values and supports saving modifications back to the file.
 */
class Config extends stdClass
{
    /**
     * @var Config|null The singleton instance of the Config class.
     */
    private static $instance = null;

    /**
     * @var string The path to the .env file.
     */
    private string $envPath;

    /**
     * @var array An array of original lines from the .env file.
     */
    private array $originalLines = [];

    /**
     * @var array An associative array of modified variables (key => original key).
     */
    private array $modifiedVars = [];

    /**
     * @var array An associative array of existing keys (lowercase key => true) to prevent duplicates.
     */
    private array $existingKeys = [];

    /**
     * Private constructor to prevent direct instantiation.
     *
     * @param string|null $envPath The optional path to the .env file.
     * @throws \RuntimeException If the .env file is not found or contains duplicate keys.
     */
    private function __construct(?string $envPath = null)
    {
        // Set the default .env file path if none is provided
        $this->envPath = $envPath ?? __DIR__ . DIRECTORY_SEPARATOR . '../../config/.env';

        // Check if the .env file exists
        if (!file_exists($this->envPath)) {
            throw new \RuntimeException("File .env not found at path: {$this->envPath}");
        }

        // Load the content of the .env file
        $envContent = file_get_contents($this->envPath);
        $this->originalLines = explode("\n", $envContent);

        // Parse each line in the .env file
        foreach ($this->originalLines as $line) {
            $line = rtrim($line, "\r");
            $trimmedLine = trim($line);

            // Skip comments, empty lines, and lines without '='
            if (strpos($trimmedLine, '#') === 0 || $trimmedLine === '' || !str_contains($line, '=')) {
                continue;
            }

            // Extract key and value, ignoring inline comments
            [$key, $value] = $this->parseLine($line);
            $lowerKey = strtolower($key);

            // Check for duplicate keys
            if (isset($this->existingKeys[$lowerKey])) {
                throw new \RuntimeException("Duplicate key: $key in file {$this->envPath}");
            }

            // Store the key-value pair and mark the key as existing
            $this->existingKeys[$lowerKey] = true;
            $this->$lowerKey = $this->parseValue($value);
        }
    }

    /**
     * Static method to get the singleton instance of the Config class.
     *
     * @param string|null $envPath The optional path to the .env file.
     * @return Config The singleton instance.
     */
    public static function getInstance(?string $envPath = null): self
    {
        if (self::$instance === null) {
            self::$instance = new self($envPath);
        }

        return self::$instance;
    }

    /**
     * Prevent cloning of the singleton instance.
     */
    private function __clone() {}

    /**
     * Prevent unserialization of the singleton instance.
     */
    public function __wakeup() {}

    /**
     * Magic method for dynamic property access.
     *
     * @param string $name The name of the property to retrieve.
     * @return mixed The value of the property or null if it doesn't exist.
     */
    public function __get(string $name)
    {
        return $this->get($name);
    }

    /**
     * Magic method for dynamic property assignment.
     *
     * @param string $name The name of the property to set.
     * @param mixed $value The value to assign to the property.
     */
    public function __set(string $name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * Get a configuration value by its key.
     *
     * @param string $key The key of the configuration value to retrieve.
     * @param mixed $default The default value to return if the key doesn't exist.
     * @return mixed The value associated with the key or the default value.
     */
    public function get(string $key, $default = null)
    {
        $lowerKey = strtolower(trim($key));
        return property_exists($this, $lowerKey) ? $this->$lowerKey : $default;
    }

    /**
     * Set a configuration value by its key.
     *
     * @param string $key The key of the configuration value to set.
     * @param mixed $value The value to assign to the key.
     */
    public function set($key, $value)
    {
        $lowerKey = strtolower(trim($key));
        $this->$lowerKey = $this->parseValue($value);
        $this->modifiedVars[$lowerKey] = $key;
    }

    /**
     * Parse a line from the .env file to extract the key and value.
     *
     * @param string $line A single line from the .env file.
     * @return array An array containing the key and value, where the value has no enclosing quotes.
     *
     * Example:
     * Input: 'DB_PSWD="%Vilnius25#45~54"'
     * Output: ['DB_PSWD', '%Vilnius25#45~54']
     */
    function parseLine(string $line): array
    {
        // Regex pattern to match key-value pairs in .env files
        // Supports:
        // - Keys without spaces or '='
        // - Values enclosed in quotes (single or double)
        // - Values without quotes
        // - Inline comments starting with #
        $pattern = '/^(?<key>[^=\s]+)\s*=\s*(?:(?<quote>["\'])(?<quoted>(?:\\\\.|(?!\k<quote>).)*)\k<quote>|(?<unquoted>[^#\s]+(?:\s+[^#\s]+)*))?(?:\s*#.*)?$/';

        // Attempt to match the pattern against the line
        if (preg_match($pattern, $line, $matches)) {
            $key = $matches['key'];
            
            // Check if the value is enclosed in quotes
            if ($matches['quoted']) {
                $value = $matches['quoted'];
                $value = preg_replace('/\\\\(.)/', '$1', $value);
            } else {
                $value = trim($matches['unquoted'] ?? '');
            }
    
            return [$key, $value];
        }
        return ['', ''];
    }
    

    /**
     * Parse a value from the .env file to its appropriate type.
     *
     * @param mixed $value The raw value from the .env file.
     * @return mixed The parsed value (null, boolean, integer, float, or string).
     */
    private function parseValue($value)
    {
        // Handle null values
        if ($value === 'null' || $value === '') {
            return null;
        }

        // Handle boolean values
        if (in_array(strtolower($value), ['true', 'on', 'yes'])) {
            return true;
        }
        if (in_array(strtolower($value), ['false', 'off', 'no'])) {
            return false;
        }

        // Handle integer values
        if (is_numeric($value) && ctype_digit((string)$value)) {
            return (int)$value;
        }

        // Handle float values
        if (preg_match('/^-?\d+\.\d+$/', $value)) {
            return (float)$value;
        }

        // Handle quoted strings
        if (preg_match('/^(["\'])(.*)\1$/', $value, $matches)) {
            $value = $matches[2];
            $value = str_replace("\\{$matches[1]}", $matches[1], $value);
        }

        return $value;
    }

    /**
     * Save modifications back to the .env file.
     */
    public function save()
    {
        $newLines = [];

        // Process each original line
        foreach ($this->originalLines as $line) {
            $trimmedLine = trim($line);

            // Keep pure comments and empty lines
            if (strpos($trimmedLine, '#') === 0 || $trimmedLine === '') {
                $newLines[] = $line;
                continue;
            }

            // Skip lines without '='
            if (!str_contains($line, '=')) {
                $newLines[] = $line;
                continue;
            }

            // Extract key and original value
            [$key, $originalValue] = $this->parseLine($line);
            $lowerKey = strtolower($key);

            // Update modified values
            if (isset($this->modifiedVars[$lowerKey])) {
                $newValue = $this->formatValue($this->$lowerKey);
                $newLines[] = $this->modifiedVars[$lowerKey] . '=' . $newValue;
                unset($this->modifiedVars[$lowerKey]);
            } else {
                $newLines[] = $line;
            }
        }

        // Add new modified variables
        foreach ($this->modifiedVars as $lowerKey => $originalKey) {
            $newLines[] = $originalKey . '=' . $this->formatValue($this->$lowerKey);
        }

        // Write the updated content back to the .env file
        file_put_contents($this->envPath, implode("\n", $newLines));
    }

    /**
     * Format a value for saving to the .env file.
     *
     * @param mixed $value The value to format.
     * @return string The formatted value as a string.
     */
    private function formatValue($value)
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_string($value) && preg_match('/\s|=|"/', $value)) {
            $value = str_replace('"', '\"', $value);
            return '"' . $value . '"';
        }

        return $value;
    }
}