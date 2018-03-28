<?php

require('Stack.php');

function isPlalindrome($text){
	$len = strlen($text);
	$mid = intdiv($len, 2);
	$odd = $len & 1;
	
	$stack = new Stack;
	for ($i=0; $i<=$mid - 1; $i++) {
		$stack->push($text[$i]);
	}
	
	$j = $mid + ($odd ? 1 : 0);
	for (; $j<$len; $j++) {
		if ($text[$j] != $stack->get()) { 
			return false;
		}
	}
	return true;
}

function test($strArr){
	foreach ($strArr as $v) {
		printf("text:[%s], isPlalindrome:%d<br>", $v, isPlalindrome($v));
	}
}

$list = [
	//'([{()}])',
	'abcddcba',
	'xyzyx',
	'aha',
	'ahha',
	'ahah',
	//'([{}()])',
	
];

test($list);