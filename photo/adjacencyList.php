<?php

require_once('MatrixData.php');

outputCss();

run();

function run(){
	$u = [];
	$v = [];
	$w = [];
	
	//顶点(有边的顶点)的第一条边的序号
	//相对于$edge,它存储的是$edge同一个顶点最后出现的边
	$first = [];
	
	//一个顶点边序号对应的下一条边的序号
	$next = [];
	
	//这里的顶点序号最大值为5;
	for ($i=0; $i<6; $i++) {
		$first[$i] = -1; 
	}
	
	$edge = data(false);
	
	echo("<h2>使用邻接表来存储边信息</h2>");
	
	echo('<div class="wrap-center">');
	printf('<h4>共%d条边.</h4><br>', count($edge));
	
	for ($i = 0; $i < count($edge); $i++) {
		list($u[$i], $v[$i], $w[$i]) = $edge[$i];
		
		call_user_func_array('printf', array_merge(['<b>%d</b>-><b>%d</b>:<b>%d</b><br>'], $edge[$i]));
		//记录边序号$i所属顶点的上一条边的序号，如果上一条边的序号为-1，则表示$i是最后一条l边
		$next[$i] = $first[$u[$i]];
		
		//更新顶点$u[$i]的第一条边的序号值
		$first[$u[$i]] = $i;
	}
	
	echo("</div>");
	
	echo('<div class="wrap-center">');
	
	echo('<h4>邻接表如下:</h4>');
	
	//很显然 循环出的边的序号的顺序与$edge中出现的顺序正好相反
	for ($i=0; $i<count($first); $i++) {
		$point = $first[$i];
		
		if ($point === -1) {
			continue;
		}
		
		printf('<ul><li>顶点: <b>%d</b><br></li><li><ul>', $u[$point]);
		
		while ($point !== -1) {
			printf('<li><b>%d</b>-><b>%d</b>:<b>%d</b><br></li>', $u[$point], $v[$point], $w[$point]);
			$point = $next[$point];
		}
		
		echo("</ul></li></ul>");
	}
	
	echo('</div>');
}