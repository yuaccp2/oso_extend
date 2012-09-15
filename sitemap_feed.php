<?php
//$start_time = time();
error_reporting(0);
@set_time_limit(0);

//数据库连接
define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
define('DB_SERVER_USERNAME', 'root');
define('DB_SERVER_PASSWORD', '820622');
define('DB_DATABASE', 'espowcom_espow3');
define('USE_PCONNECT', 'false'); // use persistent connections?
define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'
//常量
define('HTTP_SERVER', 'http://www.espow.com'); // eg, http://localhost/ - should not be empty for productive servers
define('DIR_WS_HTTP_CATALOG', '/');
define('FILENAME_PRODUCT_INFO', 'product_info.php');
define('FILENAME_DOWNLOADS_CONTROLLER', 'downloads_controller.php');
//导入的文件
require('database_tables.php');
require('classes/tree.php');
require('classes/categories.php');
require('classes/seo_url.php');
require('classes/sessions.php');
require('function/general.php');
require('function/html_output.php');
require('function/database.php');

tep_db_connect();
//初始化类
$g_seo_url = new seoURL();
$obj_categories = new categories();
//配置常量 
$config_sql = 'select * from '. TABLE_CONFIGURATION . ' where configuration_key LIKE "SEO_DEFAULT%"';
$config_query = tep_db_query($config_sql);
while($row = tep_db_fetch_array($config_query)){
	define($row['configuration_key'], $row['configuration_value']);
}

