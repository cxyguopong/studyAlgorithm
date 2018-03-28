<?php

//图的深度与广度搜索（使用邻接矩阵表示）

require_once('../func.php');
require_once('../queue/Queue.php');

$pic = adjacency(createMatrix(5,5,999), [[0,1],[0,2],[0,4],[1,3],[2,4]]);

outputCss();

printfMatrix();

testDfs();

testBfs();

function testDfs(){
	printf('<h3>深度优先遍历</h3>');
	echo "<div class='wrap-center'><div class=\"bd-num num-style \">";
	dfsCore(0);
	echo "</div></div>";
}

function testBfs(){
	printf('<h3>广度优先搜索</h3>');
	echo "<div class=\"wrap-center\"><div class=\"bd-num num-style \">";
	bfsCore(0);
	echo "</div></div>";
}

function dfsCore($cur){
	global $pic;
	static $book = [];
	
	printf('<em>%d</em>', $cur);
	
	$book[$cur] = 1;
	for ($i=$cur+1; $i<count($pic); $i++) {
		if ($pic[$cur][$i] == 1 && empty($book[$i])) {
			dfsCore($i);
		}
	}
	return;
}

function bfsCore($cur){
	global $pic;
	$book = [];
	
	$queue = new Queue();
	$queue->push($cur);
	$book[$cur] = 1;
	
	while($queue->isEmpty() === false){
		$p = $queue->get();
		printf('<em>%d</em>', $p);
		for ($i=0; $i<count($pic); $i++) {
			if ($pic[$p][$i] === 1 && empty($book[$i])) {
				$queue->push($i);
				$book[$i] = 1;
			}
		}
	}
}

function adjacency($matrix, $points){
	foreach ($points as $p) {
		$matrix[$p[0]][$p[1]] = 1;
		$matrix[$p[1]][$p[0]] = 1;
	}
	
	for ($i=0; $i<count($matrix); $i++){
		$matrix[$i][$i] = 0;
	}
	
	return $matrix;
}

function printfMatrix(){
	global $pic;
	
	$html = '<h2>邻接矩阵(无向图)</h2>';
	$html .= '<div class="maze big">';
	for ($i=0; $i<count($pic); $i++) {
		$html .= "<div class='it'>";
		for ($j=0; $j<count($pic[$i]); $j++) {
			$cls = $pic[$i][$j] == 1 ? 'active':'';
			$html .="<em class=\"$cls\">".$pic[$i][$j]."</em>";
		}
		$html .= "</div>";
	}
	$html .= "</div>";

	echo $html;
}