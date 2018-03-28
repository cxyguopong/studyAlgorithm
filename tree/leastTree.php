<?php


require_once('../func.php');
require_once('../structure/AdjacencyList.php');

outputCss();

run();

function run(){
	$nodes = 6;
	$paths = [[2,4,11], [3,5,13], [4,6,3], [5,6,4], [2,3,6], [4,5,7], [1,2,1], [3,4,9], [1,3,2]];
	
	printf("<h2>图的最小生成树</h2>");
	
	echo('<div class="wrap-center">');
	
	echo <<<EOT
	<div class="tip">
	<pre>
	* Kruskal算法原理：
	*		1、首先将边按权值从小到到排序(这里使用快排)
	*		2、初始化并查集数组。
	*		3、然后遍历排好序的边，如果边的两个顶点不在同一棵树（拥有相同的根节点），则该边为有效边
	*		4、步骤3遍历一直到n-1条有效边。
	*		5、如果最后没有n-1条有效边，则不存在最小生成树，应该抛出异常
	</pre>
	</div>
EOT;
	
	printf("<div class=\"tip\">共<b>%d</b>个节点(从<b>1</b>开始)</div>", $nodes);
	
	printf('<h4>边如下(共%d条边)</h4>', count($paths));
	for ($i=0; $i<count($paths); $i++) {
		printf("<b>%d - %d</b><br>", $paths[$i][0], $paths[$i][1]);
	}
	
	//printf("<h4>并查集(不相交集)</h4>");
	
	$ve = least_tree($nodes, $paths);
	
	printf('<h4>需要用到的边:<br></h4>');
	
	$sum = 0;
	for ($i=0; $i<count($ve); $i++) {
		$e = $paths[$ve[$i]];
		$sum += $e[2];
		printf('<b>%d - %d</b><br>', $e[0], $e[1]);
	}
	
	printf('总长度：<b>%d</b>', $sum);
	
	
	echo('<br><br><h4>使用Prim算法计算</h4>');
	
	echo <<<'EOT'
	<div class="tip">
	<pre>
	* Prim算法：
	* -- 思路:
	* ---- 就是每次选择离生成树最近的边通过(n-1)步创建最小生成树。和dijkstra算法很类似.
	* -- 步骤:
	* ---- 1.选取任意一个点,然后将该点到所有其他顶点的直接距离保存到dis数组(没有直接距离则用无限值表示)
	* ---- 2.选取dis中的最小值对应的顶点加入到生成树中,然后根据该顶点的边来更新dis数组
	* ---- 3.重复2步骤n-1次
	</pre>
	</div>
EOT;
	
	printf('<h4>需要用到的边如下:</h4>');
	
	$adjlist = new AdjacencyList($paths, true);
	
	$len = least_tree2($nodes, $adjlist, $log);
	
	$sum = 0;
	
	for ($i=0; $i<count($log); $i++) {
		if ($i !== count($log) - 1) {
			printf('<b>%d - %d</b><br>', $log[$i], $log[$i+1]);
		}
	}
	
	printf('走过的路径是:<b>%d</b><br>', $len);
	
	echo('</div>');
}

/**
 * Kruskal原理：
 *		1、首先将边按权值从小到到排序(这里使用快排)
 *	    2、初始化并查集数组。
 *		3、然后遍历排好序的边，如果边的好个顶点已经不在同一棵树（拥有相同的根节点），则该边为有效边
 *		4、步骤3遍历一直到n-1条有效边。
 *		5、如果最后没有n-1条有效边，则不存在最小生成树，应该抛出异常
 */
function least_tree($nodes, &$paths){
	quickSort2($paths, 0, count($paths)-1);
	
	$tree = [];
	for ($i=1; $i<=$nodes; $i++) {
		$tree[$i] = $i;
	}
	
	//用来记录有效边 valid edge
	$ve = [];
	
	//按边的权值asc遍历
	for ($i=0; $i<count($paths); $i++) {
		$l = find($tree, $paths[$i][0]);
		$r = find($tree, $paths[$i][1]);
		
		if ($l !== $r) {
			//还是使用`靠左`原则
			$tree[$r] = $l;
			$ve[] = $i;
		}
		
		//n个顶点只需要n-1条边
		if (count($ve) === $nodes - 1) {
			break;
		}
	}
	
	return $ve;
}

