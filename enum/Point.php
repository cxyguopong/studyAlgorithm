<?php

/**
 * nodePoint object
 */
class Point {
	private $x;
	private $y;
	private $parent;
	
	public function __construct($x, $y, Point $parent = null){
		$this->x = $x;
		$this->y = $y;
		$this->parent = $parent;
	}
	
	public function __get($name){
		return $this->$name;
	}
}