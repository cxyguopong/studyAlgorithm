<?php

//矩阵乘法的 Strassen算法
//矩阵能相乘的前提是: 两个矩阵必须是相容的(A的cols等于B的rows)

require_once('../func.php');
outputCss();

run();

function run(){
	$square1 = [
		[1,4,6,7],
		[7,5,2,1],
		[3,8,9,3],
		[2,4,1,9]
	];
	
	$square2 = [
		[4,10,9,3],
		[3,2,12,2],
		[4,8,13,5],
		[2,9,4,7 ]
		
	];
	
	/* $square1 = [
		[1,4],
		[7,5]
	];
	
	$square2 = [
		[4,10],
		[3,2]
	]; */
	
	printf('<h2>矩阵乘法的 Strassen算法</h2>');
	
	printf('<h4>Input:</h4>');
	
	output_square($square1);
	
	output_square($square2);
	
	printf('<div class="text-center">');
	
	printf('<h4>Output:</h4>');
	
	$ret = force_multiply($square1, $square2);
	
	printf('<h4>使用原始公式:</h4>');
	
	output_square($ret);
	
	printf('<h4>使用分治法</h4>');
	
	$ret = divide_multiply_pack($square1, $square2);
	
	output_square($ret);
	
	printf('</div>');
	
}

function output_square($s){
	$s = array_map(
		function($v){
			return sprintf('<div class="it">%s</div>', implode('', array_map(function($vv){ return sprintf('<em>%s</em>', $vv); }, $v)));
		},
		$s
	);
	printf('<div class="maze">%s</div>', implode('', $s));
}

function force_multiply($s1, $s2){
	$c1 = count($s1[0]);
	$r2 = count($s2);
	
	if ($c1 !== $r2) {
		throw new InvalidArgumentException('arg1-cols must equal arg2-rows');
	}
	
	$s3 = [];
	
	$s3_r = count($s1);
	$s3_c = count($s2[0]);
	
	for ($i=0; $i<$s3_r; $i++) {
		$s3[$i] = [];
		for ($j=0; $j<$s3_c; $j++) {
			$s3[$i][$j] = 0;
			for ($k=0; $k<$c1; $k++) {
				$s3[$i][$j] += $s1[$i][$k] * $s2[$k][$j];
			}
		}
	}
	
	return $s3;
}

function divide_multiply_pack($s1, $s2){
	$s1_r = count($s1);
	$s1_c = count($s1[0]);
	
	$s2_r = count($s2);
	$s2_c = count($s2[0]);
	
	$s3 = divide_multiply($s1, $s2, [0, $s1_r - 1], [0, $s1_c - 1], [0, $s2_r - 1], [0, $s2_c - 1]);
	
	
	return $s3;
}

/**
 * 分治法
 * @param array &$s1 first matrix
 * @param array &$s2 second matrix
 * @param array tupic [s1-rows-start, s1-rows-end]
 * @param array tupic [s1-cols-start, s1-cols-end]
 * @param array tupic [s2-rows-start, s2-rows-end]
 * @param array tupic [s2-cols-start, s2-cols-end]
 */
