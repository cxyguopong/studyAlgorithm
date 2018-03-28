<?php

/**
 * Queue structure
 * 只是数组的简单实现，没有清除过期数据
 * 鉴于php数组基于链表实现，这里可以清除过期索引数据
 */
class Queue {
	
	private $data = [];
	
	public $head = 0;
	public $tail = 0;
	
	function __construct($list = null){
		is_null($list) or $this->data = is_array($list) ? $list : [$list]; 
	}
	
	function push($value){
		array_push($this->data, $value);
		++$this->tail;
		return $this;
	}
	
	function get(){
		if ($this->isEmpty()) {
			return null;
		}
		
		return $this->data[$this->head++];
	}
	
	function isEmpty(){
		return $this->head === $this->tail;
	}
}