<?php

require_once('Heap.php');

class MinHeap extends Heap {
	function __construct(Array $array){
		parent::__construct($array, Heap::HEAP_MIN);
	}
}