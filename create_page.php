<?php
	include("init.php");
	
	if(isset($_GET['website']) && $_GET['website']){

		//默认值
		$debug = false;
		$products_model = array();
		$langauge_id = 1;
		$currency = 'USD';
		$price_mantissa = false;//价格尾数为0.99

		$website = $_GET['website'];

		switch($website){
			case 'myclickshop.co.uk':
				$currency = 'GBP';
				$langauge_id = 1;
				$categories_id = 523;
				$template = 'dreambox_uk.php';
				break;
			case 'dmbox.fr':
				$currency = 'EUR';
				$langauge_id = 3;
				$categories_id = 523;
				$template = 'dreambox_fr.php';
				break;
			case 'pathgadget.com':
				die('22上的目录已被移除');
				$currency = 'USD';
				$langauge_id = 1;
				$categories_id = 523;
				$template = 'spy.php';
				break;
			default:
				;
		}
		$out_path = './output/site_page/' . $website;
		if(!is_dir($out_path)) mkdir($out_path);

		$obj_categories = new categories(true);
		$cate_ids = $obj_categories->get_subcategories($categories_id);


		$sql = 'select p.*,pd.*, seo_name from products p
				left join products_description pd using (products_id) 
				left join products_to_categories using(products_id)
				left join seo_to_products using (products_id)
				where language_id='.$langauge_id.' and products_status = 1 and products_quantity > 0 and categories_id in ('.join(',', $cate_ids).')';
		if($debug) $sql.=' limit 0,1';

		$query = tep_db_query($sql);
		$i = 0;
		while($product_info = tep_db_fetch_array($query)){
			$i++;
			$pf->loadProduct($product_info['products_id'], $langauge_id);
			$retail_price = $product_info['products_discount'] > 0 ? number_format((1/(1-$product_info['products_discount']/100)), 2, '.', '') : 1.3;

			$retail_price = $currencies->format($product_info['products_price'] * $retail_price, true, $currency);
			$out_price = $currencies->format($product_info['products_price'], true, $currency);

			$out_price_val = number_format(tep_round($product_info['products_price'] * $currencies->get_value($currency), $currencies->get_decimal_places($currency)),  $currencies->get_decimal_places($currency),'.', '');

			if($price_mantissa){
				//小数点后变成0.99
				$out_price_val = floor($out_price_val) +0.99;
				$out_price = $currencies->format($out_price_val,false,$currency);
			}

			ob_start();
			include('./template/site_page/'.$template);
			$content = ob_get_contents();
			ob_end_clean();
			file_put_contents($out_path.'/'.$product_info['seo_name'].'.html', $content);
			
			$products_model[] = $product_info['products_model'];
		}
		#信息输出
		echo 'success ' .$i.'<br/>';
		if($cate_ids){
			foreach ($cate_ids as $key => $val){
				echo sprintf('%d : %s', $val, $obj_categories->get_name($val)).'<br/>';
			}
		}
		echo join(',',$products_model).'<br/>';
	}

?>
<form method="get" action="">
<?php
	//echo 'Currency: '.tep_draw_pull_down_menu('currency_code', get_currency_list());
	//echo 'Category: '.tep_draw_pull_down_menu('cid', tep_get_category_tree());
	$web_site_list = array(
							array('text'=>'myclickshop.co.uk', 'id'=>'myclickshop.co.uk'),
							array('text'=>'dmbox.fr', 'id'=>'dmbox.fr')
					);
	echo 'website: '.tep_draw_pull_down_menu('website', $web_site_list);

?>	
<input type="submit">
</form>
