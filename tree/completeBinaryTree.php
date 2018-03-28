<?php

require_once('../func.php');
require_once("../queue/Queue.php");


$binaryTreeArray = [1,2,5,12,7,17,25,19,36,99,22,28,46,92];

outputCss();

run();

function run(){
	global $binaryTreeArray;
	
	$bta = &$binaryTreeArray;
	
	echo("<h2>完全二叉树</h2>");
	
	echo("<div class=\"wrap-center\">");
	
	echo('<h4>数列如下：</h4>');
	
	
	for ($i=0; $i<count($bta); $i++) {
		printf('<b>%d</b>', $bta[$i]);
	}
	
	echo "<br>";
	
	echo('<h4>将一维数组还原成呈现二叉树</h4>');
	
	$list = toTreeForm($binaryTreeArray);
	
	for ($i=0; $i<count($list); $i++) {
		printf('%s<br>', str_replace('-', '——', $list[$i]));
	}
	
	echo("<h4>打乱数列，再重排序成最小二叉树</h4>");
	
	for ($i=0; $i < 10; $i++) {
		$maxIndex = count($bta) - 1;
		$index1 = mt_rand(0, $maxIndex);
		$index2 = mt_rand(0, $maxIndex);
		
		if ($index1 !== $index2) {
			$bta[$index1] ^= $bta[$index2];
			$bta[$index2] = $bta[$index1] ^ $bta[$index2];
			$bta[$index1] = $bta[$index1] ^ $bta[$index2];
			
		}
	}
	
	for ($i=0; $i<count($bta); $i++) {
		printf('<b>%d</b>', $bta[$i]);
	}
	
	echo('<h4>最小堆</h4>');
	
	$heap = toMinHeap($bta);
	for ($i=0; $i<count($heap); $i++) {
		printf('<b>%d</b>', $heap[$i]);
	}
	
	echo("<br>");
	
	$list = toTreeForm($heap);
	
	for ($i=0; $i<count($list); $i++) {
		printf('%s<br>', str_replace('-', '——', $list[$i]));
	}
	
	echo "</div>";
}

/**
 * 创建最小堆算法
 * -------------------------------------------------------------------------------------------------------------
 * 算法原理:
 * 每次将一个数组元素加入到树尾
 *
 * # 如果当前加入元素后，树只有一个节点（即根结点），则不做处理
 *
 * # 比较当前元素与父元素大小，若小于父编号对应的元素，则两者交换，一直到当前元素小于父元素或置换成根元素则结束
 *
 * -------------------------------------------------------------------------------------------------------------
 */
function toMinHeap($list){
	
	//为了计算父、子节点编号的方便，完全二叉树的一维数组表示从索引1开始
	//则左子节点的编号为parentIndex*2，右子节点的编号为parentIndex*2+1;
	//父节点的编号为intdiv(childrenIndex, 2);
	$heap = [99999999];
	
	for ($i=0; $i<count($list); $i++) {
		$val = $list[$i];
		
		array_push($heap, $val);
		
		$len = count($heap);
		if ($len === 2) {
			continue;
		}
		
		$index = $len - 1;
		while ($index >= 1) {
			$pIndex = intdiv($index, 2);
			
			if ($pIndex < 1) break;
			
			//和父节点交换值
			if ($heap[$pIndex] > $heap[$index]) {
				$heap[$pIndex] ^= $heap[$index];
				$heap[$index] = $heap[$pIndex] ^ $heap[$index];
				$heap[$pIndex] = $heap[$pIndex] ^ $heap[$index];
			}
			$index = $pIndex;
		}
	}
	
	return array_slice($heap, 1);
}

/**
 * 创建最小堆算法二
 * 为了计算父子节点编号关系方便，数组索引(编号)从1开始
 * -------------------------------------------------------------------------------------------------------------
 * 算法原理:
 * 从$list尾部开始，将每个元素当成树根，然后从此元素向下（数组后面）比较父、子节点大小
 * # 若当前元素编号大于2/n，则表示元素编号为叶子节点，叶子节点做为根，则表示整棵子树只有1个元素，不需要做调整。
 *   所以实际上从intdiv(2/n)开始算起则可以了。
 *
 * # 将1-2/n的每个结点当前根元素，然后调整子树的最小堆（即父节点大于子节点，则交换两者位置）
 *
 * -------------------------------------------------------------------------------------------------------------
 */
function toMinHeap2($list){
	$max = count($list);
	$s = intdiv($max, 2);
	
	$swapfunc = function(&$one, &$two){
		$one ^= $two;
		$two = $one ^ $two;
		$one = $one ^ $two;
	}
	
	//让树编号从1开始
	array_unshift($list, 0);
	
	for (; $s>0; $s--) {
		$n = $s;
		
		while ($n <= $max) {
			$flag = 0;
			$c = $n;
			
			//left child-node
			$list[$n] > $list[$n*2] and $c = $n * 2;  
			
			//right child-node
			($n * 2 + 1 <= $max && $list[$c] > $list[$n * 2 + 1]) and $c = $n * 2 + 1; 
			
			//swap parent-node and child-node
			if ($c !== $n) {
				//change
				$flag = 1;
				
				//swap
				$list[$n] ^= $list[$c];
				$list[$c] = $list[$n] ^ $list[$c];
				$list[$n] = $list[$n] ^ $list[$c];
			}
				
			if ($flag == 0) {
				break;
			}
			
			$n = $c;
		}
		
	}
	
	return array_slice($list, 1);
}

/**
 * 使用深度优先搜索将一维数组表示的二叉树呈现出来
 * 如果根节点索引对应数组第一个元素索引0，则：
 * 		则左子节点索引是(0+1)*2-1、右子节点索引是:(0+1)*2。所以左子节点的索引计算公式是：(parentIndex + 1)*2 - 1，右子节点：(parentIndex + 1)*2
 * 如果根节点索引使用1（即数组第一个元素不使用），则：
 * 		左子节点索引：parentIndex*2 右子节点索引：parentIndex*2 + 1
 */
function toTreeForm($bta, $level=1, $index = null){
	static $dist = [];
	
	$level <= 1 and $level = 1;
	
	$level === 1 and $index = 0;
	
	if (isset($bta[$index])) {
		array_push($dist, str_repeat('-', $level - 1) . $bta[$index]);
		
	} else {
		return;
	}
	
	for ($i=0; $i<2; $i++) {
		toTreeForm($bta, $level + 1, ($index + 1) * 2 - 1 + $i);	
	}
	
	if ($level === 1) {
		$temp = $dist;
		$dist = [];
		return $temp;
	}
	
	return;
}

