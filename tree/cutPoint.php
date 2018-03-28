<?php

require_once('../func.php');

outputCss();

//节点数，请根据边调整
$pointNum = 6;
	
$edges = [[1,4], [1,3], [4,2], [3,2], [2,5], [2,6], [5,6]];

//邻接表 各顶点的第一条边
$al_first = [];

//邻接表 各顶点的上一条边
$al_next = [];

//邻接表 顶点
$al_u = [];

//邻接表 连接点
$al_v = [];
	
run();

function run(){
	global $edges, $pointNum, $al_first, $al_next, $al_u, $al_v;
	
	printf("<h2>无向图求割点</h2>");
	
	echo('<div class="wrap-center">');
	
	printf('<h4>共%d条边:</h4>', count($edges));
	
	for ($i=0; $i<count($edges); $i++) {
		call_user_func_array('printf', array_merge(['<b>%d - %d</b><br>'], $edges[$i]));
	}
	
	adjacency_list();
	
	//生成树的根节点选择(这里怎么选都可以)
	$root = 1;
	
	//从根节点开始 dfs
	$cuts = dfs($root, $root, $root);
	
	print_r($cuts);
	
	echo('</div>');
}


//这里的边不能出现顶点到自己的边(即[1,1],[2,2]…)
//因为是无向图，所以两个顶点只能描述一次，不能出现([1,2],[2,1];…这种情况)
function adjacency_list(){
	global $edges, $pointNum, $al_first, $al_next, $al_u, $al_v;
	
	$len = count($edges);
	
	//因为是无向边，所以这里的边要*2
	for ($i=0; $i<$len; $i++) {
		$edges[] = [$edges[$i][1], $edges[$i][0]];
	}
	
	$edgeNum = count($edges);
	
	//点编号从1开始算起，但是我们保持数组为php索引数组
	for ($i=0; $i<=$pointNum; $i++) {
		$al_first[$i] = -1;
	}
	
	for ($i=0; $i<$edgeNum; $i++) {
		list($al_u[$i], $al_v[$i]) = $edges[$i];
		
		$al_next[$i] = $al_first[$al_u[$i]];
		$al_first[$al_u[$i]] = $i;	
	}
}

function dfs($cur, $parent, $root, $index = 1){
	global $pointNum, $al_first, $al_next, $al_u, $al_v;
	
	static $num = [], $min = [], $cut = [];
	
	$childN = 0;
	
	$edge = $al_first[$cur]; 
	
	//初始化
	
	$num[$cur] = $index;
	$min[$cur] = $index;
	
	while ($edge !== -1) {
		
		$child = $al_v[$edge];
		
		//下一个子节点
		$edge = $al_next[$edge];
		
		//子节点不能是$parent 最关键的一步
		//树的特性:任何节点都可以是根结点,自然父子关系可以倒转
		if ($child === $parent) {
			continue;
		}
		
		if (empty($num[$child]))
		//未扩展的子节点 
		{
			$childN ++;
			dfs($child, $cur, $root, $index + 1);
		
			$min[$cur] = min($min[$cur], $min[$child]);
			
			//只要有子顶点在不经过当前顶点的情况下，不能回到生成树，则该点为补割点
			if ($cur !== $root && $min[$child] >= $num[$cur]) {
				array_push($cut, $cur);
			}
			
			//作为根 如果产生第二个子节点 自然要列为割点
			if ($cur === $root && $childN === 2) {
				array_push($cut, $cur);
			}
		
		
		} else {
		//如果子节点已经访问,
			$min[$cur] = min($min[$cur], $num[$child]);
		}
			
	}
	
	//重置
	if ($cur === $root) {
		print_r($num);
		echo "<br>";
		print_r($min);
		echo "<br>";
		print_r($cut);
		exit;
		
		$temp = $cut;
		$num = []; $min = []; $cut = [];
		return $temp;
	}
	
	return;
}
