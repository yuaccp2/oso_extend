<?php
$start_time = time();
//error_reporting(0);
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

$debug = $_SERVER['HTTP_HOST'] == 'web.com' ? true : false;
?>