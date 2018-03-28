<?php

require('bfs.php');

function test($pic, $x, $y){
	
	echo "<link href=\"../css/style.css\" rel='stylesheet'>";
	
	$area = [];
	bfs($x, $y, $pic, function($pointV, $point, $books) use ($pic, &$area){
			$area = $books;
			if ($pointV <= 0) {
				return false;
			} 
			
		}, $debuginfo);
	
	$needtime = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
	$head = sprintf(
		'<div class="hd">统计所在岛屿面积，共<i>%d</i>步，探索耗时：<i>%.3f</i>秒，历经<i>%d</i>次探索(<i>%d</i>递归)</div>',
		count($area), 
		$needtime, 
		(isset($debuginfo['loop']) ? $debuginfo['loop'] : 0), 
		(isset($debuginfo['count']) ? $debuginfo['count'] : 0)
		);
		
	$presentCls = ['water', 'land'];
	$present = ['※', '.', '~'];
	$html = '';
	
	foreach ($pic as $_x => $v) {
		$tmpl = '';
		foreach ($v as $_y => $vv) {
			$cls = $presentCls[$vv < 1 ? 0 : 1];
			if ($_x === $x && $_y == $y) {
				$cls = 'start';
				$lab = '※';
			
			} elseif ($vv < 1) {
				$cls = 'water';
				$lab = '~';
			} else {
				$cls = 'land';
				$lab = '#';
				
				isset($area[$_x.','.$_y]) && $cls = 'neighbor';
			}
			 
			$tmpl .= sprintf('<em class="%2$s">%1$s</em>', $lab, $cls);
		}
		$html .= '<div class="it">'. $tmpl .'</div>';
	}
	
	$html = str_replace('x', $html, "<div class=\"maze\">${head}x</div>");
	printf('<div class="head">使用算法：%s</div>', '广度优先搜索');	
	
	echo $html;
	
}

$pic = [
	[0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,1,0,0,1,1,0,0,0,0,0,0,1,2,0],
	[0,0,0,0,0,0,0,0,0,1,1,1,0,0,1,0,0,2,1,0],
	[0,0,0,0,0,0,0,1,0,0,1,0,0,1,1,0,0,5,0,0],
	[0,1,0,0,1,0,0,0,1,0,0,0,0,1,1,0,0,0,0,0],
	[0,0,0,0,0,0,1,0,0,1,0,1,0,1,1,0,0,0,0,0],
	[0,0,0,0,0,0,2,0,1,0,1,1,1,1,1,0,0,0,0,0],
	[0,0,0,0,0,0,1,0,0,1,1,1,1,1,0,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,1,1,1,1,1,0,0,0,0,3,0],
	[0,0,0,0,0,0,0,0,0,1,1,1,1,1,0,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,0,0,0,0,0],
	[0,1,1,1,0,0,0,0,0,0,0,0,0,0,1,1,0,0,0,0],
	[0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,0,0],
	[0,0,0,0,0,6,0,0,0,0,0,0,1,1,1,1,0,0,0,0],
	[0,0,0,0,1,3,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,7,8,1,0,0,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
];

test($pic, 8, 10);