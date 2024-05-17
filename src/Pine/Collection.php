<?php
namespace pine;

class Collection extends AbstractArray {
	
	/** @var array The underlying array */
	protected $array;
	
	/**
	 * Create a new collection object
	 * @param array $array optional array passed by value
	 * @param array $_array option array passed by reference
	 */
	public function __construct($array = null,&$array_reference = null) {
		if(isset($array) && is_array($array)) {
			$this->array = $array;
		} else if(isset($array_reference) && is_array($array_reference)) {
			$this->array = &$array_reference;
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
		return $this->offsetGet($name);
	}
	
	/**
	 * Magic method to set a collection item as a property.
	 * @param mixed $name
	 * @param mixed $value
	 */
	public function __set($name,$value) {
		$this->offsetSet($name,$value);
	}
	
	/**
	 * Add an item to the end of the list
	 * @param mixed $item
	 */
	public function add($item) {
		$this->array[] = $item;
	}
	
	/**
	 * Add all of the items in a list to this list
	 * @param array $list
	 */
	public function addAll($list) {
		foreach ($list as $item) {
			$this->add($item);
		}
	}
	
	/**
	 * Returns the number of elements in the underlying array
	 * @return int
	 */
	public function count() {
		return count($this->array);
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