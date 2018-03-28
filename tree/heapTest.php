<?php

require_once('../func.php');

require_once('MinHeap.php');
require_once('Heaputil.php');

outputCss();

test();

function test(){
	$arr = [19,3,7,8,1,4,3,111,32,44,69,2,9,35,446,58,231,76,1];
	
	$space = '&nbsp;&nbsp;&nbsp';
	
	$type = 0;
	
	printf("<h2>堆实例</h2>");
	
	echo('<div class="wrap-center">');
	
	printf("<h4>初始数据(之后使用%s)</h4>",  $type === 0 ? '最小堆' : '最大堆');
	printf('<b>%s</b><br>', implode($space, $arr));
	
	echo("<h4>排序后</h4>");
	$heap = new MinHeap($arr, $type);
	
	printf('<b>%s</b><br>', implode($space, $heap->sort()));
	
	
	echo "<h4>插入元素并重新排序</h4>";
	
	foreach ([12, 0, -2] as $v) {
		$heap->push($v);
		printf('插入一个元素：<b>%d</b><br>', $v);
		printf('<b>%s</b><br>', implode($space, $heap->sort()));
		
	}
	
	
	$minth = 5;
	printf('<h4>求第/前n小/大的值</h4>', $minth);
	
	printf('<b>%s</b><br>', implode($space, $arr));
	$heapUtil = new HeapUtil($arr);
	printf('第%d小的值为：%d<br>', $minth, $heapUtil->minNth($minth));
	
	$maxth = 3;
	printf('第%d大的值为：%d<br>', $maxth, $heapUtil->maxNth($maxth));
	
	printf('前%d小的值为：%s<br>', $minth, implode(',', $heapUtil->minUntilNth($minth)));
	
	printf('前%d小的值为：%s<br>', $maxth, implode(',', $heapUtil->maxUntilNth($maxth)));
	
	echo('</div>');
}