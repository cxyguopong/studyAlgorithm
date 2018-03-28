<?php

//Dijkstra(迪科斯彻)算法——单源最短路径（假定没有负边）
require_once('MatrixData.php');

echo('<h2>floyed算法——任意两点最短路</h2>');

$pic = data();

outputCss();

printfMatrix();

test();

function test(){
	global $pic;
	
	printf('<h3>计算任意两个节点的最短路径</h3>');
	$list = floyd($nodes);
	
	printfMatrix();
}

function floyd(&$nodes = null){
	global $pic;
	
	$len = count($pic);
	for ($i=0; $i<$len; $i++) {
		for ($j=0; $j<$len; $j++) {
			for ($k=0; $k<$len; $k++) {
				($pic[$j][$k] > $pic[$j][$i] + $pic[$i][$k]) and ($pic[$j][$k] = $pic[$j][$i] + $pic[$i][$k]);
			}
		}
	}
}


function printfMatrix(){
	global $pic;
	
	$html = '';
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