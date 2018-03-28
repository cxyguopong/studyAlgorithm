<?php

require('Stack.php');
require('../queue/Queue.php');

function whoWin($one, $two){
	$q1 = new Queue($one); 
	$q2 = new Queue($two);
	
	$desk = new Stack();
	$mark = 0;
	
	$win = function($bh, &$queue) use (&$mark, &$desk) {
		while (true) {
			$cur = $desk->get();
			
			$queue->push($cur);
			if ($cur === $bh) {
				break;
			}
			
			$mark ^= (2 << $cur);
		}
	};
	
	//1 为 $one
	$i = 1;
	while (!$q1->isEmpty() && !$q2->isEmpty()) {
		for (; $i<=2; $i++) {
			$q = 'q'.$i;
			$q = $$q;
			
			$cur = $q->get();
			
			if ((2 << $cur) & $mark) {
				$q->push($cur);
				$win($cur, $q);
			} else {
				$desk->push($cur);
				$mark ^= (2 << $cur);
			}
			
			if ($q->isEmpty()) break 2;
		}
	}
	
	return $i == 1 ? -1 : 1;
}

function test($one, $two){
	printf('[%s]<br>', implode(',', $one));
	printf('[%s]<br>', implode(',', $two));
	printf('win:%d<br>', whoWin($one, $two) == 1 ? 1 : 2);
}

$one = [2,4,1,2,5,6];
$two = [3,1,3,5,6,4];
test($one, $two);
