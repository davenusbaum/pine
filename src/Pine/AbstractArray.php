<?php

namespace Pine;

/**
 * The base level method for a object wrapper around an array
 */
abstract class AbstractArray implements \ArrayAccess, \Iterator
{
    protected $array;

    /**
     * Returns the number of elements in the underlying array
     * @return int
     */
    public function count() {
        return count($this->array);
    }

    /**
     * Return the current element
     * @return mixed
     */
    public function current() {
        return current($this->array);
    }

    /**
     * Return the key of the current element
     * @return mixed
     */
    public function key() {
        return key($this->array);
    }

    /**
     * Move forward to next element
     */
    public function next() {
        next($this->array);
    }

    /**
     * Whether an offset exists
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset) {
        return isset($this->array[$offset]);
    }

    /**
     * Offset to retrieve
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset) {
        return isset($this->array[$offset]) ? $this->array[$offset] : null;
    }

    /**
     * Assign a value to the specified offset
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->array[] = $value;
        } else {
            $this->array[$offset] = $value;
        }
    }

    /**
     * Unset an offset
     * @param mixed $offset
     */
    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind() {
        reset($this->array);
    }

    /**
     * Returns the collection as an array
     * @return array
     */
    public function toArray() {
        return $this->array;
    }

    /**
     * Checks if current position is valid
     * @return boolean
     */
    public function valid() {
        return key($this->array) !== null;
    }
}