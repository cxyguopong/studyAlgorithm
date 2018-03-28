<?php

//选择排序


require_once('../func.php');

outputCss();

run();

function run(){
	$sequence = [31, 41, 59, 26, 41, 58];	
	$sort = 'desc';
	
	printf('<h2>选择排序</h2>');
	
	printf('<h4>Input:</h4><br><b>%s</b>', implode(',', $sequence));
	
	select_sort($sequence);
	
	printf('<h4>Output:</h4><br><b>%s</b>', implode(',', $sequence));
}

/**
 * 选择排序
 * @param array &$sequence 待排序数列
 */
function select_sort(&$seq){
	for ($i=0; $i<count($seq) - 1; $i++) {
		
		$min = $seq[$i];
		
		$j = $i;
		
		for ($k = $i+1; $k<count($seq); $k++) {
			if ($min > $seq[$k]) {
				$min = $seq[$k];
				$j = $k;
			}
		}
		
		if ($j !== $i) {
			swap_int($seq, $i, $j);
		}
		
	}
}