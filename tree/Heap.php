<?php

class Heap {
	
	const HEAP_MIN = 0;
	
	const HEAP_MAX = 1;
	
	//堆的一维数组表示
	private $heap = [];
	
	//堆长度
	private $len = 0;
	
	//0-最小堆 1-最大堆
	private $type;
	
	/**
	 * @param array $array 一维数组
	 * @param int   $type 0-最小堆 1-最大堆
	 */
	protected function __construct(Array $array, int $type){
		//让堆元素从索引(编号1)开始算起,方便计算父子结点关系
		$this->heap[] = 0;
		
		$this->len = count($array);
		
		$this->type = $type;
		
		foreach ($array as $v) {
			$this->heap[] = $v;
		}
		
		$this->create();
	}
	
	/**
	 * 堆排序
	 * 最大堆->asc
	 * 最小堆->desc
	 *
	 * 为了不破坏当前堆的完整性，这里我们克隆堆，在克隆堆上做操作
	 */
	public function sort(){
		$head = clone $this;
		
		for ($i=$head->len; $i>1; $i--) {
			$head->swap(1, $i);
			$head->siftdown(1, $i-1);
		}
		
		return $head->get();
	}
	
	
	
	/**
	 * 插入一个元素
	 * @param int $val
	 * @return $this
	 */
	public function push($val){
		$this->heap[] = $val;
		++$this->len;
		
		$this->siftup($this->len);
		return $this;
	}
	
	//获取堆数组
	public function get(){
		return array_slice($this->heap, 1);
	}
	
	//获取堆顶
	public function getTop(){
		return $this->heap[1];
	}
	
	//替换堆顶
	//@return $this
	public function replaceTop($val){
		$this->heap[1] = $val;
		$this->siftdown(1);
		return $this;
	}
	
	//创建堆
	private function create(){
		
		//如果只有一个根节点,所以用intdiv
		$tail = intdiv($this->len, 2);
		
		for (; $tail>0; $tail--) {
			$this->siftdown($tail);
		}
		
		return $this;
	}
	
	/**
	 * 向下调整结点(根据$this->type)
	 *
	 * @param int $index 节点索引
	 * @param int $max 	 允许向下调整的最大索引(用于$this->sort时使用)
	 * @return null
	 */
	private function siftdown($index, $max = null){
		
		$flag = 0;
		
		$max === null and $max = $this->len;

		while ($index*2 <= $max && $flag === 0) {
			
			$t = $index;
			
			//最小堆
			if ($this->type === 0) {
				($this->heap[$index] > $this->heap[$index*2]) and $t = $index * 2;
				
				($index*2 + 1 <= $max && $this->heap[$t] > $this->heap[$index*2+1]) and $t = $index*2 + 1;
			
			//最大堆
			} else {
				($this->heap[$index] < $this->heap[$index*2]) and $t = $index * 2;
				
				($index*2 + 1 <= $max && $this->heap[$t] < $this->heap[$index*2+1]) and $t = $index*2 + 1;
			}
			
			if ($t !== $index) {
				$this->swap($index, $t);
				$index = $t;
			
			} else {
				$flag = 1;
			} 
			
		}
		
	}
	
	/**
	 * 向上调整结点
	 * 用于向堆中插入新值的情况
	 *
	 * @param int $index
	 * 
	 * @return $this
	 */
	private function siftup($index){
		while ($index/2 >= 1) {
			$pIndex = intdiv($index, 2);
			
			$needSwap = $this->type === 0 ? $this->heap[$index] < $this->heap[$pIndex] : $this->heap[$index] > $this->heap[$pIndex]; 
			
			if (!$needSwap) {
				break;
			}
			
			$this->swap($pIndex, $index);
			
			$index = $pIndex;
		}
		
		return $this;
	}
	
	/**
	 * 交换两个元素
	 * @param int $i 
	 * @param int $j
	 */
	private function swap($i, $j){
		$l = &$this->heap[$i];
		$r = &$this->heap[$j];
		
		$l ^= $r;
		$r = $l ^ $r;
		$l = $l ^ $r;
	}
}