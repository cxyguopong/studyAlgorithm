<?php

require_once('Heap.php');


class MaxHeap extends Heap {
	function __construct(Array $array){
		parent::__construct($array, Heap::HEAP_MAX);
	}
}