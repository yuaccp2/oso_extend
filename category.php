<?php
	include('init.php');
$arr = array();
foreach ($obj_categories->data as $key => $val){
	$layer_id = $obj_categories->get_layer($key);
	if($layer_id == 2){
		if($obj_categories->get_child($key)){
			$arr[] = $key;
		}
	}
}
sort($arr);
echo join(',',$arr).'<br/>';
echo(count($arr));
die();
foreach ($arr as $key => $val){
	echo $val. ':' .$obj_categories->layer[$val].'<br/>';
}
	echo '3:' .$obj_categories->layer[3].'<br/>';
	echo '408:' .$obj_categories->layer[408].'<br/>';
?>