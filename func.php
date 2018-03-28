<?php

//生成矩阵
function createMatrix(int $rows, int $cols = null, $val = 0){
	if ($rows <= 0) {
		throw new InvalidArgumentException('rows must gt 0!');
	}
	
	empty($cols) and $cols = $rows;
	
	$res = [];
	
	for ($i=0; $i<$rows; $i++)
		for ($j=0; $j<$cols; $j++)
			$res[$i][$j] = $val;
	
	return $res;
}

/**
 * 邻接矩阵赋值
 * @param array $matrix 矩阵
 *
 * @param array $points 要赋值的节点，如：[1,1,13],[2,3,5]…
 * 节点意义[m,n,o] 其中m,n表示坐标，o表示值，默认为1
 * 
 * @param boolean $undirected 是否是无向图 [default:true]
 * 
 * @return array 赋值后的矩阵
 */
function adjacency($matrix, $points, $undirected = true){
	foreach ($points as $p) {
		$matrix[$p[0]][$p[1]] = !empty($p[2]) ? $p[2] : 1;
		!!$undirected and $matrix[$p[1]][$p[0]] = 1;
	}
	
	for ($i=0; $i<count($matrix); $i++){
		$matrix[$i][$i] = 0;
	}
	
	return $matrix;
}

/**
 * 交换数组中的两个元素
 * @param array &$seq 目标数组
 * @param int $i 第一个元素索引
 * @param int $j 第二个元素索引 
 */
function swap(&$seq, $i, $j){
	$tmp = $seq[$i];
	$seq[$i] = $seq[$j];
	$seq[$j] = $tmp;
}

/**
 * 交换数组中的两个元素(适合元素值为数字的情况)
 * @param array &$seq 目标数组
 * @param int $i 第一个元素索引
 * @param int $j 第二个元素索引
 */
function swap_int(&$seq, $i, $j){
	$seq[$i] = $seq[$i] ^ $seq[$j];
	$seq[$j] = $seq[$i] ^ $seq[$j];
	$seq[$i] = $seq[$i] ^ $seq[$j];
}

function outputCss(){
	echo "<link href=\"../css/style.css\" rel='stylesheet'>";
}