//demo
/*
$_SESSION['languages_code'] = 'fr';//en|fr|ru
$product_id = '5328';
$link = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_id, 'NONSSL', false);
echo $link;
*/
/*
		$seo_url = tep_href_link(FILENAME_RSS,  'cPath=' . $val['id'] . '&language=en');
		$seo_url = tep_href_link(FILENAME_FAQ, "cID=".$faq['faq_id']);
		$seo_url = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$products['products_id'],'NONSSL',false);
		$seo_url = tep_href_link(FILENAME_DEFAULT,tep_get_abscPath($top_categories['categories_id']),'NONSSL',false);
*/
//408目录下的都是电池产品
//	$id = 492;
//	$cates_str = getAllChildCates($id);
//	var_dump($cates_str);
/*
<url>
<loc>http://www.espow.com/cookie_usage.html</loc>
<lastmod>2010-05-13</lastmod>
<priority>0.5</priority>
<changefreq>always</changefreq>
</url>
*/
	//传入顶级分类id，递归得到所有的子分类id
	function getAllChildCates($cateId){
		$str = '';
		$sql = 'select distinct categories_id,last_modified from categories where categories.parent_id="'. $cateId .'" order by sort_order asc';
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()){
			while($row = mysql_fetch_assoc($result)){
				$str .= $row['categories_id'] . '@' . $row['last_modified'];
				$str .= ';';
				$str .= getAllChildCates($row['categories_id']);
			}
		}
		return $str;
	}

	function convertSymbolAnd($str){
		$newstr = preg_replace('/&/','%26',$str);
		return $newstr;
	}

	$languages_id = 1;

	$_SESSION['languages_code'] = 'en';

	$output_str = '';

	$file_name = 'sitemap_'. $_SESSION['languages_code'] .'.xml';
	$path = './file/';
	$full_path = $path . $file_name;
	$file_handle = fopen($full_path,'wb');
	flock($file_handle, LOCK_EX);
	$start_str = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$start_str .= '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">' . "\n";
	fwrite($file_handle, $start_str);

	//根分类及子分类只获取一次，并存储到数组中，在后面多次使用
	$get_root_cates_sql = 'select distinct categories_id,last_modified from categories where parent_id=0 order by sort_order asc';
	$get_root_cates_query = tep_db_query($get_root_cates_sql);
	$root_arr = array();
	while($row = tep_db_fetch_array($get_root_cates_query)){
		$root_cates_arr[] = $row;
		$sub_cates_arr[$row['categories_id']] = getAllChildCates($row['categories_id']);
	}

	//目录链接部分
	foreach($root_cates_arr as $root_cates_value){
		$last_modified = $root_cates_value['last_modified'];
		if(strtotime($last_modified)){
			$lastmod = date('Y-m-d',strtotime($last_modified));
		}else{
			$lastmod = date('Y-m-d',time());
		}
		$whole_str = '';
		$whole_str .= "<url>\n";
		$whole_str .= '<loc>' . convertSymbolAnd(tep_href_link('index.php',tep_get_abscPath($root_cates_value['categories_id']),'NONSSL',false)) . "</loc>\n";
		$whole_str .= '<lastmod>' . $lastmod . "</lastmod>\n";
		$whole_str .= "<priority>0.5</priority>\n";
		$whole_str .= "<changefreq>always</changefreq>\n";
		$whole_str .= "</url>\n";
		$output_str .= $whole_str;
	}
	foreach($sub_cates_arr as $sub_cates_value){
		$sub_arr = explode(';',$sub_cates_value);
		array_pop($sub_arr);
		foreach($sub_arr as $sub_value){
			list($sub_id,$sub_lastmod) = explode('@',$sub_value);
			if(strtotime($sub_lastmod)){
				$lastmod = date('Y-m-d',strtotime($sub_lastmod));
			}else{
				$lastmod = date('Y-m-d',time());
			}
			$whole_str = '';
			$whole_str .= "<url>\n";
			$whole_str .= '<loc>' . convertSymbolAnd(tep_href_link('index.php',tep_get_abscPath($sub_id),'NONSSL',false)) . "</loc>\n";
			$whole_str .= '<lastmod>' . $lastmod . "</lastmod>\n";
			$whole_str .= "<priority>0.5</priority>\n";
			$whole_str .= "<changefreq>always</changefreq>\n";
			$whole_str .= "</url>\n";
			$output_str .= $whole_str;
		}
	}
	//fwrite($file_handle, $output_str);
	//$output_str = '';

	//目录RSS链接部分
	foreach($root_cates_arr as $root_cates_value){
		$last_modified = $root_cates_value['last_modified'];
		if(strtotime($last_modified)){
			$lastmod = date('Y-m-d',strtotime($last_modified));
		}else{
			$lastmod = date('Y-m-d',time());
		}
		$whole_str = '';
		$whole_str .= "<url>\n";
		$whole_str .= '<loc>' . convertSymbolAnd(tep_href_link('rss.php', 'cPath=' . $root_cates_value['categories_id']. '&language=en')) . "</loc>\n";
		$whole_str .= '<lastmod>' . $lastmod . "</lastmod>\n";
		$whole_str .= "<priority>0.5</priority>\n";
		$whole_str .= "<changefreq>always</changefreq>\n";
		$whole_str .= "</url>\n";
		$output_str .= $whole_str;
	}
	foreach($sub_cates_arr as $sub_cates_value){
		$sub_arr = explode(';',$sub_cates_value);
		array_pop($sub_arr);
		foreach($sub_arr as $sub_value){
			list($sub_id,$sub_lastmod) = explode('@',$sub_value);
			if(strtotime($sub_lastmod)){
				$lastmod = date('Y-m-d',strtotime($sub_lastmod));
			}else{
				$lastmod = date('Y-m-d',time());
			}
			$whole_str = '';
			$whole_str .= "<url>\n";
			$whole_str .= '<loc>' . convertSymbolAnd(tep_href_link('rss.php', 'cPath=' . $sub_id. '&language=en')) . "</loc>\n";
			$whole_str .= '<lastmod>' . $lastmod . "</lastmod>\n";
			$whole_str .= "<priority>0.5</priority>\n";
			$whole_str .= "<changefreq>always</changefreq>\n";
			$whole_str .= "</url>\n";
			$output_str .= $whole_str;
		}
	}
	//fwrite($file_handle, $output_str);
	//$output_str = '';

	//产品链接部分
	foreach($root_cates_arr as $root_cates_value){
		if($root_cates_value['categories_id'] != '408'){
			//找出根分类下的产品
			$get_products_sql = 'select distinct products.products_id,products.products_last_modified from products,products_to_categories where products.products_id=products_to_categories.products_id and products_to_categories.categories_id="' . $root_cates_value['categories_id'] . '"';
			$get_products_query = tep_db_query($get_products_sql);
			while($pro_row = tep_db_fetch_array($get_products_query)){
				$last_modified = $pro_row['products_last_modified'];
				if(strtotime($last_modified)){
					$lastmod = date('Y-m-d',strtotime($last_modified));
				}else{
					$lastmod = date('Y-m-d',time());
				}
				$whole_str = '';
				$whole_str .= "<url>\n";
				$whole_str .= '<loc>' . convertSymbolAnd(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$pro_row['products_id'],'NONSSL',false)) . "</loc>\n";
				$whole_str .= '<lastmod>' . $lastmod . "</lastmod>\n";
				$whole_str .= "<priority>0.4</priority>\n";
				$whole_str .= "<changefreq>always</changefreq>\n";
				$whole_str .= "</url>\n";
				$output_str .= $whole_str;
			}
			//找出对应子分类下的产品
			$sub_arr = explode(';',$sub_cates_arr[$root_cates_value['categories_id']]);
			array_pop($sub_arr);
			foreach($sub_arr as $sub_value){
				list($sub_id,$sub_lastmod) = explode('@',$sub_value);
				$get_products_sql = 'select distinct products.products_id,products.products_last_modified from products,products_to_categories where products.products_id=products_to_categories.products_id and products_to_categories.categories_id="' . $sub_id . '"';
				$get_products_query = tep_db_query($get_products_sql);
				while($pro_row = tep_db_fetch_array($get_products_query)){
					$last_modified = $pro_row['products_last_modified'];
					if(strtotime($last_modified)){
						$lastmod = date('Y-m-d',strtotime($last_modified));
					}else{
						$lastmod = date('Y-m-d',time());
					}
					$whole_str = '';
					$whole_str .= "<url>\n";
					$whole_str .= '<loc>' . convertSymbolAnd(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$pro_row['products_id'],'NONSSL',false)) . "</loc>\n";
					$whole_str .= '<lastmod>' . $lastmod . "</lastmod>\n";
					$whole_str .= "<priority>0.4</priority>\n";
					$whole_str .= "<changefreq>always</changefreq>\n";
					$whole_str .= "</url>\n";
					$output_str .= $whole_str;
				}
			}
		}
	}
	//fwrite($file_handle, $output_str);
	//$output_str = '';

	//电池产品部分
	//找出根分类下的产品
	$get_products_sql = 'select distinct products.products_id,products.products_last_modified from products,products_to_categories where products.products_id=products_to_categories.products_id and products_to_categories.categories_id="408"';
	$get_products_query = tep_db_query($get_products_sql);
	while($pro_row = tep_db_fetch_array($get_products_query)){
		$last_modify = $pro_row['products_last_modified'];
		if(strtotime($last_modify)){
			$lastmod = date('Y-m-d',strtotime($last_modify));
		}else{
			$lastmod = date('Y-m-d',time());
		}
		$whole_str = '';
		$whole_str .= "<url>\n";
		$whole_str .= '<loc>' . convertSymbolAnd(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$pro_row['products_id'],'NONSSL',false)) . "</loc>\n";
		$whole_str .= '<lastmod>' . $lastmod . "</lastmod>\n";
		$whole_str .= "<priority>0.4</priority>\n";
		$whole_str .= "<changefreq>always</changefreq>\n";
		$whole_str .= "</url>\n";
		$output_str .= $whole_str;
	}
	//找出对应子分类下的产品
	$sub_arr = explode(';',$sub_cates_arr['408']);
	array_pop($sub_arr);
	foreach($sub_arr as $sub_value){
		list($sub_id,$sub_lastmod) = explode('@',$sub_value);
		$get_products_sql = 'select distinct products.products_id,products.products_last_modified from products,products_to_categories where products.products_id=products_to_categories.products_id and products_to_categories.categories_id="' . $sub_id . '"';
		$get_products_query = tep_db_query($get_products_sql);
		while($pro_row = tep_db_fetch_array($get_products_query)){
			$last_modified = $pro_row['products_last_modified'];
			if(strtotime($last_modified)){
				$lastmod = date('Y-m-d',strtotime($last_modified));
			}else{
				$lastmod = date('Y-m-d',time());
			}
			$whole_str = '';
			$whole_str .= "<url>\n";
			$whole_str .= '<loc>' . convertSymbolAnd(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$pro_row['products_id'],'NONSSL',false)) . "</loc>\n";
			$whole_str .= '<lastmod>' . $lastmod . "</lastmod>\n";
			$whole_str .= "<priority>0.4</priority>\n";
			$whole_str .= "<changefreq>always</changefreq>\n";
			$whole_str .= "</url>\n";
			$output_str .= $whole_str;
		}
	}
	fwrite($file_handle, $output_str);
	$output_str = '';

	$end_str = '</urlset>';
	fwrite($file_handle, $end_str);

	flock($file_handle, LOCK_UN);
	fclose($file_handle);

	//$end_time = time();
	//$spend_time = $end_time - $start_time;
	//echo $spend_time;
?>