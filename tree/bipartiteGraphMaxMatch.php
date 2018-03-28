<?php


require_once('../func.php');
require_once('../structure/AdjacencyList.php');
outputCss();

//男女配对
//男(女)生数量 两个集合(男/女生集合)的总数都为该值
$pointNum = 3;

//边数 表示男生认识的女生编号
//男生编号是1-3 女生的编号是7-9	
$edges = [[1,8], [1,7], [2,9], [2,8], [3,7]];

//创建邻接表
//注意这里是二分图，即同个集合的编号不可能有关联
//因为我们只需要循环二分图的其中一个集合(男生 或 女生).
//因为这里男/女生编号都是1-3 所以这里只能创建有向图邻接表(二分图的边是无向的)
//如果男/女生编号是不同区间，这里创建有向/无向都可以，因为循环只需要循环二分图的一个集合
//这里我们循环男生 ，所以只需要有向图就行了
$adjlist = new AdjacencyList($edges);

run();

function run(){
	global $edges, $adjlist;
	
	printf("<h2>二分图最大匹配</h2>");
	
	echo('<div class="wrap-center">');
	printf('<div class="tip">男生编号1-3 女生7-9</div>');
	printf('<h4>共%d条边:</h4>', count($edges));
	
	$adjlist->loopAll(function($u, $v){
		printf('<b>%d - %d<br></b>', $u, $v);
	});
	
	//从根节点开始 dfs
	$num = max_match($matchs);
	
	printf('<h4>最多配对数：%d</h4><br>', $num);
	foreach ($matchs as $k => $v) {
		printf('<b>%d - %d</b><br>', $v, $k);
	}
	echo('</div>');
}

function max_match(Array &$matchs = null){
	global $pointNum;
	
	//如果循环的是男生
	//那这里匹配的数组索引显然是女生
	//因为如果男生找到的女生已经配对了，这里要对已经匹配完成的配对进行连锁反应(需要知道女生配对的男生。然后让男生试着找其他女生)
	$matchs = [];
	
	//女生是否已匹配
	$books = [];
	
	$maxMatch = 0;
	
	//该开始 每个节点初始化为无匹配
	for ($i=7; $i<=$pointNum; $i++) {
		$matchs[$i] = -1;
	}
	
	//男生找女生(也可以反过来，不过这里就要是7-9了)
	for ($i=1; $i<=$pointNum; $i++) {
		$books = [];
		dfs($i, $matchs, $books) and ++$maxMatch;
	}
	
	return $maxMatch;
}

function dfs($i, &$matchs, &$books){
	global $adjlist;
	
	$ret = false;
	
	$adjlist->loop($i, function($u, $v) use (&$ret, &$books, &$matchs){
		if (empty($books[$v])) 
		//$v女生没有被标记，不管她是否匹配了我都可以`暂时`拥有她
		{
			$books[$v] = 1;
			//已经标记了 其他人不能再和她匹配
			
			if (empty($matchs[$v]) || dfs($matchs[$v], $matchs, $books)) {
			//1.如果她还没有匹配，我可以匹配她了，dfs结束不需要再深入了
			//2.她以前已经匹配了，不过还是属于我，但前提条件是匹配她的男士($matchs[$v])必须找到其她女性(进行dfs连锁反应)
			
				//记录女生匹配的男生
				$matchs[$v] = $u;
				
				//匹配成功
				$ret = true;	
				
				//退出循环
				return false;
			}
		}
		
	});
	
	return $ret;
}