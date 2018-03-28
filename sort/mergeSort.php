<?php

//归并排序

require_once('../queue/Queue.php');
require_once('../func.php');

outputCss();

run();

function run(){
	$sequence = [31, 41, 59, 26, 41, 58, 55,1,4,13,19,20,23,21,22,22,22,17,111,121,145,3,8,109,224];	
	$sort = 'desc';
	
	printf('<h2>归并排序</h2>');
	
	printf('<h4>Input:</h4><br><b>%s</b>', implode(',', $sequence));
	
	merge_sort_pack($sequence);
	
	printf('<h4>Output:</h4><br><b>%s</b>', implode(',', $sequence));
}

function merge_sort_pack(&$seq){
	merge_sort($seq, 0, count($seq)-1);
}

/**
 * 归并排序
 * @param array &$seq 待排序数列
 * @param int $i	//子序列开始位置
 * @param int $j	//子序列结束位置
 */
function merge_sort(&$seq, $i, $j){
	$len = $j - $i + 1;
	
	//长度为1 只有一个元素自然是排序好的
	if ($len === 1) {
		return;
	}
	
	//分解队列1的最后一个值
	$mid = $i + intdiv($len, 2) - 1;
	
	$c1 = merge_sort($seq, $i, $mid);
	$c2 = merge_sort($seq, $mid+1, $j);
	
	merge2($seq, $i, $mid, $j);
}

/**
 * 合并两个子数列
 * @param array &$seq 原始数组
 * @param int $i 子数列1在$seq中的开始索引
 * @param int $j 子数列1在$seq中的结束索引($j+1为子数列2在$seq中的开始索引)
 * @param int $k 子数列2在$seq中的结束索引
 */
function merge(&$seq, $i, $j, $k){
	$l = 0;
	//子数列2($j+1 ~ $k)的进度
	
	$c = new Queue();
	//用队列来保存排序好的数列
	
	for ($m=$i; $m<=$j; $m++) {
	//循环子数列1
	
		while ($j+1+$l <= $k) {
		//找出可能排在子数列1(元素值$seq[$m]) 的子数列2中的元素
		
			if ($seq[$j+1+$l] > $seq[$m]) {
			//子数列2中没有更多元素排在$seq[$m]之前了	
				break;
			}
			
			$c->push($seq[$j+1+$l]);
			//将子数列2中满足条件的元素push到$c中
			
			++$l;
			//子数列2指针后移
		}
		
		$c->push($seq[$m]);
		//push $seq[$m]
	}
	
	for ($m=$j+1+$l; $m<=$k; $m++) {
	//子数列2中剩下的元素均大于子数列1中元素
		$c->push($seq[$m]);
	}
	
	//将排序好的$c重新赋值给$i ~ $k
	for (;$i<=$k; $i++) {
		$seq[$i] = $c->get();
	}
	
}

/**
 * 合并两个子数列(出自算法导论)
 * @param array &$seq 原始数组
 * @param int $i 子数列1在$seq中的开始索引
 * @param int $j 子数列1在$seq中的结束索引($j+1为子数列2在$seq中的开始索引)
 * @param int $k 子数列2在$seq中的结束索引
 */
function merge2(&$seq, $i, $j, $k) {

	$l = [];
	//子数列1
	
	$r = [];
	//子数列2
	
	$inf = 99999999;
	//比$seq中所有值都大的无限值
	
	$n1 = $j - $i + 1;
	//$l的长度
	
	$n2 = $k - $j;
	//$r的长度
	
	for ($m=0; $m<$n1; $m++) {
		$l[$m] = $seq[$i + $m];
	}
	
	$l[] = $inf;
	//将$l的队底赋值无限值,这样它不可能小于$r的值,免去了判断empty($l)的问题
	
	for ($n=0; $n<$n2; $n++) {
		$r[$n] = $seq[$j + $n + 1];
	}
	
	$r[] = $inf;
	//同$l
	
	$p = 0;
	$q = 0;
	
	//只需要循环$k-$i+1次
	//因为两个队的inf值只可能是最后的两个值,而此时已经循环完毕了.
	for ($o=$i; $o<=$k; $o++) {
		if ($l[$p] < $r[$q]) {
			$seq[$o] = $l[$p];
			++$p;
		} else {
			$seq[$o] = $r[$q];
			++$q;
		}
	}
}
