<?php

require('Point.php');
//require('bfs.php');

//pipe form
$forms = ['━', '│', '┑', '┚', '┗', '┏'];

$pic = [
	[0,0,0,0,1,0,0,0],
	[0,0,0,0,0,0,1,0],
	[0,0,0,0,0,0,1,0],
	[0,0,1,0,0,0,0,0],
	[0,0,1,0,1,0,0,0],
	[0,0,0,1,2,0,0,1],
	[0,0,0,0,1,0,1,0]
]; 

$pic = [
	[1,2,0,2],
	[4,0,2,9],
	[5,2,0,4],
	[1,4,4,0],
	[4,0,0,3],
];

function dfs(Point $curPoint, $nextDirect, Point $endPoint, $outputDirect) {
		
	global $forms, $pic;
	
	static $book = [];
	
	static $way = [];
	
	static $res = [];
	
	static $level = 0;
	
	if (empty($pic)) {
		throw new InvalidArgumentException('$pic is empty!');
	}
	
	//[r b l t]
	$next = [[0,1], [1,0], [0,-1],[-1,0]];
	
	//pipe 对应的方向
	$formsDirects = [[0,2], [1,3], [1,2], [2,3], [0,3], [0,1]];
	
	//方向对应的 pipe
	$directs = [
			[0, 4, 5],  //right
			[1, 2, 5],	//bottom
			[0, 2, 3],	//left
			[1, 3, 4], 	//top
		];
	
	//上一步流水方向 与 pipe对接方向正好相反 
	$rightabouts = [2, 3, 0, 1];
	
	$result = false;

	$prev = end($way);
	//终点 其实是 n+1步，这里是越界的，所以只需要判断方向是否和最终出水口一致
	if ($prev[0] === $endPoint->x && $prev[1] === $endPoint->y) {
		if ($rightabouts[$nextDirect] === $outputDirect) {
			$res = $way;
			return true;
		}
		return;
		
	}
	
	$px = $curPoint->x;
	$py = $curPoint->y;
	
	//outBound
	if ($px < 0 || $px === count($pic) || $py < 0 || $py === count($pic[0])) {
		return;
	}
	
	$pv = $pic[$px][$py];
	
	//障碍物
	if (!isset($forms[$pv])) {
		return;
	}
	
	//pipe 连接方向
	$needDir = $rightabouts[$nextDirect];
	
	//对接方向适合的水管form
	$curForm = $directs[$needDir];
	$curForm = $pv <= 1 ? [$curForm[0]] : array_slice($curForm, 1);
	
	for ($i=0; $i<count($curForm); $i++) {
		$bk = $px.','.$py;
		if (empty($book[$bk])) {
			$pic[$px][$py] = $curForm[$i];
			
			$book[$bk] = 1;
			
			array_push($way, [$px,$py]);
			
			++$level;
			
			$fd = $formsDirects[$curForm[$i]];
			
			$nextDir = $fd[0] === $needDir ? $fd[1] : $fd[0];
			
			$nextPoint = new Point($px + $next[$nextDir][0], $py + $next[$nextDir][1]);
			
			$result = dfs($nextPoint, $nextDir, $endPoint, $outputDirect, $pic);
			
			array_pop($way);
			
			$book[$bk] = 0;
			
			--$level;

			if ($result === true) {
				break;
			}
			
			
		}
	}
	
	//重置静态变量
	if ($level === 0) {
		$temp = $res;
		$res = [];
		return $temp;
	}
	
	return $result;
	
}

function test(){
	
	global $pic, $forms;
	
	$x = 0;
	$y = 0;
	
	echo "<link href=\"../css/style.css\" rel='stylesheet'>";
	
	$needtime = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
	/* $head = sprintf(
		'<div class="hd">统计地图岛屿数量，共<i>%d</i>个岛屿</div>',
		$count ?:0
		);
		 */
	$presentCls = ['water', 'land'];
	$html = '';
	
	foreach ($pic as $_x => $v) {
		$tmpl = '';
		foreach ($v as $_y => $vv) {
			$present = $vv > 5 ? '▲' : $forms[$vv];
			 
			$tmpl .= sprintf('<em class="%2$s">%1$s</em>', $present, 'a');
		}
		$html .= '<div class="it">'. $tmpl .'</div>';
	}
	
	$html = str_replace('x', $html, "<div class=\"maze\">x</div>");
	printf('<div class="head">使用算法：%s</div>', '深度优先搜索');	
	
	echo $html;
	
	
	$way = dfs(new Point(0,0), 0, new Point(4,3), 2);
	print_r($way);
	
	$html = '';
	foreach ($pic as $_x => $v) {
		$tmpl = '';
		foreach ($v as $_y => $vv) {
			$present = $vv > 5 ? '▲' : $forms[$vv];
			 
			$tmpl .= sprintf('<em class="%2$s">%1$s</em>', $present, 'a');
		}
		$html .= '<div class="it">'. $tmpl .'</div>';
	}
	
	$html = str_replace('x', $html, "<div class=\"maze\">x</div>");	
	
	echo $html;
	
}



test(8, 10);