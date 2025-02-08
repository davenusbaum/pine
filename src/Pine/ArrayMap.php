<?php

namespace Pine;

use Iterator;

/**
 * Parameters hold key value pairs and is intended to have behavior similar
 * to that of a JavaScript object (in order to keep with the Express theme).
 */
class ArrayMap extends ArrayCore
{

    /**
     * Magic method to return a Parameter item as a property.
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed {
        return $this->offsetGet($name);
    }

    /**
     * Magic method to set a collection item as a property.
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, mixed $value) {
        $this->offsetSet($name, $value);
    }

    public function addAll(array|Iterator $parameters): void {
        foreach ($parameters as $name => $value) {
            $this->array[$name] = $value;
        }
    }

    public function get(string $name, mixed $default = null): mixed {
        return $this->offsetGet($name) ?? $default;
    }

    /**
     * Returns true if the named value is set for the collection
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool {
        return $this->offsetExists($name);
    }

    public function keys(): array {
        return array_keys($this->array);
    }

    /**
     * Delete a parameter
     * @param string $name
     */
    public function remove(string $name): void {
        $this->offsetUnset($name);
    }

    public function set(string $name, mixed $value): void {
        $this->offsetSet($name, $value);
    }

    /**
     * Returns the collection as an array
     * @return array
     */
    public function toArray(): array {
        return $this->array;
    }

    public function values(): array {
        return array_values($this->array);
    }
}