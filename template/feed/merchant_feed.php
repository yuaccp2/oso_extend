<?
set_time_limit(0);

$id_subfix = '-'.$country.'01';
$title = '';
if($country != 'US'){
	 $title = 'for ESPOW '.$country.' Products';
}

ob_start();
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
	<channel>
		<title>ESPOW - Online Store <?=$title?></title>
		<link>http://www.espow.com</link>
		<description>This is a product feed <?=$title?></description>
<?php
	while($info = tep_db_fetch_array($query)){
		$shopping_name = 'Standard Free Shipping';
		$product_cost = 0;
		if($info['products_free_shipping'] == '0'){
			$product_cost = get_shipping_cost_val($shipping_cost['flatRate'], $info['products_weight']);
			$shopping_name = 'Standard Flat Rate';
		}

		if($currency == 'USD'){
			$price = number_format($info['products_price'], 2, '.', '');
			$product_cost_str = $product_cost . ' '.$currency;
		}else{
			$price = format_number($currencies->format($info['products_price'], TRUE, $currency));
			$product_cost_str = $product_cost > 0 ? format_number($currencies->format($product_cost, TRUE, $currency)) : 0;
		}

		if(isset($info['code_or_model']) && $info['code_or_model']){
			$gid = $info['products_model'] . '-' . str_replace(' ','-',$info['code_or_model']).'-'.$info['categories_id'] . $id_subfix;
			$mpn = $gid . '-' . $info['categories_id'];
		}else{
			$gid = $info['products_model'].'-'.$info['categories_id'];
			$mpn = $gid . $info['categories_id'];
		}
?>
		<item> 
			<title><?=XmlSafeStr(str_replace('!','',$info['products_name']))?></title> 
			<link>http://www.espow.com/product_info.php?products_id=<?=$info['products_id']?>&amp;currency=<?=$currency?>&amp;gsc=googleshopping</link>
			<description>
				<?php 
					if(!empty($info['products_description'])){
						echo XmlSafeStr(replace_str($info['products_description']),true);
					}else{
						echo $info['products_name'];
					}
				?>
			</description>
		    <g:google_product_category><?=$google_product_category[$info['primary_id']]?></g:google_product_category>
			<g:id><?=$gid?></g:id>
			<g:condition>new</g:condition>
			<g:price><?=$price .' '. $currency?></g:price>
			<g:availability>in stock</g:availability>
			<g:image_link>http://www.espow.com/images/shopping/<?=$info['products_model']?>.JPG</g:image_link>
			<g:shipping>
				<g:country><?=$country?></g:country>
				<g:service><?=$shopping_name?></g:service>
				<g:price><?=$product_cost_str?></g:price>
			</g:shipping>
		<?php
			if($country != 'US'){
		?>
			<g:shipping_weight><?=$info['products_weight']?> grams</g:shipping_weight>
		<?php	
			}
		?>
			<g:brand><?='ESPOW'.$info['categories_id']?></g:brand>
			<g:mpn><?=$mpn?></g:mpn>
			<g:product_type><?=get_product_type($info['categories_id'])?><?//get_path($info['categories_id']);?></g:product_type>
		</item>
<?php
	}	
?>
	</channel>
</rss>
<?php
$cache_output = ob_get_contents();
ob_end_clean();
file_put_contents(FILE_OUT_PATH.$file_name.date('Ymd').'.xml', $cache_output);
?>