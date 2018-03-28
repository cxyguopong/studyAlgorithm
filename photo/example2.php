<?php

//地图最小距离探索
require_once('Simple.php');
require_once('../func.php');
require_once('../queue/Queue.php');

$pic = adjacency(createMatrix(5,5,999), [[0,1,2],[0,4,10],[1,2,3],[1,4,7],[2,0,4],[2,3,4],[3,4,5],[4,2,3]]);

outputCss();

printfMatrix();

testDfs();

testBfs();

function testDfs(){
	global $pic;
	
	printf('<h3>深度优先遍历（寻找最短路程）</h3>');
	echo "<div class='wrap-center'><div class=\"bd-num num-style \">";
	$shortest = 0;
	$shortestWay = [];
	dfsCore(0,4, $shortest, $shortestWay);
	printf('最短距离是：%d<br>', $shortest);
	
	for ($i=1; $i<count($shortestWay); $i++) {
		printf('%d -> %d：%d<br>', $shortestWay[$i-1], $shortestWay[$i], $pic[$shortestWay[$i-1]][$shortestWay[$i]]);
	}
	
	echo "</div></div>";
}

function testBfs(){
	global $pic;
	
	printf('<h3>广度优先搜索（转机次数最少）</h3>');
	echo "<div class=\"wrap-center\"><div class=\"bd-num num-style \">";
	$stack = bfsCore(0,4);
	
	$way = [];
	
	while ($stack !== null) {
		array_unshift($way, $stack->val);
		$stack = $stack->parent;
	}
	
	printf("%s", implode('->', $way));
	
	echo "</div></div>";
}

function dfsCore($cur, $dest, &$sum, &$best){
	global $pic;
	static $book = [];
	static $_sum =0;
	static $way = [];
	
	if (empty($book[$cur])) { //处理最初调用，防止（双向通道）造成死循环
		$book[$cur] = 1;	
		array_push($way, $cur);
	}
	
	if ($cur === $dest) {
		(empty($sum) or $sum > $_sum) and ($best = $way) and ($sum = $_sum);
		return;
	}
	
	for ($i=0; $i<count($pic); $i++) {
		$dis = $pic[$cur][$i];
		if ($dis > 0 && $dis != 999 && empty($book[$i])) {
			$book[$i] = 1;
			array_push($way, $i);
			$_sum += $dis;
			
			dfsCore($i, $dest, $sum, $best);
			$book[$i] = 0;
			array_pop($way);
			$_sum -= $dis;
		}
	}
	
	if (empty($_sum)) {
		$book = [];
		
	}
	return;
}

function bfsCore($cur, $dest){
	global $pic;
	$book = [];
	
	$queue = new Queue();
	$queue->push(new Simple($cur));
	$book[$cur] = 1;
	
	$destCity = null;
	
	while($queue->isEmpty() === false){
		$parent = $queue->get();
		
		$p = $parent->val;
		
		for ($i=0; $i<count($pic); $i++) {
			$dis = $pic[$p][$i];
			if ($dis >= 1 && $dis !=999 && empty($book[$i])) {
				$destCity = new Simple($i, $parent);
				$queue->push($destCity);
				$book[$i] = 1;
			}
			
			if ($i === $dest) {
				break 2;
			}
		}
	}

	return $destCity;
}


function adjacency($matrix, $points){
	foreach ($points as $p) {
		$matrix[$p[0]][$p[1]] = $p[2];
	}
	
	for ($i=0; $i<count($matrix); $i++){
		$matrix[$i][$i] = 0;
	}
	
	return $matrix;
}

function printfMatrix(){
	global $pic;
	
	$html = '<h2>地图最小距离 - 邻接矩阵有向图表示（）</h2>';
	$html .= '<div class="wrap-center tip">0-表示城市A到到城市A的距离为0；999-表示两个城市没有路直达；其他-表示两个城市可达且路程为n</div>';
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