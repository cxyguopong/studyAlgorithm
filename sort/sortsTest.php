<?PHP

require('Sorts.php');

function getList($len, $max, $min = 1) {
	$ret = array();
	for ($i =0; $i < $len; $i++) {
		array_push($ret, mt_rand($min, $max));
	}
	return $ret;
}


echo "<pre>";
echo "Input:<br>";
$list = getList(10000, 200000);

echo "Output:<br>";
$ret = Sorts::quick($list);

print_r($ret);