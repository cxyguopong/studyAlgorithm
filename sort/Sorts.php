<?php

class Sorts {
	
	private $list = array();
	
	private $x =0;
	
	public static function quick($arr){
		$sort = new self;
		$sort->list = $arr;
		
		$sort->quickDo(0, count($arr)-1);

		$ret = $sort->list; 
		unset($sort);
		
		return $ret;
	}
	
	private function quickDo($i, $j){
		if ($j <= $i) {
			return;
		}
		
		$bh = $i;
		$jIdx = $j;
		
		$head = $base = $this->list[$i];
		$tail = $this->list[$j];
		
		//只是统计排序次数
		$this->x++;
		
		while (true) {
			while ($j != $i) {
				if ($tail < $base) {
					break;
				}
				--$j;
				
				$tail = $this->list[$j];
				
			}
			
			while ($j != $i) {
				if ($head > $base) {
					break;
				}
				++$i;
				$head = $this->list[$i];
				
			}
			
			
			//交换当前头尾值
			if ($j != $i) {	
				$head += $tail;
				$this->list[$j] = $tail = $head - $tail;
				$this->list[$i] = $head = $head - $tail;
				
			//确认base(基准)值位置，进行下一次二分排序
			} else {
			
				if ($i === $bh) {
					$this->quickDo(++$i, $jIdx);
				} else {
					$this->list[$j] = $base;
					$this->list[$bh] = $head;
					
					$this->quickDo($bh, $j - 1);

					++$j;
					$j < count($this->list) and $j <= $jIdx and $this->quickDo($j, $jIdx);
				} 
				break;
			}
		}
		return;
	}
	
}