<?php
	include('init.php');

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
		$sql = 'update products set products_price = "'.$price.'",products_discount = "'.$discount.'" where products_model = "'.$product['products_model'].'";';
		echo $sql.'<br/>';
	}
?>