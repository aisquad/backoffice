<?php

namespace Backoffice\Core\Jwt\Traits;

/**
 * Trait providing utility methods for converting objects to array, JSON, and Base64 representations.
 */
trait JwtTrait
{
    /**
     * Converts the object's properties to an associative array, excluding null values and key longer than 8 chars.
     *
     * @return array An associative array representing the object's properties.
     */
    public function toArray(): array
    {
        $array = [];
        foreach (get_object_vars($this) as $key => $val) {
            if ($val !== null && strlen($key) < 9) {
                $array[$key] = $val;
            }
        }
        return $array;
    }

    /**
     * Converts the object's properties to a JSON string.
     *
     * @return string A JSON string representing the object.
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Converts the object's properties to a Base64 encoded string.
     *
     * @return string A Base64 encoded string representing the object.
     */
    public function toBase64(): string
    {
        return base64_encode($this->toJson());
    }
}

