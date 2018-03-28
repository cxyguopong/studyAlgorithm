<?php

require_once('../func.php');
require_once('Simple.php');

function data($isMatrix = true){
	$edges = [[0,1,1],[0,2,12],[1,2,9],[1,3,3],[2,4,5],[3,2,4],[3,4,13],[3,5,15],[4,5,4]];
	
	if (!$isMatrix) {
		return $edges;
	}
	
	$pic = adjacency(createMatrix(6,6,999), $edges, false);
	return $pic;
}

function edgeIncludeNegative(){
	$edges = [[2,1,-6],[0,1,-1],[2,3,3],[0,2,4],[1,3,-3]];
	
	return $edges;
}
