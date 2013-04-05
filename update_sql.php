<?php
include('init.php');

/**
* 
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function get_category_primary_id($id){
	$query = tep_db_query('select categories_id, parent_id from categories where categories_id = "'.$id.'"');
	$info = tep_db_fetch_array($query);
	if($info['parent_id'] > 0){
		return get_category_primary_id($info['parent_id']);
	}else{
		return $info['categories_id'];
	}
}
$type = $_GET['type'];
if($type == 'product'){
	$orders_ids = array('20126680','20127225','20127232','20127159','20127250','20127010','20127055','20127128','20127150');
	$orders_values = array();
	foreach ($orders_ids as $key => $val){
		$orders_values[] = "('{$val}', '6', NOW(), '0')";
	}
	echo "INSERT INTO orders_status_history (orders_id, orders_status_id, date_added, customer_notified) VALUES ".join(',', $orders_values).';<br/>';
	echo 'update orders set orders_status = 6 where orders_id in ('.join(',', $orders_ids).')'.';<br/>';
	die();
	$cate_ids = $obj_categories->get_subcategories(492);
	$products_sql = 'SELECT products_id,products_model, products_discount, products_price FROM products 
					LEFT JOIN products_to_categories USING(products_id)
					WHERE categories_id in ('.join(',', $cate_ids).') AND products_quantity > 0 and products_status = 1';
	echo $products_sql.'<br/>';
	$products_query = tep_db_query($products_sql);
	while($product = tep_db_fetch_array($products_query)){
		$price = $product['products_price'];
		$discount = $product['products_discount'];
		//$discount = '50';
		//$price = number_format($price * 0.5, 2);
		//$sql = 'update products set products_price = "'.$price.'",products_discount = "'.$discount.'" where products_model = "'.$product['products_model'].'";';
		echo $sql.'<br/>';
	}
}elseif($type == 'category'){
	$obj_categories = new categories(true);

	$sql = 'select * from categories where parent_id > 0 and primary_id = 0 #and categories_id = 888';
	$sql = 'select * from categories where parent_id > 0';
	$query = tep_db_query($sql);
	while($info = tep_db_fetch_array($query)){
		$primary_id = get_category_primary_id($info['categories_id']);
		//var_dump($primary_id );die();
		$sql = 'update categories set primary_id = "'.$primary_id.'" where categories_id = "'.$info['categories_id'].'";';
		echo $sql.'<br/>';
	}
}
?>