<?php
require('init.php');

	$categories_name_str = '"Flashes","Flash Triggers","Flash Sync Cable Cord","Shutter Release","Lights","Batteries and Chargers","Other Accessories","AA batteries","AAA batteries"';
	$sql = 'SELECT GROUP_CONCAT(categories_id) cids,  language_id FROM categories_description
			WHERE language_id = 1
			AND categories_name IN ('.$categories_name_str.')
			GROUP BY language_id';
	$category_info = tep_db_fetch_array(tep_db_query($sql));
	$category_ids = explode(',',$category_info['cids']);
	$all_cateids = array();
	foreach ($category_ids as $key => $val){
		$tmp = $obj_categories->get_subcategories($val);
		$all_cateids = array_merge($all_cateids, $tmp);
	}
	echo '<pre>';
	echo 'SELECT products_model, categories_name FROM products 
			LEFT JOIN products_to_categories USING(products_id) 
			LEFT JOIN categories_description USING(categories_id)
			WHERE categories_id IN ('.join(',', $all_cateids).') AND language_id = 1'.'<br/>';
	echo '</pre>';
?>