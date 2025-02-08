<?php

namespace Pine;

class Body extends ArrayMap {

    public function get($name, $default = null, $delimiter = '.'): mixed {
        if (str_contains($name, $delimiter)) {
            return parent::get($name, $default);
        }
        $value = $this->array;
        $key = strtok($name, $delimiter);
        while($key !== false) {
            if(isset($value[$key])) {
                $value = $value[$key];
            } else {
                return $default;
            }
            $key = strtok($delimiter);
        }
        return $value;
    }
}
