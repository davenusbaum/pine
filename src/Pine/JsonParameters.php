<?php

namespace pine;

class JsonParameters extends Parameters {

    public function get($name, $default = null, $delimiter = '.') {
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
