<?php
	include('init.php');
?>
<form method="get" action="">
	
<?php
echo 'Category: '.tep_draw_pull_down_menu('cid', tep_get_category_tree());
	
	if(isset($_GET['cid']) && $_GET['cid']){
		$cate_ids = $obj_categories->get_subcategories($_GET['cid']);
		$products_sql = 'SELECT products_id,products_model, products_discount, products_price FROM products 
						LEFT JOIN products_to_categories USING(products_id)
						WHERE categories_id in ('.join(',', $cate_ids).') AND products_quantity > 0 and products_status = 1';
		echo $products_sql.'<br/>';
		/*
		$products_query = tep_db_query($products_sql);
		while($product = tep_db_fetch_array($products_query)){
			$price = $product['products_price'];
			$discount = $product['products_discount'];
			//$discount = '50';
			//$price = number_format($price * 0.5, 2);
			$sql = 'update products set products_price = "'.$price.'",products_discount = "'.$discount.'" where products_model = "'.$product['products_model'].'";';
			echo $sql.'<br/>';
		}*/
	}
?>
<input type="submit">
</form>
