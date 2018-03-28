<?php

require('Queue.php');

//解密号码
function getNumber($str){
	if (empty(strlen($str))) {
		throw new InvalidArgumentException();
	}
	
	$queue = new Queue();
	for ($i=0; $i<strlen($str); $i++) {
		$queue->push($str[$i]);
	}
	
	$ret = '';
	while ($queue->isEmpty() === false) {
		$ret .= $queue->get();
		$queue->isEmpty() or $queue->push($queue->get());
	}
	
	return $ret;
}

$number = getNumber('631758924');

print_r($number);