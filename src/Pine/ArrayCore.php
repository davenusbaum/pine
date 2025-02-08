<?php

namespace Pine;

use ArrayAccess;
use Iterator;
use Countable;

/**
 * Base class to support common elements of ArrayMap and ArrayList
 */
class ArrayCore implements ArrayAccess, Iterator, Countable
{
    protected array $array;

    /**
     * Create a new array object
     * @param array|null $array optional array passed by value
     */
    public function __construct(?array $array = null) {
        if (isset($array)) {
            $this->array = $array;
        } else {
            $this->array = array();
        }
    }

    /**
     * Returns the number of elements in the underlying array
     * @return int
     */
    public function count(): int {
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
    public function offsetExists(mixed $offset): bool {
        return isset($this->array[$offset]);
    }

    /**
     * Offset to retrieve
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed {
        return $this->array[$offset] ?? null;
    }

    /**
     * Assign a value to the specified offset
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void {
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
    public function offsetUnset(mixed $offset): void {
        unset($this->array[$offset]);
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind(): void {
        reset($this->array);
    }

    /**
     * Checks if current position is valid
     * @return bool
     */
    public function valid():bool {
        return key($this->array) !== null;
    }
}