<?php
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
	'`', '－', '＝', '、', '；', '‘', '', '’，', '。', '、', '~', '！', '·', '#', '￥', '%', '……', '—', '*', '（', '）', '——', '+', '｜', '：', '“', '”', '《', '》', '？');
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
	$str = xmlEntities(htmlentities($str));
	return $str;
} 
/**
* 
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function xmlEntities($str){
	$xml = array('&#34;','&#38;','&#38;','&#60;','&#62;','&#160;','&#161;','&#162;','&#163;','&#164;','&#165;','&#166;','&#167;','&#168;','&#169;','&#170;','&#171;','&#172;','&#173;','&#174;','&#175;','&#176;','&#177;','&#178;','&#179;','&#180;','&#181;','&#182;','&#183;','&#184;','&#185;','&#186;','&#187;','&#188;','&#189;','&#190;','&#191;','&#192;','&#193;','&#194;','&#195;','&#196;','&#197;','&#198;','&#199;','&#200;','&#201;','&#202;','&#203;','&#204;','&#205;','&#206;','&#207;','&#208;','&#209;','&#210;','&#211;','&#212;','&#213;','&#214;','&#215;','&#216;','&#217;','&#218;','&#219;','&#220;','&#221;','&#222;','&#223;','&#224;','&#225;','&#226;','&#227;','&#228;','&#229;','&#230;','&#231;','&#232;','&#233;','&#234;','&#235;','&#236;','&#237;','&#238;','&#239;','&#240;','&#241;','&#242;','&#243;','&#244;','&#245;','&#246;','&#247;','&#248;','&#249;','&#250;','&#251;','&#252;','&#253;','&#254;','&#255;');
    $html = array('&quot;','&amp;','&amp;','&lt;','&gt;','&nbsp;','&iexcl;','&cent;','&pound;','&curren;','&yen;','&brvbar;','&sect;','&uml;','&copy;','&ordf;','&laquo;','&not;','&shy;','&reg;','&macr;','&deg;','&plusmn;','&sup2;','&sup3;','&acute;','&micro;','&para;','&middot;','&cedil;','&sup1;','&ordm;','&raquo;','&frac14;','&frac12;','&frac34;','&iquest;','&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&AElig;','&Ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;','&Igrave;','&Iacute;','&Icirc;','&Iuml;','&ETH;','&Ntilde;','&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;','&times;','&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;','&Yacute;','&THORN;','&szlig;','&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&aelig;','&ccedil;','&egrave;','&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&eth;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&divide;','&oslash;','&ugrave;','&uacute;','&ucirc;','&uuml;','&yacute;','&thorn;','&yuml;');
    //$str = str_replace($html,$xml,$str);
    $str = str_ireplace($html,$xml,$str);
	return $str;
}
/**
* 过滤单词
* @authoer nathan 
* @access public 
* @param String	$str 内容
* @return String
*/
function replace_str($str){
	//espow, free shpping, discount
	$search_arr = array('espow','free shpping','discount');
	$replace_arr = array_fill(0,count($search_arr),'');
	return str_ireplace($search_arr, $replace_arr, $str);
}