function divide_multiply($s1, $s2, $s1_r, $s1_c, $s2_r, $s2_c){
	
	$s1_r_len = $s1_r[1] - $s1_r[0] + 1;
	$s1_c_len = $s1_c[1] - $s1_c[0] + 1;
	$s2_r_len = $s2_r[1] - $s2_r[0] + 1;
	$s2_c_len = $s2_c[1] - $s2_c[0] + 1;
	
	if ($s1_r_len === 1 && $s1_c_len === 1 && $s2_r_len === 1 && $s2_c_len === 1) {
		return $s1[$s1_r[1]][$s1_c[1]] * $s2[$s2_r[1]][$s2_c[1]];
	}
	
	$s1_r_m = $s1_r[1] + $s1_r[0] >> 1;
	$s1_c_m = $s1_c[1] + $s1_c[0] >> 1;
	$s2_r_m = $s2_r[1] + $s2_r[0] >> 1;
	$s2_c_m = $s2_c[1] + $s2_c[0] >> 1;
	
	//s1 top rows range  
	$s1_r_l = [$s1_r[0], $s1_r_m];
	//s1 bottom rows range
	$s1_r_r = [$s1_r_m + 1, $s1_r[1]];
	//s1 left cols range
	$s1_c_l = [$s1_c[0], $s1_c_m];
	//s1 right cols range
	$s1_c_r = [$s1_c_m + 1, $s1_c[1]];
	
	//s1 top rows range  
	$s2_r_l = [$s2_r[0], $s2_r_m];
	//s1 bottom rows range
	$s2_r_r = [$s2_r_m + 1, $s2_r[1]];
	//s1 left cols range
	$s2_c_l = [$s2_c[0], $s2_c_m];
	//s1 right cols range
	$s2_c_r = [$s2_c_m + 1, $s2_c[1]];
	
	//$s3[11] = $s1[1][1] * $s2[1][1] + $s1[1][2] * $s2[2][1]
	$c11 = square_add(divide_multiply($s1, $s2, $s1_r_l, $s1_c_l, $s2_r_l, $s2_c_l), divide_multiply($s1, $s2, $s1_r_l, $s1_c_r, $s2_r_r, $s2_c_l));
	
	//$s3[12] = $s1[1][1] * $s2[1][2] + $s1[1][2] * $s2[2][2]
	$c12 = square_add(divide_multiply($s1, $s2, $s1_r_l, $s1_c_l, $s2_r_l, $s2_c_r), divide_multiply($s1, $s2, $s1_r_l, $s1_c_r, $s2_r_r, $s2_c_r));
		
	//$s3[21] = $s1[2][1] * $s2[1][1] + $s1[2][2] * $s2[2][1]
	$c21 = square_add(divide_multiply($s1, $s2, $s1_r_r, $s1_c_l, $s2_r_l, $s2_c_l), divide_multiply($s1, $s2, $s1_r_r, $s1_c_r, $s2_r_r, $s2_c_l));
		
	//$s3[22] = $s1[2][1] * $s2[1][2] + $s1[2][2] * $s2[2][2]
	$c22 = square_add(divide_multiply($s1, $s2, $s1_r_r, $s1_c_l, $s2_r_l, $s2_c_r), divide_multiply($s1, $s2, $s1_r_r, $s1_c_r, $s2_r_r, $s2_c_r));
	
	return comb_multiply($c11, $c12, $c21, $c22);
}

//直接从$s1 和 $s2中读取元素，省去创建s1'、s2'时间
function strassen_multiply(&$s1, &$s2, $s1_r, $s1_c, $s2_r, $s2_c){
	$s1_r_len = $s1_r[1] - $s1_r[0] + 1;
	$s1_c_len = $s1_c[1] - $s1_c[0] + 1;
	$s2_r_len = $s2_r[1] - $s2_r[0] + 1;
	$s2_c_len = $s2_c[1] - $s2_c[0] + 1;
	
	if ($s1_r_len === 1 && $s1_c_len === 1 && $s2_r_len === 1 && $s2_c_len === 1) {
		return $s1[$s1_r[1]][$s1_c[1]] * $s2[$s2_r[1]][$s2_c[1]];
	}
	
	$s1_r_m = $s1_r[1] + $s1_r[0] >> 1;
	$s1_c_m = $s1_c[1] + $s1_c[0] >> 1;
	$s2_r_m = $s2_r[1] + $s2_r[0] >> 1;
	$s2_c_m = $s2_c[1] + $s2_c[0] >> 1;
	
	//s1 top rows range  
	$s1_r_l = [$s1_r[0], $s1_r_m];
	//s1 bottom rows range
	$s1_r_r = [$s1_r_m + 1, $s1_r[1]];
	//s1 left cols range
	$s1_c_l = [$s1_c[0], $s1_c_m];
	//s1 right cols range
	$s1_c_r = [$s1_c_m + 1, $s1_c[1]];
	
	//s1 top rows range  
	$s2_r_l = [$s2_r[0], $s2_r_m];
	//s1 bottom rows range
	$s2_r_r = [$s2_r_m + 1, $s2_r[1]];
	//s1 left cols range
	$s2_c_l = [$s2_c[0], $s2_c_m];
	//s1 right cols range
	$s2_c_r = [$s2_c_m + 1, $s2_c[1]];
	
	//m1 = B12 - B22
	$m1 = square_sub($s2, $s2, $s2_r_l, $s2_c_r, $s2_r_r, $s2_c_r);
	
	//m2 = A11 + A12
	$m2 = square_add_core($s1, $s1, $s1_r_l, $s1_c_l, $s1_r_l, $s1_c_r);
	
	//m3 = A21 + A22
	$m3 = square_add_core($s1, $s1, $s1_r_r, $s1_c_l, $s1_r_r, $s1_c_r);
	
	//m4 = B21 - B11
	$m4 = square_sub($s2, $s2, $s2_r_r, $s2_c_l, $s2_r_l, $s2_c_l);
	
	//m5 = A11 + A22
	$m5 = square_add_core($s1, $s1, $s1_r_l, $s1_c_l, $s1_r_r, $s2_c_r);
	
	//$s3[11] = $s1[1][1] * $s2[1][1] + $s1[1][2] * $s2[2][1]
	$c11 = square_add(divide_multiply($s1, $s2, $s1_r_l, $s1_c_l, $s2_r_l, $s2_c_l), divide_multiply($s1, $s2, $s1_r_l, $s1_c_r, $s2_r_r, $s2_c_l));
	
	//$s3[12] = $s1[1][1] * $s2[1][2] + $s1[1][2] * $s2[2][2]
	$c12 = square_add(divide_multiply($s1, $s2, $s1_r_l, $s1_c_l, $s2_r_l, $s2_c_r), divide_multiply($s1, $s2, $s1_r_l, $s1_c_r, $s2_r_r, $s2_c_r));
		
	//$s3[21] = $s1[2][1] * $s2[1][1] + $s1[2][2] * $s2[2][1]
	$c21 = square_add(divide_multiply($s1, $s2, $s1_r_r, $s1_c_l, $s2_r_l, $s2_c_l), divide_multiply($s1, $s2, $s1_r_r, $s1_c_r, $s2_r_r, $s2_c_l));
		
	//$s3[22] = $s1[2][1] * $s2[1][2] + $s1[2][2] * $s2[2][2]
	$c22 = square_add(divide_multiply($s1, $s2, $s1_r_r, $s1_c_l, $s2_r_l, $s2_c_r), divide_multiply($s1, $s2, $s1_r_r, $s1_c_r, $s2_r_r, $s2_c_r));
}

