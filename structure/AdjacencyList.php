<?php

//必须是连通图,即在边中能循环到每个顶点
class AdjacencyList 
{
	//默认边权值
	const DEFAULT_SIDE_VAL = 0;
	
	//是否无向边
	private $undirect = false;
	
	//边原始数据
	private $sides = [];
	
	//边对应的起点
	private $u = [];
	
	//边对应的终点
	private $v = [];
	
	//边权
	private $w = [];
	
	//起点对应的第一条边
	//邻接表的特殊结构，这里每个起点的第一条正好是sides顺序该起点的最后一条边
	private $first = [];
	
	//起点对应的边集合(不包括第一条边)
	//元素索引对应的是边编号，元素值对应起点下一条边编号
	//邻接表 的特殊结构，这里每个起点的边编号与sides参数正好是相反的顺序
	//如果元素值=-1，表示该元素索引是该顶点的最后一条边
	private $next = [];
	
	//最大顶点编号
	private $max = null;

	/**
	 * __construct
	 * @param array $sides 边二维数组 索引0-起点 1-终点 2-边权(可以不传)
	 * @param boolean $undirect 是否有向图(默认为有向图)
	 */
	public function __construct(Array $sides = [], bool $undirect = null){
		$this->sides = $sides;
		isset($undirect) and $this->undirect = $undirect;
		$this->create();
	}
	
	//创建邻接表
	private function create(){
		//防止同一条边
		$book = [];
		
		$len = count($this->sides);
		
		for ($i = 0; $i<$len; $i++) {
			
			//没有边权 给默认值
			if (isset($this->sides[$i][2]) === false) {
				$this->sides[$i][2] = self::DEFAULT_SIDE_VAL;
			}
			$side = $this->sides[$i];
			
			$max = $side[0] > $side[1] ? $side[0] : $side[1];
			
			$this->max < $max and $this->max = $max;
		
			//如果是无向图，则边数*2
			$this->undirect === true and array_push($this->sides, [$side[1], $side[0], $side[2]]);
			
		}
		
		//初始化$first
		for ($i=0; $i<=$this->max; $i++) {
			$this->first[$i] = -1;
		}

		//创建邻接表的核心代码
		for ($i=0; $i<count($this->sides); $i++) {
			$side = $this->sides[$i];
			
			$c = $side[0] . ',' . $side[1];
			
			//一个顶点到另一个顶点只能有一条边
			if (empty($book[$c])) {
				list($this->u[$i], $this->v[$i], $this->w[$i]) = $side;
				
				//下面两句是核心
				$this->next[$i] = $this->first[$this->u[$i]];
				$this->first[$this->u[$i]] = $i;
				
				$book[$c] = 1;
			}
			
		}
	}
	
	/**
	 * 循环所有的顶点的边信息
	 * @param callback $callback 回调函数(param1-顶点编号, param2-终点编号 param3-边权值)
	 */
	public function loopAll(Callable $callback){
		for ($i=0; $i<=$this->max; $i++) {
			$this->loop($i, $callback);
		}
	}
	
	/**
	 * 循环一个顶点的所有边信息
	 * @param int $point 顶点编号 
	 * @param callback $callback 回调函数
	 * ------ @param int 顶点编号
	 * ------ @param int 终点编号 
	 * ------ @param int 边权值 
	 * ------ @return mixed 如果返回false则退出循环 
	 */
	public function loop(Int $point, Callable $callback){
		if (isset($this->first[$point]) === false) {
			throw new InvalidArgumentException(sprintf('$point %d不存在连通图中', $point));
		}
		
		$side = $this->first[$point];
		
		while ($side !== -1) {
			$ret = call_user_func($callback, $point, $this->v[$side], $this->w[$side]);
			
			if ($ret === false) {
				break;
			}
			
			$side = $this->next[$side];
		}
	}
	
}