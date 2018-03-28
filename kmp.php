<?php

echo microtime();

echo "<pre>";
echo microtime(true);

exit;


//$arr = array(1,2,13);
//var_dump(substr('1243223aa',0, -1));exit;

class BM_search {
	
	private $searchstr;
	
	private $badChars = [];
	
	private $goodSuffixs = [];
	
	private $strlen;
	
	function __construct($searchstr) {
		
		if ($searchstr === null || !($strlen = strlen($searchstr))) {
			throw new Exception('search string can\'t be null or empty string!');
		}
		$this->searchstr = $searchstr;
		$this->strlen = $strlen; 
	
		$this->makeBadChars();
		$this->makeGoodSuffix();
		
		
		var_dump($this->searchstr);
		echo '----------------';
		
		var_dump($this->badChars);
		
		echo '--------------';
		
		var_dump($this->goodSuffixs);
	}
	
	private function makeBadChars() {
		$cp = $this->searchstr;
		$len = strlen($cp);
		$i = 0;
		
		while ($i < $len) {
			$last = substr($cp, -1);
			
			$cp = substr($cp, 0, -1);
			array_unshift($this->badChars, strrpos($cp, $last));
			++$i;
		}
	}
	
	private function makeGoodSuffix() {
		$i = 1;
		while ($i <= $this->strlen) {
			$suffix = substr($this->searchstr, -$i);
			$prefix = substr($this->searchstr, 0, -1);
			
			
			
			$max = $i === $this->strlen ? -1 : ($prefix === $suffix ? $i : $this->goodSuffixs[0]);
			
				
			array_unshift($this->goodSuffixs, $max);
			++$i;
		}
	}
		
}

$search = 'abcadefgabca';
$bm = new BM_search($search);exit;



//$str = 'aaababcd';

//$pattern = 'bab';

//var_dump(_bm_str($str, $pattern));exit;


/* function _clear_next($searchstr) {
    $strlen = strlen($searchstr);
    $nextArr = [-1, 0];
    $j = 0;
    $k = 1;
    
    while ($k < $strlen -1) {
        if ($j === -1 || $searchstr[$j] == $searchstr[$k]) {
            $nextArr[++$k] = ++$j;
            
        } else {
            $j = $nextArr[$j];    
        }
    }
    return $nextArr;
}

$str = 'aaababcd';

var_dump(_clear_next($str));exit; */


function _next($searchstr) {
    $strlen = strlen($searchstr);
    $nextArr = [-1, 0];
    $j = 0;
    $k = 1;
    
    while ($k < $strlen -1) {
        if ($j === -1 || ($searchstr[$j] == $searchstr[$k])) {
            ++$j; 
			++$k;
			
			$nextArr[$k] = $j;
			
			$searchstr[$k] == $searchstr[$j] and $nextArr[$k] = $nextArr[$j];
			
            
        } else {
            $j = $nextArr[$j];    
        }
    }
    return $nextArr;
}


function kmp_search($text, $searchstr) {
    $tlen = strlen($text);
    $slen = strlen($searchstr);
    
    $cop = _next($searchstr);
    
    $j = $k = 0;
    
    while ($k < $tlen && $j < $slen) {
    //$k >= $tlen 表示搜索完字符；而$j == $slen 是匹配成功的标识
    
        if ($j === -1 || $searchstr[$j] == $text[$k]) {
        //$j == -1 也表示前面没有字符了，所以匹配字符= 0 = -1+1，而
        
            ++$j;
            ++$k;
        
        } else {
            $j = $cop[$j];    
        }
    }
    
    return $j === $slen ? $k - $j : false;
    
}


$str = '61aabdfdabbab2aababababa1babb1abjababajfabbeab1abcdabab';
$pattern = 'abab';

echo 'strlen:'.strlen($str), '<br>';

var_dump(kmp_search($str, $pattern));exit;