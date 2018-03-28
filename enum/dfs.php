<?php

/**
 * deeply first search
 * @author guopong
 */
function dfs($x, $y, $pic, &$debuginfo = null){
	//$best:最优路线 $way:当前探索路线 $book:标记当前路线已被走过的坐标 $level:0返回结果; >0递归返回上一次调用
	static $best = [], $way = [], $book = [], $level=0;
	
	//记录递归次数
	static $count=0;
	static $loop =0;
	
	if ($level === 0 && empty($pic)) {
		throw new InvalidArgumentException('$pic is empty!');
	}
	
	empty($pic) and $pic = $_pic;
	
	++$count;
	
	$next = [[0,1], [1,0], [0,-1], [-1,0]];
	
	$rows = count($pic);
	$cols = count($pic[0]);
	
	//撞墙 或 ArrayIndexOutOfBound 或 大于上一步完成探索best的步数(如果没有这步 dfs效率会很差)
	if ($x < 0 || $x === $rows || $y < 0 || $y === $cols || $pic[$x][$y] === 1 || (!empty($best) && count($best) < count($way))) {
		return;
		
	//找到目标
	} elseif ($pic[$x][$y] === 2) {
		$way[] = $x.','.$y;
		(empty($best) || count($best) > count($way)) and ($best = $way);
		array_pop($way);
		return;
	}
	
	for ($i=0; $i<count($next); $i++) {
		
		//统计
		++$loop;
		
		//当我不使用$form 存储坐标保存格式时，下面使用$form的地方 直接`$x. ','. $y`时，总时间慢了0.5秒左右
		$form = $x.','.$y;

		//开始前进
		if (empty($book[$form])) {
			
		//标记本坐标，告诉下一步的递归函数
		$book[$form] = 1;
		
		//将当前坐标push到临时路径中，
		//如果这里使用[$x,$y]存储，总速度慢了大概0.3秒
		array_push($way, $form);
		
		++$level;
	
		//探寻下一个坐标
		dfs($x+$next[$i][0], $y+$next[$i][1], $pic);
		
		//后续探索已结束，本坐标恢复可用
		$book[$form] = 0;
		
		//这一条路线及后续坐标已经尝试完毕，将当前点从way中删除
		array_pop($way);
		
		--$level;
		}
	}
	
	//探索完毕，退出当前递归函数
	if ($level > 0) {
		return;
	}
	
	//游戏结束 返回最佳路径结果
	$temp = array_map(function($v){
		return explode(',', $v);
	}, $best);
	
	$debuginfo = ['count'=>$count, 'loop'=>$loop];
	
	//还原静态变量
	$best = [];
	$count = 0;
	$loop = 0;
	
	return $temp;
}

