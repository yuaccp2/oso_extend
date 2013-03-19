<?php
	include('init.php');
?>
<form method="get" action="">
	
<?php
echo 'Category: '.tep_draw_pull_down_menu('cid', tep_get_category_tree());
echo '双引号:<input type="checkbox" name="marks"'.(isset($_GET['marks']) ? 'checked' : '').' >';
echo '<br/>目录名：'	. tep_draw_input_field('category_name');
	if(isset($_GET['cid']) && $_GET['cid']){
		$obj_categories = new categories(true);
		$cate_ids = $obj_categories->get_subcategories($_GET['cid']);
		$products_sql = 'SELECT products_id,products_model, products_discount, products_price FROM products 
						LEFT JOIN products_to_categories USING(products_id)
						WHERE categories_id in ('.join(',', $cate_ids).')';
		$products_query = tep_db_query($products_sql);
		$products_model = array();
		while($product = tep_db_fetch_array($products_query)){
			$products_model[] = $product['products_model'];
		}
		if(isset($_GET['marks']) && $_GET['marks']){
			echo '<br/><textarea name="content" rows="15" cols="80">"'.join('","', $products_model).'"</textarea>';
		}else{
			echo '<br/><textarea name="content" rows="15" cols="80">'.join(',', $products_model).'</textarea>';
		}
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
	}elseif($_GET['category_name']){
		$obj_categories = new categories(true);
		$category_query = tep_db_query('select * from categories_description where categories_name ="'.$_GET['category_name'].'" and language_id = 1');
		while($rows = tep_db_fetch_array($category_query)){
			$cate_ids = $obj_categories->get_subcategories($rows['categories_id']);
			echo '<br/>'.sprintf('%d : %s - %s', $rows['categories_id'], $rows['categories_name'],join(',',$cate_ids));
		}
	}
?>

<input type="submit">
</form>
