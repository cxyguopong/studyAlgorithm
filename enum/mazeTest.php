<?php

require('dfs.php');
require('bfs.php');

$algorithm = isset($_GET['bfs']) ? 'bfs' : 'dfs';
$algorithmName = $algorithm == 'bfs' ? '广度优先搜索' : '深度优先搜索';


function test($pic){
	
	echo "<link href=\"../css/style.css\" rel='stylesheet'>";
		
	global $algorithm, $algorithmName;
	
	$debuginfo = [];
	$way = [];
	//深度优先搜索
	if ($algorithm === 'dfs') {
		$way = dfs(0, 0, $pic, $debuginfo);
	
	//广度优先搜索
	} else {
		bfs(0, 0, $pic, function($pointV, $point) use ($pic, &$way){
			if ($pointV === 1) {
				return false;
			} elseif ($pointV === 2) {
				while ($point) {
					array_unshift($way, [$point->x, $point->y]);
					$point = $point->parent;
				}
				return true;
			}
		}, $debuginfo);
	}
	

	$needtime = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
	$head = sprintf(
		'<div class="hd">共<i>%d</i>步，探索耗时：<i>%.3f</i>秒，历经<i>%d</i>次探索(<i>%d</i>递归)</div>',
		count($way), 
		$needtime, 
		(isset($debuginfo['loop']) ? $debuginfo['loop'] : 0), 
		(isset($debuginfo['count']) ? $debuginfo['count'] : 0)
		);
	
	array_walk($way, function(&$v){
		$v = implode(',', $v);
	});
	
	$directCls = [
		'lb' => 'lb',
		'bl' => 'bl',
		
		'lt' => 'lt',
		'tl' => 'lt',
		
		'rb' => 'rb',
		'br' => 'rb',
		
		'rt' => 'rt',
		'tr' => 'rt',
		
		'tb' => 'tb',
		'bt' => 'tb',
		
		'lr' => 'lr',
		'rl' => 'lr'
	];
	
	$direct = [];
	for ($i=0; $i < count($way)-1; $i++) {
		$point = explode(',', $way[$i]);
		
		$dr = '';
		for ($j=0; $j<=1; $j++) {
			if ($j === 0 && !isset($way[$i-1])) {
				continue;
			}
			
			$tar = $j === 0 ? $way[$i-1] : $way[$i+1];
			$tar = explode(',', $tar);
			
			if ($tar[0] != $point[0]) {
				$dr .= $tar[0] > $point[0] ? 'b' : 't';
			} else {
				$dr .= $tar[1] > $point[1] ? 'r' : 'l';
			}
			
		}
		
		if (strlen($dr) === 1) {
			$dr = in_array($dr, ['l', 'r']) ? 'lr' : 'tb';
		}
		
		$direct[$way[$i]] = $directCls[$dr];
		

	}
	

	
	$present = ['.', '■', '☆'];
	$presentCls = ['', 'wall', 'star'];
	$html = '';
	
	foreach ($pic as $x => $v) {
		$tmpl = '';
		foreach ($v as $y => $vv) {
			$cls = $presentCls[$vv];
			
			if ($vv === 0 && in_array($x.','.$y, $way)) {
				$cls.= 'act';
				$cls.= ' '.$direct[$x.','.$y];
			}
			 
			$tmpl .= sprintf('<em class="%2$s">%1$s</em>', $present[$vv], $cls);
		}
		$html .= '<div class="it">'. $tmpl .'</div>';
	}
	
	$html = str_replace('x', $html, "<div class=\"maze\">${head}x</div>");
	
	printf('<div class="head">使用算法：%s</div>', $algorithmName);
	
	echo $html;
	exit;
}

 $pic = [
	[0,0,0,0,1,0,0,0],
	[0,0,0,0,0,0,1,0],
	[0,0,0,0,0,0,1,0],
	[0,0,1,0,0,0,0,0],
	[0,0,1,0,1,0,0,0],
	[0,0,0,1,2,0,0,1],
	[0,0,0,0,1,0,1,0]
]; 
/* $pic = [
	[0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,1,0,0,1,1,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,0,1,0,0,1,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,1,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,1,0,0,1,0,1,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,2,0,1,0,1,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,1,0,0,0,1,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,0,0,0,1,0,1,1,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
]; */

test($pic);