<?php

require_once("MatrixData.php");
require_once("../queue/Queue.php");

outputCss();

$edge = edgeIncludeNegative();

//顶点数
$pointNumber = 5;

//无限值
$infinity = 99999999;

run();

function run(){
	global $edge, $pointNumber;
	
	echo("<h2>bellman-ford算法，计算单源点最短距离</h2>");
	
	echo("<div class=\"wrap-center\">");
	
	echo('<div class="tip">可完美处理负权边问题</div>');
	
	echo('');
	
	printf("<h4>共<b>%d</b>条边，<b>%d</b>个顶点（序号从<b>0</b>开始）</h4>", count($edge), $pointNumber);
	
	for ($i=0;$i<count($edge);$i++) {
		call_user_func_array('printf', array_merge(['<b>%d</b>-><b>%d</b>:<b>%d</b><br>'], $edge[$i]));
	}
	//$point
	echo("</div>");
	
	$source = 0;
	$dist = bellman_ford($source);
	
	echo('<div class="wrap-center">');
	
	echo("<h4>使用bellman-ford算法</h4>");
	
	for ($i=0; $i<count($dist); $i++) {
		printf('源点<b>%d</b>距离<b>%d</b>最短为：<b>%d</b><br>', $source, $i, $dist[$i]);
	}
	echo "</div>";
	
	echo('<div class="wrap-center">');
	
	echo('<h4>使用dijkstra算法</h4>');
	
	echo('<div class="tip tip-error">当非源点所属的边为负值时，则dijkstra可能计算不出最短边！<small>因为当考虑这条边时，对应的计算节点可能已经移到dist数组中了</small></div>');
	
	echo <<<EOT
	'<div class="tip tip-error">这个例子的错误是：0->1都缩短到了-2，其实按照dijkstra只能处理正权边的原因，这里0->1的值为-1就不应该再计算1这个点了（因为它已经属于已知最短路的集合了）。
	<br>
	不过这不重要，而1这个节点又被缩短了，但是却不能再以他为起点进行松弛操作，所以0->3的最短值也只是-4，而最短值应该是bellman-ford的-5（即0->2->1->3）</div>
EOT;

	$dist = dijkstra($source);

	for ($i=0; $i<count($dist); $i++) {
		printf('源点<b>%d</b>距离<b>%d</b>最短为：<b>%d</b><br>', $source, $i, $dist[$i]);
	}
	
	echo "</div>";
	
	
	echo('<div class="wrap-center">');
	
	echo('<h4>使用Bellman-Ford的队列优化</h4>');
	
	$dist = bellman_ford_queue($source);
	for ($i=0; $i<count($dist); $i++) {
		printf('源点<b>%d</b>距离<b>%d</b>最短为：<b>%d</b><br>', $source, $i, $dist[$i]);
	}
	echo "</div>";
}


function bellman_ford($source){
	global $edge, $pointNumber, $infinity;
	
	$u = [];
	$v = [];
	$w = [];
	
	$dist = [];
	
	$eLen = count($edge);
	
	for ($i=0; $i<$eLen; $i++) {
		list($u[$i], $v[$i], $w[$i]) = $edge[$i];
	}
	
	for ($i=0; $i<$pointNumber; $i++) {
		$dist[$i] = $infinity;
	}
	
	//源点距离源点边长设为0
	$dist[$source] = 0;
	
	$flag = 0;
	
	//源点到任一其他点的最短路径最多经过n-1条边,所以这里只需要循环n-1次
	for ($i=0; $i<$pointNumber-1; $i++) {
		$flag = 0;
		
		for ($j=0; $j<$eLen; $j++) {
			if ($dist[$u[$j]] !== $infinity && $dist[$v[$j]] > $dist[$u[$j]] + $w[$j]) {
				$dist[$v[$j]] = $dist[$u[$j]] + $w[$j];
				++$flag;
			}
		}
		
		if ($flag === 0) {
			break;
		}
		
	}
	
	$flag = 0;
	
	for ($i=0; $i<$eLen; $i++) {
		if ($dist[$u[$i]] + $w[$i] < $dist[$v[$i]]) {
			throw new InvalidArgumentException(sprintf('顶点%d %d之间的边导致了负权回路!', $u[$i], $v[$i]));
		}
	}
	
	return $dist;
}

function dijkstra($source){
	global $edge, $pointNumber, $infinity;
	
	$matrix = adjacency(createMatrix($pointNumber,$pointNumber,$infinity), $edge, false);
	
	//$from = [];
	$dist = [];
	$book = [];
	
	for ($i=0; $i<$pointNumber; $i++) {
		$dist[] = $matrix[$source][$i];
		//$dist[] = 0;
	}
	
	$book[$source] = 1;
	$dist[$source] = 0;
	
	while (count($book) < $pointNumber) {
		$min = $infinity;
		$minIdx = -1;
		
		foreach ($dist as $index => $v) {
			if (empty($book[$index])) {
				if ($minIdx === -1 || $v < $min) {
					$min = $v;
					$minIdx = $index;
				}
			}
		}
		
		$dist[$minIdx] = $min;
		$book[$minIdx] = 1;
		
		for ($i=0; $i<$pointNumber; $i++) {
			//其实这里不加empty($book[$i]),在$matrix[$minIdx][$i]为负数时,可以进一步缩短$dist[$i]
			//但是这没有意义,因为$i已经在$book中了,则他
			//
			//$matrix[$minIdx][$i] < $infinity,防止负权边时,出现99999995之类的数据
			//但是不应该用dijkstra处理有负权边的最短距离,所以这里也不需要
			if (/*empty($book[$i]) && $matrix[$minIdx][$i] !== $infinity &&*/ $dist[$i] > $min + $matrix[$minIdx][$i]) {
				$dist[$i] = $min + $matrix[$minIdx][$i];
			}
		}
		
	}
	
	return $dist;
}

function bellman_ford_queue($source){
	global $edge, $pointNumber, $infinity;
	
	$dist = [];
	
	//queue
	$queue = new Queue();
	
	//记录节点是否在队列中
	$book = [];
	
	//adjacency table
	$u=[];
	$v=[];
	$w=[];
	$first=[];
	$next=[];
	
	for ($i=0; $i<$pointNumber; $i++) {
		$first[] = -1;
		$dist[] = $infinity;
	}
	
	for ($i=0; $i<count($edge); $i++) {
		list($u[$i], $v[$i], $w[$i]) = $edge[$i];
		$next[$i] = $first[$u[$i]];
		$first[$u[$i]] = $i;
	}
	
	$dist[$source] = 0;
	$book[$source] = 1;
	
	$queue->push($source);
	
	while ($queue->isEmpty() === false) {
		$p = $queue->get();
		$book[$p] = 0;
		
		$e = $first[$p];
		
		while ($e !== -1) { 
			if ($dist[$v[$e]] > $dist[$u[$e]] + $w[$e]) {
				$dist[$v[$e]] = $dist[$u[$e]] + $w[$e];
				if (empty($book[$v[$e]])) {
					$book[$v[$e]] = 1;
					$queue->push($v[$e]);
				}
			}
			$e = $next[$e];
		}
	}
	
	return $dist; 
	
}
