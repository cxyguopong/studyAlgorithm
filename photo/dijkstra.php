<?php

//Dijkstra(迪科斯彻)算法——单源最短路径（假定没有负边）
require_once('MatrixData.php');

$pic = data();

outputCss();

printfMatrix();

test();

function test(){
	global $pic;
	
	printf('<h3>计算从0点到其他点(1-5)的最短路径</h3>');
	echo "<div class='wrap-center'><div class=\"bd-num num-style \">";
	$list = dijkstra(0, $nodes);
	
	for ($i=1; $i<count($pic); $i++) {
		$paths = [];
		$node = $nodes[$i];
		while ($node) {
			array_unshift($paths, $node->val);
			$node = $node->parent;
		}
		printf('源点0距离节点<b>%d</b>的最短距离是：<b>%d</b>，路径：<b>%s</b><br>', $i, $list[$i], implode('->', $paths));
	}
	
	echo "</div></div>";
}

function dijkstra($target, &$nodes = null){
	global $pic;
	
	//记录已经求值的点
	$book = [];
	
	//最短距离集合
	$p = [];
	
	//待求值的集合
	$q = [];

	//源节点对象 用于记录路径详情
	$targetNode = new Simple($target);
	
	//源点不需要求值
	$book[$target] = 1;
	
	//初始化$p、$q
	for ($i=0; $i<count($pic); $i++) {
		if (empty($book[$i]) && $i !== $target) {
			$q[$i] = $pic[$target][$i];
			
			//主要是方便最后不需要将数组按索引排序（因为最短距离不一定是从1-n按顺序来的）
			$p[$i] = 0;
		
			
			//记录路径详情
			$nodes[$i] = new Simple($i, $targetNode);
		}
		
	}
	
	//$q为空表示都已求值
	while (!empty($q)) {
		$min = null;
		$val = null;
		
		//在q中剩下的节点中找最短的节点
		foreach ($q as $k=>$v) {
			if ($min === null or $val > $v) {
				$min = $k;
				$val = $v;
			}
			
		}
		//记录节点$min已完成，并保存值
		$book[$min] = 1;
		$p[$min] = $val;
		
		unset($q[$min]);
		
		//以本次求出的最短距离节点“松弛”(更新$q中的最小距离)$q中剩下的节点
		foreach ($q as $k=>$v) {
			$comp = $val + $pic[$min][$k];
			
			$comp < $v and $q[$k] = $comp and ($nodes[$k]->setParent($nodes[$min]));
		}
	}
	
	return $p;
}


function printfMatrix(){
	global $pic;
	
	$html = '<h2>Dijkstra算法——单源最短路</h2>';
	$html .= '<div class="maze big">';
	for ($i=0; $i<count($pic); $i++) {
		$html .= "<div class='it'>";
		for ($j=0; $j<count($pic[$i]); $j++) {
			//$cls = $pic[$i][$j] == 1 ? 'active':'';
			$html .="<em class=\"\">".$pic[$i][$j]."</em>";
		}
		$html .= "</div>";
	}
	$html .= "</div>";

	echo $html;
}