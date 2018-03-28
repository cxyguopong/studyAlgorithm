<?php

//插入排序


require_once('../func.php');

outputCss();

run();

function run(){
	$sequence = [31, 41, 59, 26, 41, 58];	
	$sort = 'desc';
	
	printf('<h2>插入排序</h2>');
	
	printf('<h4>Input:</h4><br><b>%s</b>', implode(',', $sequence));
	
	insertion_sort($sequence, $sort);
	
	printf('<h4>Output:</h4><br><b>%s</b>', implode(',', $sequence));
}

/**
 * 插入排序
 * @param array &$sequence 待排序数列
 * @param string $sort asc-升序 desc-逆序
 */
function insertion_sort(&$sequence, $sort='asc'){
	for ($i=1; $i<count($sequence); $i++) {
		$cur = $sequence[$i];
		
		$j = $i - 1;
		
		while ($j > -1 && (($sort === 'asc' && $sequence[$j] > $cur) || ($sort === 'desc' && $sequence[$j] < $cur))) {
			$sequence[$j+1] = $sequence[$j];
			--$j;
		}
		
		$sequence[$j+1] = $cur;
	}
}