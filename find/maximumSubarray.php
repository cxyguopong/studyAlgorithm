<?php


//最大子数组
//使用分治思想来计算最大了数组

require_once('../func.php');
outputCss();

run();

function run(){
	$sequence = [13,-3,-25,20,-3,-16,-23,18,20,-7,12,-5,-22,15,-4,7];
	//$sequence = [8,6,3,2,1];
	//$sequence = [];

	printf('<h2>计算最大子数组</h2>');
	
	printf('<h4>Input:</h4>');
	
	printf('<b>%s</b><br>', implode(',', $sequence));
	
	printf('<div class="text-center">');
	
	printf('<h4>Output:</h4>');

	$ret = find_maximum_subarray($sequence);
	
	call_user_func_array('printf', array_merge(['从索引<b>%d</b>到<b>%d</b>，Sum:<b>%d</b><br>'], $ret));
	
	printf('<h4>子数组为：</h4>');
	
	printf(implode(',', call_user_func_array('array_slice', [$sequence, $ret[0], $ret[1] - $ret[0] + 1])));
	
	
	printf('<h4>暴力算法</h4>');
	
	$ret = force_find($sequence);
	
	call_user_func_array('printf', array_merge(['从索引<b>%d</b>到<b>%d</b>，Sum:<b>%d</b><br>'], $ret));
	
	printf('</div>');
	
}

function find_maximum_subarray($seq){
	$tuple = find_maximum_subarray_core($seq, 0, count($seq) - 1);	
	return $tuple;
}


function find_maximum_subarray_core($seq, $i, $j){
	$len = $j - $i + 1;
	
	if ($len === 1) {
		return [$i, $j, $seq[$i]];
	}
	
	$mid = $j + $i >> 1;
	
	//$i <= $start <= $end <= $mid
	$l = find_maximum_subarray_core($seq, $i, $mid);
	
	//$mid < $start <= $end <= $j
	$r = find_maximum_subarray_core($seq, $mid+1, $j);
	
	//$i <= $start <= $mid < $end <= $j
	$m = find_max_crossing_subarray($seq, $i, $mid, $j);
	
	if ($l[2] >= $r[2] && $l[2] >= $m[2]) {
		return $l;
	
	} elseif ($r[2] >= $l[2] && $r[2] >= $m[2]) {
		return $r;
	
	} else {
		return $m;
	}
	
}

//最大子数组横跨左右两边时
function find_max_crossing_subarray($seq, $i, $mid, $j){
	$m;
	$n;
	
	$l_s = -PHP_INT_MAX;
	$s = 0;
	
	//$seq 索引 [$mid..$i]中的最大值
	for ($k=$mid; $k>=$i; $k--) {
		$s += $seq[$k];
		if ($l_s < $s) {
			$l_s = $s;
			$m = $k;
		}
	}
	
	$r_s = -PHP_INT_MAX;
	$s=0;
	
	//$seq 索引 ($mid..$j]中的最大值
	for ($k=$mid+1; $k<=$j; $k++) {
		$s += $seq[$k];
		if ($r_s < $s) {
			$r_s = $s;
			$n = $k;
		}
	}
	
	return [$m, $n, $l_s + $r_s];
}

function force_find($seq){
	$l; 
	$r; 
	$max = -PHP_INT_MAX;
	
	$sum  = 0;
	
	for ($i=0; $i<count($seq); $i++) {
		$sum = 0;
		
		for ($j=$i; $j<count($seq); $j++) {
			$sum += $seq[$j];
			
			if ($max < $sum) {
				$l = $i;
				$r = $j;
				$max = $sum;
			}
		}
	}
	
	return [$l, $r, $max];
}
