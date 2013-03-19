<?php
$start_time = time();
//error_reporting(0);
$debug = false;
if($_SERVER['HTTP_HOST'] == 'web.com'){//本地为测试模式
	$debug = true;
}
@set_time_limit(0);

//数据库连接
define('DB_SERVER', '192.168.0.22'); // eg, localhost - should not be empty for productive servers
define('DB_SERVER_USERNAME', 'espow');
define('DB_SERVER_PASSWORD', 'dbuser');
define('DB_DATABASE', 'espowcom_espow3');
define('USE_PCONNECT', 'false'); // use persistent connections?
define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'
//常量
define('HTTP_SERVER', 'http://www.espow.com'); // eg, http://localhost/ - should not be empty for productive servers
define('DIR_WS_HTTP_CATALOG', '/');
define('FILENAME_PRODUCT_INFO', 'product_info.php');
define('FILENAME_DOWNLOADS_CONTROLLER', 'downloads_controller.php');
define('PRODUCTS_RATE','1.3');
//导入的文件
require('database_tables.php');
require('classes/tree.php');
require('classes/categories.php');
require('classes/seo_url.php');
require('classes/sessions.php');
require('classes/PriceFormatter.php');
require('classes/currencies.php');
require('function/general.php');
require('function/html_output.php');
require('function/database.php');

/*
$pf = new PriceFormatter;
$pf->loadProduct($product_info['products_id'],$languages_id);

$retail_price = $product_info['products_discount'] > 0 ?number_format((1/(1-$product_info['products_discount']/100)),2,'.','') : PRODUCTS_RATE;
if ( $product_info['products_type'] != 'A' ){
$pf->getRetailSinglePrice($retail_price);
}
$pf->getSinglePrice();
*/
tep_db_connect();
//初始化类
$g_seo_url = new seoURL();
$obj_categories = new categories();
$pf = new PriceFormatter;
$currencies = new currencies;
$currency = 'USD';
//配置常量 
$config_sql = 'select * from '. TABLE_CONFIGURATION . ' where configuration_key LIKE "SEO_DEFAULT%"';
$config_query = tep_db_query($config_sql);
while($row = tep_db_fetch_array($config_query)){
	define($row['configuration_key'], $row['configuration_value']);
}

$languages_id = 1;

$_SESSION['languages_code'] = 'en';

$output_str = '';
$output_str .= '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" . '<product_list>' . "\n";

function convertSpecificChars($str){
	$newstr = str_replace(array('&nbsp;','&deg;','&','<','>',"'",'"',), array(' ','°','&amp;','&lt;','&gt;','&apos;','&quot;'), $str);
	return $newstr;
}

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

	$search = array('～','°','±','c㎡','');
	$replace = array('~','','','cm','');
	$str = str_replace($search, $replace,$str);
	$str = preg_replace("/[\x{0080}-\x{00FF}]+/iu"," ", $str); 
	//$str = preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/",'',$str);
	$str = htmlentities($str);
	return $str;
}

