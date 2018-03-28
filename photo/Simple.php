<?php

/**
 * nodePoint object
 */
class Simple {
	private $val;
	private $parent;
	
	public function __construct($val, Simple $parent = null){
		$this->val = $val;
		$this->parent = $parent;
	}
	
	public function __get($name){
		return $this->$name;
	}
	
	public function setParent(Simple $parent){
		$this->parent = $parent;
	}
}