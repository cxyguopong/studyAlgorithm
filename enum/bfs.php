<?php

require('../queue/Queue.php');
require('./Point.php');


/**
 * 广度优先搜索
 * =====================================================================
 * = 函数本身不参与结果计算，只用于广度优先搜索探寻结果
 * = CLIENT 请使用者使用 $call 回调自己统计结果并优化搜索步骤
 * = $debuginfo 用于收集性能数据 
 * ======================================================================
 *
 * @param int $x 起始x坐标
 * @param int $y 起始y坐标
 * @param array $pic 探索二维矩阵图 
 * @param Callable $call 用户收集数据的回调函数，返回值可用于决定该节点是否 push 到探索队列\
 * 		  ===================================================================	  
 *		  = @param mixed 1 该节点的值
 *        = @param Point 2 节点对象
 *		  = @param array 3 已标记(走过)的节点
 *        = @return：  	  
 * 		  = 	false: 该节点不可搜索（不会push 到探索队列） 
 * 		  =  	true: 已找到目标，停止搜索
 *		  =		null/mixed: 不影响探索流程
 *        ===================================================================
 * @param &debuginfo 性能数据
 *
 * @return null
 */
function bfs($x, $y, $pic, Callable $call = null, &$debuginfo = null){
	
	$queue = new Queue();
	$books = [];
	$count = 0;
	
	$dist = null;
	
	$next = [[0,1],[1,0],[0,-1],[-1,0]];
	
	$queue->push(new Point($x, $y));
	
	$books[$x.','.$y] = 1;
	
	while (!$queue->isEmpty()) {
		//以当前队列头作为基础节点扩展
		$parent = $queue->get();
		
		++$count;
		
		for ($i=0; $i<count($next); $i++) {
			
			++$count;
			
			$pointX = $parent->x + $next[$i][0];
			$pointY = $parent->y + $next[$i][1];
			
			if ($pointX < 0 || $pointX === count($pic) || $pointY < 0 || $pointY === count($pic[0])) {
				continue;
			}
			
			$pointV = $pic[$pointX][$pointY];
			if (isset($books[$pointX.','.$pointY])) {
				continue;
			}
			
			$point = new Point($pointX, $pointY, $parent);
			
			if (isset($call)) {
				$res = $call($pointV, $point, $books);
				if ($res === true) {
					break 2;
				} else if ($res === false) {
					continue;
				}
			}
			
			/* if ($pointV === 2) {
				$dist = $point;
				break 2;	
			} */
			
			$books[$pointX.','.$pointY] = 1;
			$queue->push($point);
		}
		
	}

	$debuginfo['loop'] = $count;
	
	/* $ret = [];
	while ($dist) {
		array_unshift($ret, [$dist->x, $dist->y]);
		$dist = $dist->parent;
	}
	return $ret; */
}