/**
* 取单词的首字母
* @authoer nathan 
* @access public 
* @param $name string 字符串
* @return 
*/
function cut_frist($name){
	$name_arr = explode(' ',$name);
	$str = '';
	foreach ($name_arr as $key => $val){
		$str.=ucwords(substr($val, 0, 1));
	}
	return $str;
}
/**
* 取分类的级别路径
* @authoer nathan 
* @access public 
* @param $sub_categories	int 分类ID
* @param $mark				string 分隔符号
* @return String
*/
function get_path($sub_categories, $mark = '&gt;'){
	global $obj_categories;
	if(empty($sub_categories)) return 'NAN';
	if($obj_categories->get_parent($sub_categories)){
		$parents_id = $obj_categories->get_parents($sub_categories);
		$ids_str = join(',', $parents_id);
		$csql = 'select categories_name from '. TABLE_CATEGORIES_DESCRIPTION .' where categories_id in ('. $ids_str .')  and language_id = 1 order by field(categories_id,'.$ids_str.')';
		$c_query = tep_db_query($csql);
		$name_arr = array();
		while($row = tep_db_fetch_array($c_query)){
			$name_arr[] = $row['categories_name'];
		}
		return XmlSafeStr(join(' '.$mark.' ',$name_arr));
	}
	 return 'NAN';
}
/**
* 获取指定的目录信息
* @authoer nathan 
* @access public 
* @param Array $category_arr 指定的目录信息
* @param id	   $categories_id 目录ID
* @return Array
*/
//$config_arr,$info['categories_id']
function get_category_config($category_arr , $categories_id){
	global $obj_categories;
	$category = '';
	foreach ($category_arr as $key => $val){
		$sub_ids = $obj_categories->get_subcategories($key);
		if($key == $categories_id || in_array($categories_id, $sub_ids)){
			$category = $val;
		}
	}
	return $category;
}
/**
* 转换为数字
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function format_number($str){
	preg_match('[\d+\.\d+]', $str,$result);
	if(isset($result[0])) return $result[0];
	return 'NAN';
}
/**
* 获取运费价格表
* @authoer nathan 
* @access public 
* @param String	$cost_str 运费重量价格串
* @return Array
*/
function get_shipping_cost($cost_str){
	$tem_arr = split('[:,]',$cost_str);
	$tem_length = count($tem_arr);
	$shipping_cost = array();
	for ($i=0; $i<$tem_length; $i+=2){
		$shipping_cost[] = array('weight'=>$tem_arr[$i],'cost'=>$tem_arr[$i+1]);
	}
	return $shipping_cost;
}
/**
* 获取指定重量的运费
* @authoer nathan 
* @access public 
* @param Array $shipping_cost 运费价格表
* @param Int $products_weight 产品重量
* @return float
*/
function get_shipping_cost_val($shipping_cost, $products_weight){
	$product_cost = 'NAN';
	if($products_weight && is_array($shipping_cost) && $shipping_cost){
		foreach ($shipping_cost as $key => $val){
			if($val['weight'] >= $products_weight){
				$product_cost = $val['cost'];
				break;
			}
		}
	}
	return $product_cost;
}
/**
* 
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function get_products_model($info, $tag){
	$model = $info['products_model'];
	if($info['code_or_model']) $model.='-'.$info['code_or_model'];
	return $model .'-'.$tag;
}

/**
* AD广告关键字
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function get_product_type($category_id){
	global $obj_categories;
	$brand_parent_category =  array(21,73,1,314,3);//子目录属于品牌名称，故取上级目录名称
	$cid = $obj_categories->get_parent($category_id);
	if($cid && in_array($cid, $brand_parent_category)){
		$category_id = $cid;
	}
	$name = $obj_categories->get_name($category_id);
	return htmlentities($name);
}
/*----------------------------------------end 函数--------------------------------------------------------------*/