/**
* 
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function get_all_currencies_price($price, $separator = null){
	global $currencies;
	$currency_all_val = array();
	$currency_type = array("USD","EUR","GBP","CAD","AUD","RUB");
	foreach ($currency_type as $key => $val){
		$currency_all_val[] = $currencies->format($price, true, $val);
	}
	if($separator){
		return join($separator, $currency_all_val);
	}
	return $currency_all_val;
}

$categories_name = array();
$categories_top_sql = 'SELECT 	categories_id, categories_name FROM categories
						LEFT JOIN categories_description USING(categories_id)
						WHERE language_id = 1';
$categories_top_query = tep_db_query($categories_top_sql);
while($c_info = tep_db_fetch_array($categories_top_query)){
	$categories_name[$c_info['categories_id']] = $c_info['categories_name'];
}

$where = '';
if($debug){
	$where = ' and products_model = "ECARDVR83" ';
}

$get_products_sql = 'SELECT DISTINCT p.products_id, cd.categories_id, pd.products_name, p.products_model, pd.products_description, p.products_image, cd.categories_name, p.products_price, p.products_status, p.products_new, p.products_discount, p.products_type, p.products_quantity, products_free_shipping, products_discount
FROM products AS p, products_description AS pd, products_to_categories AS ptc, categories_description AS cd 
WHERE p.products_id = pd.products_id AND p.products_id = ptc.products_id AND ptc.categories_id = cd.categories_id AND pd.language_id ="1" AND cd.language_id ="1" '. $where .'
GROUP BY p.products_model 
ORDER BY p.products_model';
if($debug){
	$get_products_sql .= ' limit 0,1';
}
$get_products_query = tep_db_query($get_products_sql);
while($row = tep_db_fetch_array($get_products_query)){
	$pro_str = '';
	$pro_str .= '<product>' . "\n";
	$products_name = XmlSafeStr($row['products_name']);
	$pro_str .= '<name>' . $products_name . '</name>' . "\n";
	$pro_str .= '<url>'. tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$row['products_id'],'NONSSL',false) .'</url>' . "\n";
	$pro_str .= '<id>' . $row['products_id'] . '</id>' . "\n";
	$pro_str .= '<sku>' . $row['products_model'] . '</sku>' . "\n";
	$pro_str .= '<free>' . ($row['products_free_shipping'] > 0 ? '1' : '0') . '</free>' . "\n";
	$pro_str .= '<discount>' . $row['products_discount'] . '</discount>' . "\n";
	$long_desc = strip_tags($row['products_description']);
	$long_desc = XmlSafeStr($long_desc);
	$short_desc = tep_clipped_string($long_desc,$needle=' ',$strlen='110');
	$pro_str .= '<short_desc>' . $short_desc . '</short_desc>' . "\n";
	$pro_str .= '<long_desc>' . $long_desc . '</long_desc>' . "\n";
	$pro_str .= '<image>http://www.espow.com/images/' . $row['products_image'] . '</image>' . "\n";

	//目录名称
	$categories_name_str = XmlSafeStr($row['categories_name']);
	$pro_str .= '<category>' . $categories_name_str . '</category>' . "\n";
	$cates_ids = $obj_categories->get_parents($row['categories_id']);

	if($cates_ids){
		$cates_ids [] = $row['categories_id'];
		$_tmp_name = array();
		foreach ($cates_ids as $key => $val){
			$_tmp_name[] = $categories_name[$val];
		}
		$categories_name_str = join(' | ',$_tmp_name);
	}
	$pro_str .= '<multi_category>' . XmlSafeStr($categories_name_str) . '</multi_category>' . "\n";

	//price number_format
	$pf->loadProduct($row['products_id']);
	$rate = $row['products_discount'] > 0 ? number_format((1/(1-$row['products_discount']/100)),2,'.','') : PRODUCTS_RATE;
	$retail_price = $pf->getRetailSinglePrice($rate);
	$out_price = $pf->getSinglePrice();

	$pro_str .= '<price>' . $out_price . '</price>' . "\n";
	$pro_str .= '<saleprice>' . $retail_price . '</saleprice>' . "\n";

	$price_val = $pf->getSinglePriceValue();
	$rate_price = tep_add_tax($price_val * $rate, 0);
	$out_price = get_all_currencies_price($price_val, ' | ');
	$retail_price = get_all_currencies_price($rate_price, ' | ');
	$pro_str .= '<multi_price>' . $out_price . '</multi_price>' . "\n";
	$pro_str .= '<multi_saleprice>' . $retail_price . '</multi_saleprice>' . "\n";

	$pro_str .= '<availability>' . (($row['products_status'] ==1 && $row['products_quantity'] > 0) ? '1' : '0') . '</availability>' . "\n";
	$pro_str .= '<new>' . $row['products_new'] . '</new>' . "\n";
	//查询产品属性信息
	$get_products_attr_sql = 'SELECT DISTINCT pa.products_model, pa.options_id, pa.options_values_id, pot.products_options_name, povt.options_value_name FROM products_attributes AS pa, products_options_text AS pot, products_options_value_text AS povt WHERE pa.options_id = pot.products_options_text_id AND pa.options_values_id = povt.options_value_id AND pot.language_id =1 AND povt.language_id =1 AND pa.products_model = "' . $row['products_model'] . '" ORDER BY pot.products_options_name';
	$get_products_attr_query = tep_db_query($get_products_attr_sql);
	$attr_arr = array();
	$pro_attr_str = '';
	while($attr_row = tep_db_fetch_array($get_products_attr_query)){
			if(empty($attr_row['products_options_name']) || empty($attr_row['options_value_name'])) continue;

			$attr_arr[$attr_row['products_options_name']][] = $attr_row['options_value_name'];
	}
	if(!empty($attr_arr)){
		$attr_value = '';
		foreach($attr_arr as $_option => $_attrs){
			$attr_value = join('|', $_attrs);
			$_option = XmlSafeStr(str_replace(' ', '_', $_option));
			$pro_attr_str .= '<'.$_option.'>' . XmlSafeStr($attr_value) . '</'.$_option.'>' . "\n";
		}
	}
	$output_str .= $pro_str;
	$output_str .= $pro_attr_str;
	$output_str .= '</product>' . "\n";
}
	$output_str .= '</product_list>';
if($debug){
	echo $get_products_attr_sql.'<br/>';
	echo $output_str;
	file_put_contents('file/debug_sli_search_feed'.date('Ymd').'.xml',$output_str);
}else{
	file_put_contents('file/sli_search_feed'.date('YmdHis').'.xml',$output_str);
}
echo '<br/>------------------------------------------------<br/>';
echo time() - $start_time .'sec';
?>