//两个矩阵相加
function square_add($s1, $s2){
	if (gettype($s1) === 'integer') {
		return $s1 + $s2;
	}
	
	$r = [];
	
	for ($i=0; $i<count($s1); $i++) {
		for ($j=0; $j<count($s1[0]); $j++) {
			$r[$i][$j] = $s1[$i][$j] + $s2[$i][$j];
		}
	}
	
	return $r;
}

function square_add_add($s1, $s2, $s1_r, $s1_c, $s2_r, $s2_c){
	if (gettype($s1) === 'integer') {
		return $s1 + $s2;
	}
	
	$r = [];
	
	for ($i=0; $i < $s1_r[1] - $s1_r[0] + 1; $i++) {
		for ($j=0; $j < $s1_c[1] - $s1_c[0] + 1; $j++) {
			$r[$i][$j] = $s1[$s1_r[0] + $i][$s1_c[0] + $j] + $s2[$s2_r[0] + $i][$s2_c[0] + $j];
		}
	}
	
	return $r;
}


//两个矩阵相减
function square_sub($s1, $s2, $s1_r, $s1_c, $s2_r, $s2_c){
	if (gettype($s1) === 'integer') {
		return $s1 - $s2;
	}
	
	$r = [];
	
	for ($i=0; $i < $s1_r[1] - $s1_r[0] + 1; $i++) {
		for ($j=0; $j < $s1_c[1] - $s1_c[0] + 1; $j++) {
			$r[$i][$j] = $s1[$s1_r[0] + $i][$s1_c[0] + $j] - $s2[$s2_r[0] + $i][$s2_c[0] + $j];
		}
	}
	
	return $r;
}


//合并子数组
//c11
//c12
//c21
//c22
function comb_multiply($s1, $s2, $s3, $s4){
	if (gettype($s1) === 'integer') {
		return [[$s1, $s2],[$s3, $s4]];
	}
	
	$c = [];
	
	$h = count($s1) + count($s3);
	$w = count($s1[0]) + count($s2[0]);
	
	for ($i=0; $i<$h; $i++) {
		for ($j=0; $j<$w; $j++) {
			//s1
			if ($i < count($s1) && $j < count($s1[0])) {
				$c[$i][$j] = $s1[$i][$j];
			
			//s2
			} elseif ($i < count($s1) && $j >= count($s1[0])) {
				$c[$i][$j] = $s2[$i][$j - count($s1[0])];
			
			//s3
			} elseif ($i >= count($s1) && $j < count($s1[0])) {
				$c[$i][$j] = $s3[$i - count($s1)][$j];
			
			//s4
			} else {
				$c[$i][$j] = $s4[$i - count($s1)][$j - count($s1[0])];
			}
		}
	}
	
	return $c;
}