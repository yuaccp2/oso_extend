<?php
	include('init.php');
	

$result = array();
$query = tep_db_query('select products_name,products_id from products_description where language_id = 3');
while($row = tep_db_fetch_array($query)){
	if($row['products_name'] == strtoupper($row['products_name'])){
		$result[] = $row['products_id'];
		//echo 'update products_description ';
	}
}
echo count($result).'<br/>';
echo join(',', $result);
die();
	$arr = array('ETATTOOM48','ETATTOOM46','ETATTOOM41','ETATTOOM10','ETATTOOM09','ETATTOOM04','ETATTOOM03','ETATTOOK75','ETATTOOK74','ETATTOOK57','ETATTOOK46','ETATTOOK39','ETATTOOK36','ETATTOOK32','ETATTOOK31','ETATTOOK30','ETATTOOK29','ETATTOOK28','ETATTOOK25','ETATTOOK24','ETATTOOK23','ETATTOOK22','ETATTOOK16','ETATTOOK12','ETATTOOK02','ETATTOOI04','ETABLETPC8','ETABLETA6','ETABLETA43','ETABLETA41','ETABLETA4','ESWIG044','ESWIG031');

	$start_time = microtime(true);
	$products = array();
	$query = tep_db_query('select products_model from products limit 0,10000');
	while($info = tep_db_fetch_array($query)){
		$products[] = $info['products_model'];
	}
	$len = count($products);
	$i = 0;

	while(true){//1秒3000~3300
		$end_time = microtime(true);
		if($end_time - $start_time >= 1.1){
			var_dump($i,$start_time, $end_time);
			break;
		}
		$key = $i%$len;
		if(isset($products[$key])) $i++;
	}
	
	$i = 0;
	$start_time = microtime(true);
	//1秒钟1000~1200
	while(true){
		$end_time = microtime(true);
		if($end_time - $start_time >= 1.1){
			var_dump($i,$start_time, $end_time);
			break;
		}
		$model = $products[$i%$len];
		$query = tep_db_query('select products_model from PRODUCTS where products_model = "'.$model.'"');
		if(tep_db_num_rows($query) > 0){
			$i++;
		}
	}
?>