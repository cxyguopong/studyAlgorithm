<?php


//二分查找

require_once('../func.php');
require_once('../sort/Sorts.php');
require_once('BinSearch.php');

outputCss();

run();

function run(){
	$sequence = [31, 41, 59, 26, 41, 58, 55,1,4,13,19,20,23,21,22,22,22,17,111,121,145,3,8,22,109,22,224];
	//$sequence = [];
	
	$sequence = Sorts::quick($sequence);
		
	printf('<h2>二分查找</h2>');
	
	printf('<h4>Input:</h4>');
	
	printf('<div class="text-center">');
	test_out_list($sequence);
	
	printf('<h4>Output:</h4>');
	
	$val = [0, 1, 4, 22, 224, 9999, 8888];
	
	test_find_array($sequence, $val);
	
	printf('<hr>');
	
	printf('</div>');
	
}

function test_find_array($seq, $list){
	
	foreach ($list as $v) {
		test_find_val($seq, $v, 's');
		test_find_val($seq, $v, 'l');
		test_find_val($seq, $v, 'r');
		test_find_val($seq, $v, 'lt');
		test_find_val($seq, $v, 'gt');
		printf('<hr>');
	}
}

function test_find_val($seq, $val, $side = null){
	
	$methods = [
		's' => 'search',
		'l' => 'firstEq',
		'r' => 'lastEq',
		'lt' => 'lastLt',
		'gt' => 'firstGt'
	];
	
	$sideText = '';
	
	switch ($side) {
		case 'l':
		case 'left':
			$sideText .= '(寻找最小索引)';
			break;
		
		case 'r':
		case 'right':
			$sideText .= '(寻找最大索引)';
			break;
		
		case 'lt':
			$sideText .= sprintf('(最后一个小于%d的值)', $val);
			break;
		
		case 'gt':
			$sideText .= sprintf('(第一个大于%d的值)', $val);
	}
	
	printf('要查找的数%2$s：<b>%d</b><br>', $val, $sideText);
	
	$bs = new BinSearch($seq);
	
	$key = $bs->{$methods[$side]}($val);
	
	if ($key === -1) {
		printf('未找到自然数<br><br>');
	
	} else {
		printf('已找到数%d, 位于数组中第<b>%s</b>个数', $val, $key+1);
		
		test_out_list($seq, $key);
		
	}	
}

function test_out_list($seq, $actindex = null){
	printf('<div class="series">');
	for ($i=0; $i<count($seq); $i++) {
		printf('<div class="it %3$s"><span>%d</span><i>%d</i></div>', $seq[$i], $i+1, $actindex === $i ? 'act' : '');
	}
	
	printf('</div>');
}
