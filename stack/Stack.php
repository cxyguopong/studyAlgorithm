<?php

class Stack {
	private $datas = [];
	
	function __construct($datas = null){
		$this->datas = is_array($datas) ? $datas: (is_null($datas) ? [] : [$datas]);
	}
	
	public function push($value){
		array_push($this->datas, $value);
		return $this;
	}
	
	public function get(){
		return array_pop($this->datas);
	}
	
	public function clear(){
		$this->datas = [];
	}
	
	public function getLen(){
		return count($this->datas);
	}
	
	public function isEmpty(){
		return empty($this->datas);
	}
}