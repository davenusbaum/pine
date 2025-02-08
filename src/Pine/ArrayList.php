<?php

namespace Pine;

use ArrayAccess;
use Iterator;
use Countable;
use OutOfBoundsException;

/**
 * A sequential list
 */
class ArrayList extends ArrayCore
{
    public function add(mixed $item): void {
        $this->array[] = $item;
    }

    public function addAll(ArrayList|array $list): void {
        foreach ($list as $item) {
            $this->array[] = $item;
        }
    }

    public function get(int $index, mixed $default = null): mixed {
        return $this->array[$index] ?? $default;
    }

    public function set(int $index, mixed $value): mixed {
        if ($index < 0 || $index >= count($this->array)) {
            throw new OutOfBoundsException("Index '$index' does not exist in array list.");
        }
        $previous = $this->array[$index];
        $this->array[$index] = $value;
        return $previous;
    }
}