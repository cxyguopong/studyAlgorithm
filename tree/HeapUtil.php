<?php
 
require_once('Heap.php');
require_once('MinHeap.php');
require_once('MaxHeap.php');
 
class HeapUtil {
	
	const HEAP_MIN = 0;
	const HEAP_MAX = 1;
	
	private $list;
	
	public function __construct($list){
		$this->list = $list;
	}
	
	//求第n小的值
	//求第n小的值用n大小的最大堆
	public function minNth(Int $n){
		$head = $this->updateMaxHead($n);
		
		return $head->getTop();
	}
	
	//求第n大的值
	//求第n大的值用n大小的最小堆
	public function maxNth(Int $n){
		$head = $this->updateMinHead($n);
		
		return $head->getTop();
	}
	
	//求前n小的值
	//求前n小的值用n大小的最大堆
	public function minUntilNth(Int $n){
		$head = $this->updateMaxHead($n);
		return $head->get();
	}
	
	//求前n大的值
	//求前n大的值用n大小的最小堆
	public function maxUntilNth(Int $n){
		$head = $this->updateMinHead($n);
		
		return $head->get();
	}
	
	//创建并更新n大小的最大堆
	private function updateMaxHead($n){
		$head = $this->createHeadN($n, self::HEAP_MAX);
		
		$headTop = $head->getTop();
		
		for ($i=$n; $i<count($this->list); $i++) {
			$this->list[$i] < $head->getTop() and $head->replaceTop($this->list[$i]);
		}
		
		return $head;
	}
	
	//创建并更新n大小的最小堆
	private function updateMinHead($n){
		$head = $this->createHeadN($n, self::HEAP_MIN);
		$headTop = $head->getTop();
		
		for ($i=$n; $i<count($this->list); $i++) {
			$this->list[$i] > $head->getTop() and $head->replaceTop($this->list[$i]);
		}
		
		return $head;
	}
	
	private function createHeadN($n, $type){
		$h = [];
		
		for ($i=0; $i<$n; $i++) {
			$h[] = $this->list[$i];
		}
		
		switch ($type) {
			case self::HEAP_MIN:
				return new MinHeap($h);

			case self::HEAP_MAX:
				return new MaxHeap($h);
			}
	}
}