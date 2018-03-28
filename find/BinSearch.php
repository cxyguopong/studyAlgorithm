<?php

//二分查找
// 参考 http://www.cnblogs.com/luoxn28/p/5767571.html
// -----------------------------
// ** 必做是排序好的数列
// -----------------------------
// -- 1、求指定数在数组中的索引
// -- 2、求指定数在数组中的最小(最大)索引，当数组中的元素重复时使用
// -- 3、求数组中小于指定数的最后一个数
// -- 4、求数组中大于指定数的第一个数
class BinSearch {
	private $len = 0;
	private $arr = [];
	
	function __construct($arr){
		$this->arr = array_values($arr);
		
		if (empty($this->arr)) {
			throw new InvalidArgumentException('array is empty!');
		}
		
		$this->len = count($arr);
	}
	
	public function search($val){
		$l = 0;
		$r = $this->len - 1;
		
		while ($l <= $r) {
			$mid = ($l + $r) >> 1;
			
			if ($this->arr[$mid] === $val) {
				return $mid;
			
			} elseif ($this->arr[$mid] < $val) {
				$l = $mid + 1;
			} else {
				$r = $mid - 1;
			}
		}
		
		return -1;
	}
	
	public function firstEq($val){
		$l = 0;
		$r = $this->len -1;
		
		while ($l <= $r) {
			$mid = ($r + $l) >> 1;
			if ($this->arr[$mid] >= $val) {
				$r = $mid - 1;
			} else {
				$l = $mid + 1;
			}
		}
		
		if ($l < $this->len && $this->arr[$l] === $val) {
			return $l;	
		}
		
		return -1;
	}
	
	public function lastEq($val){
		$l = 0;
		$r = $this->len - 1;
		
		while ($l <= $r) {
			$mid = ($l + $r) >> 1;
			if ($this->arr[$mid] > $val) {
				$r = $mid - 1;
			} else {
				$l = $mid + 1;
			}
		}
		
		if ($r >= 0 && $this->arr[$r] === $val) {
			return $r;
		}
		
		return -1;
	}
	
	public function lastEqOrLt($val){
		$l = 0;
		$r = $this->len - 1;
		
		while ($l <= $r) {
			$mid = $r + $l >> 1;
			if ($this->arr[$mid] >$val) {
				$r = $mid - 1;
			} else {
				$l = $mid + 1;
			}
		}
		
		return $r;
	}
	
	public function lastLt($val){
		$l = 0; 
		$r = $this->len - 1;
		
		while ($l <= $r) {
			$mid = $r + $l >> 1;
			if ($this->arr[$mid] >= $val) {
				$r = $mid - 1;
			} else {
				$l = $mid + 1;
			}
		}
		
		// -1也是我们需要的返回值之一 所以这里不需要判断
		
		return $r;
	}
	
	public function firstEqOrGt($val){
		$l = 0;
		$r = $this->len - 1;
		
		while ($l <= $r) {
			$mid = $r + $l >> 1;
			if ($this->arr[$mid] >= $val) {
				$r = $mid - 1;
			} else {
				$l = $mid + 1;
			}
		}
		
		if ($l === $this->len) {
			return -1;
		}
		
		return $l;
	}
	
	public function firstGt($val){
		$l = 0;
		$r = $this->len - 1;
		
		while ($l <= $r) {
			$mid = $r + $l >> 1;
			if ($this->arr[$mid] > $val) {
				$r = $mid - 1;
			} else {
				$l = $mid + 1;
			}
		}
		
		if ($l === $this->len) {
			return -1;
		}
		
		return $l;
	}
	
}