function least_tree2($nodes, $adjlist, &$path){
	
	//根节点(生成树中的第一个点)
	$root = 1;
	
	//dis中没有边到达的顶点设为infinity
	$inf = 99999999;
	
	//标识哪些顶点已在生成树中
	$books = [];
	
	//保存当前树到各顶点的距离
	$dis = [];
	
	//已就位的顶点集合
	//其实和$books一样,只是是按顺序保存的顶点,所以方便找到顶点连通路线
	$fin = [];
	
	//记录走过的最长距离
	$max = 0;
	
	for ($i=1; $i<=$nodes; ++$i) {
		$dis[$i] = $inf;
	}
	
	$dis[1] = 0;
	$books[$root] = 1;
	array_push($fin, $root);
	
	
	$adjlist->loop($root, function($u, $v, $w) use (&$dis){
		$dis[$v] = $w;
	});
	
	//还需要找到n-1个顶点
	for ($i=1; $i<$nodes; $i++) {
		$node = null;
		$min = $inf;
		for ($j=1; $j<=$nodes; $j++) {

			if (empty($books[$j]) && $min > $dis[$j]) {

				$node = $j;
				$min = $dis[$j];
			}
		}
		
		$max += $min;
		
		$books[$node] = 1;
		array_push($fin, $node);
		
		$adjlist->loop($node, function($u, $v, $w) use (&$dis, &$books){
			(empty($books[$v]) && $dis[$v] > $w) and $dis[$v] = $w;
		});
	}
	//print_r($fin)
	$path = $fin;
	
	return $max;
}

//寻找根节点 并压缩树层次
function find(&$tree, $node){
	if ($tree[$node] === $node) {
		return $node;
	}
	
	$tree[$node] = find($tree, $tree[$node]);
	return $tree[$node];
	
}

//https://www.zhihu.com/question/23171968
function quickSort2(&$list, $s, $e){
	if ($s >= $e) {
		return;
	}
	
	$m = $s;
	
	for ($i=$s+1; $i<=$e; $i++) {
		if ($list[$i][2] < $list[$s][2]) {	//modify according to the actual situation
			swap($list, ++$m, $i);
		}
	}
	
	swap($list, $s, $m);
	
	quickSort2($list, $s, $m-1);
	quickSort2($list, $m+1, $e);
	
}

//这里的元素是数组，所以不能用in-place完成交换
function swap(&$list, $s, $e){
	/* if ($s !== $e) {
		$o = &$list[$s];
		$t = &$list[$e];
		
		$o ^= $t;
		$t = $o ^ $t;
		$o = $o ^ $t;
	} */
	if ($s !== $e) {
		$tmp = $list[$s];
		$list[$s] = $list[$e];
		$list[$e] = $tmp;
	}
	return;
}


function edgeQuickSort(array &$l, int $s, int $e){
	if ($s === $e) {
		return;
	}
	
	//$e $s以后还要用 
	$ee = $e;
	$ss = $s;
	
	//以最左的点为起点
	$base = $l[$s];
		
	while (true) {
		$r; 
		$z;
		
		//既然以最左的点为起点，就必须要从右边搜起
		while ($ee > $ss) {
			$r = $l[$ee];
			if ($r < $base) {
				break;
			}
			--$ee;
		}
		
		while ($ss < $ee) {
			$z = $l[$ss];
			if ($z > $base) {
				break;
			}
			++$ss;
		}
		
		//已经完成了快排的核心功能(大小分两边)
		if ($ee === $ss) {
			break;
		}
	
		//交换两值
		swap($l, $ee, $ss);
		
		//已经交换了 就不必再比了
		--$ee;
	}
	
	swap($l, $ee, $s);
	
	//此时 $ss === $ee
	if ($s < $ee) {
		edgeQuickSort($l, $s, $ee-1);
	} 
	
	if ($e > $ee) {
		edgeQuickSort($l, $ee+1, $e);
	}
	
	return;
}