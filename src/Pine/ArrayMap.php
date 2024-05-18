<?php

namespace Pine;

use Pine\AbstractArray;

/**
 * An object wrapper around an associative array
 */
class ArrayMap extends AbstractArray
{
    /**
     * Create a new collection object
     * @param array $array optional array passed by value
     */
    public function __construct($array = null) {
        if(isset($array) && is_array($array)) {
            $this->array = $array;
        } else {
            $this->array = array();
        }
    }

    /**
     * Magic method to return a collection item as a property.
     * @param mixed $name
     * @return mixed
     */
    public function __get($name) {
        return $this->get($name);
    }

    /**
     * Magic method to set a collection item as a property.
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name,$value) {
        $this->set($name,$value);
    }

    /**
     * If true if the named value is set for the collection
     * @param string $name
     * @return boolean
     */
    public function has($name) {
        return isset($this->array[$name]);
    }

    /**
     * Returns the value for the specified name.
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name,$default = null) {
        if(isset($this->array[$name])) {
            return $this->array[$name];
        }
        return $default;
    }

    /**
     * Remove a named value from the collection
     * @param mixed $name
     */
    public function remove($name) {
        if(array_key_exists($name, $this->array)) {
            unset($this->array[$name]);
        }
    }

    /**
     * Set a value in the collection
     * @param string $name
     * @param mixed $value
     */
    public function set($name,$value) {
        $this->array[$name] = $value;
    }
}