<?php

namespace Pine;

/**
 * Abstract class that provides a basic object wrapper for an array
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
    public function current(): mixed {
        return current($this->array);
    }

    /**
     * Return the key of the current element
     * @return mixed
     */
    public function key(): mixed {
        return key($this->array);
    }

    /**
     * Move forward to next element
     * @return void
     */
    public function next(): void {
        next($this->array);
    }

    /**
     * Whether an offset exists
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool {
        return isset($this->array[$offset]);
    }

    /**
     * Offset to retrieve
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset): mixed {
        return isset($this->array[$offset]) ? $this->array[$offset] : null;
    }

    /**
     * Assign a value to the specified offset
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value): void {
        if (is_null($offset)) {
            $this->array[] = $value;
        } else {
            $this->array[$offset] = $value;
        }
    }

    /**
     * Unset an offset
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset): void {
        unset($this->container[$offset]);
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind(): void {
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
     * @return bool
     */
    public function valid():bool {
        return key($this->array) !== null;
    }
}