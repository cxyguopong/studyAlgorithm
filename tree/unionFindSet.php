<?php

require_once('../func.php');

outputCss();

run();

function run(){
	$len = 10;
	$paths = [[1,2], [3,4], [5,2], [4,6], [2,6], [8,7], [9,7], [1,6], [2,4]];
	
	printf("<h2>并查集(不相交集)</h2>");
	
	echo('<div class="wrap-center">');
	
	printf("<div class=\"tip\">共<b>%d</b>个节点(从<b>1</b>开始)</div>", $len);
	
	printf('<h4>线段如下</h4>');
	for ($i=0; $i<count($paths); $i++) {
		printf("<b>%d - %d</b><br>", $paths[$i][0], $paths[$i][1]);
	}
	
	printf("<h4>并查集(不相交集)</h4>");
	$ret = union_find_set($len, $paths);
	$num = 0;
	for ($i=0; $i<$len; $i++) {
		if ($i+1 == $ret[$i]) {
			printf("<b class=\"cell\">%d</b>", $ret[$i]);
			++$num;
		} else {
			printf("<b>%d</b>", $ret[$i]);
		}
	}
	
	echo("<br>");
	
	for ($i=1; $i<=$len; $i++) {
		printf("<b>%s</b>", $i);
	}
	
	printf("<br><b>共%s个不相交数据集</b>", $num);

	printf('<h4>常规写法</h4>');
	$ret = union_find_set2($len, $paths);
	
	$num = 0;
	for ($i=0; $i<$len; $i++) {
		if ($i+1 == $ret[$i]) {
			printf("<b class=\"cell\">%d</b>", $ret[$i]);
			++$num;
		} else {
			printf("<b>%d</b>", $ret[$i]);
		}
	}
	
	echo("<br>");
	
	for ($i=1; $i<=$len; $i++) {
		printf("<b>%s</b>", $i);
	}
	
	printf("<br><b>共%s个不相交数据集</b>", $num);
	
	echo('</div>');
}

//推荐使用写法2
function union_find_set($len, $paths){
	$trees = [];
	
	//让索引从1开始
	$trees[] = 0;
	
	for ($i=1; $i<=$len; $i++) {
		$trees[] = $i;
	}
	
	for ($i=0; $i<count($paths); $i++) {
		$path = $paths[$i];
		
		//根结点使用靠左原则，即线段0索引为1索引的父级
		$chi = $path[1];
		$root = $path[0];
		while ($trees[$root] !== $root) {
			$root = $trees[$root];
		}
		
		union_find_set_core($trees, $chi, $root);
	}
	
	return array_slice($trees, 1);
}

//其实写法2比写法1更清晰，主流一些。但两者求出的数组结果却没太大差异，树最多不会超过三层
//写法1每次将右侧节点归于左侧根时，会将右侧根到右结点的每个结点父级归为左侧根进行缩短树层次。并没有对左侧节点进行压缩，事实上左侧树也不可能超过三层。
//写法2将压缩树的代码独立成函数，整体结构清晰明了。（每次对左、右两个结点的树进行压缩）
function union_find_set2($len, $paths){
	$trees = [];
	
	//让索引从1开始
	$trees[] = 0;
	
	for ($i=1; $i<=$len; $i++) {
		$trees[] = $i;
	}
	
	for ($i=0; $i<count($paths); $i++) {
		$path = $paths[$i];
		
		//根结点使用靠左原则，即线段0索引为1索引的父级
		$l = find($trees, $path[0]);
		$r = find($trees, $path[1]);
		
		if ($l !== $r) {
			$trees[$r] = $l;
		}
		
	}
	
	return array_slice($trees, 1);
}

function union_find_set_core(&$trees, $node, $root){
	
	//表示$node本来就属于$root
	//其实当前探索的是一条冗余的线段
	if ($node === $root) {
		return;
	
	//探索到顶了
	} elseif ($trees[$node] === $node) {
		$trees[$node] = $root;
		return;
	}
	
	//继续探索父级
	$need = union_find_set_core($trees, $trees[$node], $root);
	
	//将回溯的每个节点的父节点都改成根节点
	//其实这里和功能无关，和后续的合并效率有关
	//
	//相当于将原本的e->d->c->b->a，改成了e->a,d->a,c->a,b->a。
	//如果现在有一条线索[e,f]，如果是原先的链式多层次结点，e递归要经过d,c,b最后找到根a
	//而现在e的父级就是a，这极大的提高了查找的效率
	//而这条语句是在回溯中执行的，并不需要额外的时间
	
	$trees[$node] = $root;
	return;
}

function find(&$trees, $x){
	if ($trees[$x] !== $x) {
		$trees[$x] = find($trees, $trees[$x]);
	}
	return $trees[$x];
}