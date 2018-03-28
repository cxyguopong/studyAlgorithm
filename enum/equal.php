<?php

ini_set('max_execution_time', 120);

function equal(){
	$a = [];
	$b = [];
	$c = [];
	$k = [];
	
	for ($a[0]=1; $a[0]<=9; $a[0]++)
		for ($a[1]=1; $a[1]<=9; $a[1]++)
			for ($a[2]=1; $a[2]<=9; $a[2]++)
				for ($a[3]=1; $a[3]<=9; $a[3]++)
					for ($a[4]=1; $a[4]<=9; $a[4]++)
						for ($a[5]=1; $a[5]<=9; $a[5]++){
							//for ($a[6]=1; $a[6]<=9; $a[6]++)
							//	for ($a[7]=1; $a[7]<=9; $a[7]++)
									//for ($a[8]=1; $a[8]<=9; $a[8]++){
									
										for ($i=0; $i<=8; $i++)
											$b[$i] = 0;
										
										//for ($i=0; $i<=8; $i++)
										for ($i=0; $i<=5; $i++)
											$b[$a[$i]] = 1;
										
										$sum = 0;
										for ($i=0; $i<=8; $i++)
											$sum += $b[$i];
										
										$d = $a[0]*10 + $a[1];
							//			$d = $a[0]*100 + $a[1]*10 + $a[2];
							//			$e = $a[3]*100 + $a[4]*10 + $a[5];
										$e = $a[2]*10 + $a[3];
										
							//			$f = $a[6]*100 + $a[7]*10 + $a[8];
										$f = $a[4]*10 + $a[5];
										
										//if ($sum === 9 && $d + $e === $f) {
										
										//$d < $e：因数不可能相等、避免重复
										if ($sum === 6 && $d < $e && $d + $e === $f) {	
											$c[] = [$d, $e, $f]; 
										} 
									}
											
	return $c;									
}

function dfs($step = 1, $n = 9){
	static $combs = [];
	static $book = [];
	static $comb = [];
	static $level = 0;
	
	if ($step === $n + 1) {
		$a = $comb[0]*100 + $comb[1]*10 + $comb[2]; 
		$b = $comb[3]*100 + $comb[4]*10 + $comb[5];
		$c = $comb[6]*100 + $comb[7]*10 + $comb[8];
		($a < $b && $a + $b === $c) and $combs[] = $comb;
		return;
	}
	
	for ($i = 1; $i <= $n; $i++) {
		if (empty($book[$i])) {
			$comb[$step-1] = $i;
			$book[$i] = 1;
			
			++$level;
			dfs($step+1, $n);
			$book[$i] = 0;
			--$level;
		}
		
	}
	
	if ($level > 0) {
		return;
	}
	
	$temp = $combs;
	$combs = [];
	
	return $temp;
}

function test(){
	$list = dfs();
	
	printf('共有组合：%d<hr><br>', count($list));
	
	foreach ($list as $v) {
		$tmpl = <<<EOT
			<div class="it">
				<em>%d</em>
				<em>%d</em>
				<em>%d</em>
				+
				<em>%d</em>
				<em>%d</em>
				<em>%d</em>
				=
				<em>%d</em>
				<em>%d</em>
				<em>%d</em>
			</div>
EOT;
		array_unshift($v, $tmpl);
		call_user_func_array('printf', $v);
	}
}

echo <<<EOT
	<style>
		.it {
			margin-top:10px;
		}
		.it em {
			line-height: 25px;
			padding: 0 5px 0 5px;
			color:#03A9F4;
			font-style:normal;
			border:1px solid;
			box-shadow: 0 0 1px;
		}
	</style>
EOT;

test();

/* $l = equal();

foreach ($l as $v) {
	array_unshift($v, '%d + %d = %d<br>');
	call_user_func_array('printf', $v);
} */