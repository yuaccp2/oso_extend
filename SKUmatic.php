<?php
	require("init.php");

/**
* 过滤XML不支持的字符
* @authoer nathan 
* @access public 
* @param $str string 字符串
* @param $strip_html 过滤HTML标签 默认:false
* @return 
*/
function XmlSafeStr($str, $strip_html = false)
{
	$str = utf8_encode($str);
	if($strip_html) $str = strip_tags($str);
	$cn_symbol = array(
	'`', '－', '＝', '、', '；', '‘', '’', '，', '。', '、', '~', '！', '·', '#', '￥', '%', '……', '—', '*', '（', '）', '——', '+', '｜', '：', '“', '”', '《', '》', '？');
	$en_symbol = array('`', '-', '=', '\'', ';', '\'', '\'', ',', '.', '/', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', '|', ':', '"', '"', '<', '>', '?');
	$str = str_replace($cn_symbol, $en_symbol,$str);

	$SBC_case = array('｀', '－', '＝', '＼', '；', '，', '．', '／', '～', '！', '＠', '＃', '＄', '％', '＾', '＆', '＊', '（', '）', '＿', '＋', '｜', '：', '＜', '＞', '？');
	$DBC_case = array('`', '-', '=', '\'', ';', ',', '.', '/', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', '|', ':', '<', '>', '?');
	$str = str_replace($SBC_case, $DBC_case,$str);

	$search = array('～','°','±','c㎡','',"\t");
	$replace = array('~','','','cm','',' ');
	$str = str_replace($search, $replace,$str);
	$str = preg_replace("/[\x{0080}-\x{00FF}]+/iu"," ", $str); 
	//$str = preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/",'',$str);
	$str = htmlentities($str);
	return $str;
}

$categories_name = array();
$categories_top_sql = 'SELECT 	categories_id, categories_name FROM categories
						LEFT JOIN categories_description USING(categories_id)
						WHERE language_id = 1';
$categories_top_query = tep_db_query($categories_top_sql);
while($c_info = tep_db_fetch_array($categories_top_query)){
	$categories_name[$c_info['categories_id']] = $c_info['categories_name'];
}

/**
* 
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function get_category_pathstr($cid, $separator){
	global $categories_name,$obj_categories;
	//目录名称
	if(!isset($categories_name[$cid])) return '';
	$categories_name_str = XmlSafeStr($categories_name[$cid]);
	$cates_ids = $obj_categories->get_parents($cid);

	if($cates_ids){
		$cates_ids [] = $cid;
		$_tmp_name = array();
		foreach ($cates_ids as $key => $val){
			$_tmp_name[] = $categories_name[$val];
		}
		$categories_name_str = join($separator,$_tmp_name);
	}
	return $categories_name_str;
}


	$sql = 'SELECT products_id, categories_id,products_name, products_head_keywords_tag, products_head_desc_tag, products_model, products_price, products_discount FROM products
LEFT JOIN products_description USING(products_id)
LEFT JOIN products_to_categories USING(products_id)
WHERE language_id=1 AND products_status = 1 AND products_quantity > 0
GROUP BY products_model';
if($debug){
	$sql .= ' limit 0,3';
}
$output_str = '';
$query = tep_db_query($sql);
while($rows = tep_db_fetch_array($query)){
	//NAME|KEYWORDS|DESCRIPTION|SKU|BUYURL|AVAILABLE|IMAGEURL|PRICE|ADVERTISERCATEGORY|CONDITION
	$name = XmlSafeStr($rows['products_name']);
	$keyword = $rows['products_head_keywords_tag'] ? XmlSafeStr($rows['products_head_keywords_tag']) : $name;
	$desc = $rows['products_head_keywords_tag'] ? XmlSafeStr($rows['products_head_keywords_tag']) : $name;
	$model = $rows['products_model'];
	$image = HTTP_SERVER . '/images/' .tep_get_products_image_str($model,'medium');
	$url = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$rows['products_id'],'NONSSL',false);
	$price = tep_round($rows['products_price'],2);
	$category_str = get_category_pathstr($rows['categories_id'],'>');
	$output_str .= "{$name}\t{$keyword}\t{$desc}\t{$model}\t{$url}\tYES\t{$image}\t{$price}\t{$category_str}\t\n";
}
if($debug){
	echo '<pre>'.$output_str.'</pre>';
}else{
	file_put_contents('file/SKUmatic_'.date('YmdHis').'.txt',$output_str);
}
echo time() - $start_time;
?>