header('Content-type:text/html;charset=utf-8');
include('init.php');
$debug = false;

	$startTime = microtime(true);

	//初始化类
	$g_seo_url = new seoURL();
	$obj_categories = new categories();
	$currencies = new currencies();
	$pf = new PriceFormatter;
	$obj_categories = new categories();

	//获取全部目录产品，除jammer
    $categories_all_flag = false;

	//入口
	$country_code = 'CA';//


	switch($country_code){
		case 'CA':
			$categories_ids = array(492,332,343,347,329,880,408,335,350);
			$select_country = array('CA');
			$languages_id = 1;
			break;
		case 'DE':
			$categories_ids = array('842','409','688','340','742','414','574','581','580','707','770');//de
			$languages_id = 5;
			$select_country =array('DE');
			break;
		case 'FR':
			$categories_ids = array('409','688','340','733','372','770','444','748','412','842','574');//fr
			$languages_id = 3;
			$select_country =array('FR');
			break;
		default:
			$select_country = array('US','AU','UK');
			$languages_id = 1;
			$categories_all_flag = true;
			break;
	}


	//运费价格
	$config_sql = 'select * from '. TABLE_CONFIGURATION . ' where configuration_key like "MODULE_SHIPPING%" or configuration_key LIKE "SEO_DEFAULT%"';
	$config_query = tep_db_query($config_sql);
	while($row = tep_db_fetch_array($config_query)){
		define($row['configuration_key'], $row['configuration_value']);
	}
	/*
		US UPS,USPS
		UK reg,spec
		AU fedex(Hong Kong Post)
	*/
	$shipping_cost = Array();
	$shipping_cost['ups'] = get_shipping_cost(MODULE_SHIPPING_ZONES_UPS_COST_1);//联合包裹
	$shipping_cost['flatRate'] = get_shipping_cost(MODULE_SHIPPING_ZONES_FEDEX_COST_1);//联邦 平邮Standard Flat Rate
	$shipping_cost['usps'] = get_shipping_cost(MODULE_SHIPPING_ZONES_USPS_COST_1);//美国邮政
	$shipping_cost['reg'] = get_shipping_cost(MODULE_SHIPPING_REG_COST_1);
	$shipping_cost['spec'] = get_shipping_cost(MODULE_SHIPPING_SPEC_COST_1);

	/**
	* POST 数据处理
	*/
	if($categories_all_flag){
		$categories_id_arr = array();
		//多个目录ID 逗号隔开
		$category_exclude_id = array(681,702,645);
		foreach ($category_exclude_id as $key => $val){
			if(empty($val)) continue;
			$ctmp = $obj_categories->get_subcategories($val);
			$ctmp[] = $val;
			$categories_id_arr = array_merge($ctmp, $categories_id_arr);
		}

		if(empty($categories_id_arr)){
			echo 'Categori id is empty<br/>';
			ajax_die();
		}


		$categories_id_str = join('","', $categories_id_arr);
		$sql = 'select p.*, products_name, products_description,c.categories_id,primary_id from '. TABLE_PRODUCTS . ' p
				left join '. TABLE_PRODUCTS_DESCRIPTION .' pd using(products_id) 
				left join '. TABLE_PRODUCTS_TO_CATEGORIES . ' ptc on p.products_id = ptc.products_id 
				left join '. TABLE_CATEGORIES . ' c on c.categories_id = ptc.categories_id 
				where c.categories_id not in ("'.$categories_id_str.'") and products_quantity > 0 and products_status = 1 and products_price > 0 and pd.language_id = '.$languages_id.' and products_model not like "EPJAMMER%"';
	//$sql .= ' group by products_model,code_or_model';
	}else{
		$categories_id_arr = array();
		foreach ($categories_ids as $key => $val){
			if(empty($val)) continue;
			$ctmp = $obj_categories->get_subcategories($val);
			$ctmp[] = $val;
			$categories_id_arr = array_merge($ctmp, $categories_id_arr);
		}

		if(empty($categories_id_arr)){
			echo 'Categori id is empty<br/>';
			ajax_die();
		}

		$categories_id_str = join('","', $categories_id_arr);
		$sql = 'select p.*, products_name, products_description,c.categories_id,primary_id from '. TABLE_PRODUCTS . ' p
				left join '. TABLE_PRODUCTS_DESCRIPTION .' pd using(products_id) 
				left join '. TABLE_PRODUCTS_TO_CATEGORIES . ' ptc on p.products_id = ptc.products_id 
				left join '. TABLE_CATEGORIES . ' c on c.categories_id = ptc.categories_id 
				where c.categories_id in ("'.$categories_id_str.'") and products_quantity > 0 and products_status = 1 and products_price > 0 and pd.language_id = '.$languages_id.' and products_model not like "EPJAMMER%"';
	}

	if($debug) $sql.= ' and p.products_id in (1300,56377,58815,62338)';
	$query = tep_db_query($sql);
	//echo $sql;die();

	/**
	* 目录资料 取顶级目录资料
	*/
	$google_product_category = array(
							'332'=>'Electronics &gt; Computers',
							'343'=>'Vehicles &amp; Parts',
							'347'=>'Home &amp; Garden &gt; Home Security',
							'329'=>'Home &amp; Garden',
							'350'=>'Sporting Goods &gt; Outdoor Recreation',
							'408'=>'Electronics &gt; Electronics Accessories &gt; Power &gt; Chargers &gt; Solar Chargers',
							'492'=>'Electronics &gt; Computers',
							'681'=>'Cameras &amp; Optics &gt; Camera &amp; Optic Accessories &gt; Camera &amp; Optic Lens Accessories',
							'902'=>'Health &amp; Beauty'
							);
    
	//按国家分类
	$country_arr = array(
			'US' => array('currency'=>'USD','country'=>'US'),
			'AU' => array('currency'=>'AUD','country'=>'AU'),
			'UK' => array('currency'=>'GBP' ,'country'=>'UK'),
			'FR' => array('currency'=>'EUR' ,'country'=>'FR'),
			'DE' => array('currency'=>'EUR' ,'country'=>'DE'),
			'CA' => array('currency'=>'CAD' ,'country'=>'CA')
	);

	$languages_id = 1;//目录取英文资料

    foreach ($select_country as $key => $val){
		$currency = $country_arr[$val]['currency'];
		$country = $country_arr[$val]['country'];
		if($categories_all_flag){
			$file_name = 'ALLProduct'.$country.'Feed_';
		}else{
			$file_name = 'Product'.$country.'Feed_'.join(',', $categories_ids).'_';
		}
		include(GOOGLE_TEMPLATE_PATH.'merchant_feed.php');

		mysql_data_seek($query, 0);
    }

	tep_db_close();
	$costTime = microtime(true)-$startTime;
	//header('location:./google_merchant_feed.php?time='.$costTime);exit();
	echo $costTime / 60;
 ?>
