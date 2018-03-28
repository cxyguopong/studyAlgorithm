<?php


//逆序对
//使用归并排序的思想来计算任意数列的逆序对

require_once('../func.php');
outputCss();

run();

function run(){
	$sequence = [2,3,8,6,1];
	//$sequence = [8,6,3,2,1];
	//$sequence = [];

	printf('<h2>计算逆序对</h2>');
	
	printf('<h4>Input:</h4>');
	
	printf('<b>%s</b><br>', implode(',', $sequence));
	
	printf('<div class="text-center">');
	
	printf('<h4>Output:</h4>');

	$count = reverse_order_pair($sequence);
	//$count = reverse_order_pair($sequence);
	printf('共<b>%d</b>对<br>', $count);
	
	printf('<h4>同时对数列进行了排序</h4>%s', implode(',', $sequence));
	
	printf('</div>');
	
}

function reverse_order_pair(&$seq){
	$count = merge($seq, 0, count($seq) - 1);
	return $count;
}

function merge(&$seq, $i, $j, &$trace = null){
	static $count = 0;
	
	$len = $j - $i + 1;
	
	if ($len === 1) {
		return;
	}
	
	$mid = $j + $i >> 1;
	
	merge($seq, $i, $mid, $trace);
	merge($seq, $mid+1, $j, $trace);
	
	$count += statistic($seq, $i, $mid, $j);
	
	//重置静态变量
	if ($i === 0 && $j ===count($seq) - 1) {
		$tmp = $count;
		$count = 0;
		return $tmp;
	}
	
	return;
}

function statistic(&$seq, $l, $mid, $r, &$trace=null){
	$inf = 99999999;
	
	$count = 0;
	
	$ll = $mid - $l + 1;
	$lr = $r - $mid;
	
	$al = [];
	for ($i=0; $i < $ll; $i++) {
		$al[$i] = $seq[$l + $i];
	}
	
	$al[] = $inf;
	
	$ar = [];
	for ($i=0; $i < $lr; $i++) {
		$ar[$i] = $seq[$mid + 1 + $i];
	}
	
	$ar[] = $inf;
	
	$m =0;
	$n =0;
	
	for ($i=$l; $i<= $r; $i++) {
		if ($al[$m] <= $ar[$n]) {
			$seq[$i] = $al[$m];
			++$m;
			
		} else {
			$seq[$i] = $ar[$n];
			++$n;
			//$al总数减去已使用的数
			$count += $ll - $m;
		}
	}

	return $count;
}


