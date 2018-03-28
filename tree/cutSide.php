<?php

require_once('../func.php');
require_once('../structure/AdjacencyList.php');

outputCss();

//节点数，请根据边调整
$pointNum = 6;
	
$edges = [[1,4], [1,3], [1,7], [4,2], [3,2], [2,5], [5,6]];

//无向图
$undirect = true;

$adjlist = new AdjacencyList($edges, $undirect);

run();

function run(){
	global $edges, $pointNum;
	
	printf("<h2>无向图求割边</h2>");
	
	echo('<div class="wrap-center">');
	
	printf('<h4>共%d条边:</h4>', count($edges));
	
	for ($i=0; $i<count($edges); $i++) {
		call_user_func_array('printf', array_merge(['<b>%d - %d</b><br>'], $edges[$i]));
	}
	
	//生成树的根节点选择(这里怎么选都可以)
	$root = 1;
	
	//从根节点开始 dfs
	$cuts = dfs($root, $root, $root);
	
	printf("<h4>割边有<b>%d</b>条:</h4><br>", count($cuts));
	for ($i=0; $i<count($cuts); $i++) {
		printf('<b>%d - %d</b><br>', $cuts[$i][0], $cuts[$i][1]);
	}
	
	echo('</div>');
}

function dfs($cur, $parent, $root, $index = 1){
	global $pointNum, $adjlist;
	
	static $num = [], $min = [], $cut = [];
	
	//这里只考虑边,所以不需要考虑根节点,所以不需要计算子点数量
	//$childN;
	
	$num[$cur] = $index;
	$min[$cur] = $index;
	
	$adjlist->loop($cur, function($u, $v) use (&$num, &$min, &$cut, $index, $root, $parent){
		
		if (empty($num[$v])) {
			dfs($v, $u, $root, $index+1);
			$min[$u] = min($min[$u], $min[$v]);
			
			if ($min[$v] > $num[$u]) {
				array_push($cut, [$u, $v]);
			}
			
		} elseif ($v !== $parent) {
			$min[$u] = min($min[$u], $num[$v]);
		}
		
	});
	
	if ($cur === $root) {
		$temp = $cut;
		$num = []; $min = []; $cut = [];
		return $temp;
	}
	
	return;
	
}
