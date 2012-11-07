<?php
include("init.php");
?>
<?php
	
	if(isset($_GET['cid']) && $_GET['cid']){
		$obj_categories = new categories(true);
		$cate_ids = $obj_categories->get_subcategories($_GET['cid']);

		$debug = false;
		$products_model = array();
		$currency = 'USD';
		if(isset($_GET['currency_code']) && $_GET['currency_code']){
			$currency = $_GET['currency_code'];
		}

		$sql = 'select p.*,pd.*, seo_name from products p
				left join products_description pd using (products_id) 
				left join products_to_categories using(products_id)
				left join seo_to_products using (products_id)
				where language_id=1 and categories_id in ('.join(',', $cate_ids).')';
		if($debug) $sql.=' limit 0,1';
		$templage_site_name = 'spy';

		$query = tep_db_query($sql);
		$i = 0;
		while($product_info = tep_db_fetch_array($query)){
			$i++;
			$pf->loadProduct($product_info['products_id'],3);
			$retail_price = $product_info['products_discount'] > 0 ?number_format((1/(1-$product_info['products_discount']/100)),2,'.','') : 1.3;

			//$retail_price = $currencies->format($product_info['products_price'] * $retail_price, true, 'EUR');
			//$out_price = $currencies->format($product_info['products_price'], true, 'EUR');
			$retail_price = $pf->getRetailSinglePrice($retail_price);
			$out_price = $pf->getSinglePrice();
			preg_match('/(?P<num>[0-9\.]+)/',$out_price, $match);
			if(isset($match['num'])){
				$new_price = floor($match['num'] * 0.95) + 0.99;
				$out_price = $currencies->format($new_price,false,$currency);
			}else{
				die('price is empty');
			}
			ob_start();
			include('./template/site_page/'.$templage_site_name.'.php');
			$content = ob_get_contents();
			ob_end_clean();
			$file_path = './output/site_page/'.$templage_site_name;
			if(!is_dir($file_path)) @mkdir($file_path);
			file_put_contents($file_path.'/'.$product_info['seo_name'].'.html',$content);
			$products_model[] = $product_info['products_model'];
		}
		echo 'success ' .$i.'<br/>';
		echo join(',',$products_model).'<br/>';
	}

?>
<form method="get" action="">
<?php
	echo 'Currency: '.tep_draw_pull_down_menu('currency_code', get_currency_list());
	echo 'Category: '.tep_draw_pull_down_menu('cid', tep_get_category_tree());

?>	
<input type="submit">
</form>
