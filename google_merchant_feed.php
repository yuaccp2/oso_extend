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
function get_product_type($category_id, $country){
	global $obj_categories,$languages_id;
	$languages_id = 1;
	$brand_parent_category =  array(21,73,1,314,3);//子目录属于品牌名称，故取上级目录名称
	$cid = $obj_categories->get_parent($category_id);
	if($cid && in_array($cid, $brand_parent_category)){
		$category_id = $cid;
	}
	$name = $obj_categories->get_name($category_id);
	return htmlentities($name).' '.$country;
}
/**
* 获取目录ID数组
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function get_subcategories($category_ids){
	global $obj_categories;
	if(!is_array($category_ids)) $category_ids = array($category_ids);
	$categories_id_arr = array();
	foreach ($category_ids as $key => $val){
		if(empty($val)) continue;
		$ctmp = $obj_categories->get_subcategories($val);
		$categories_id_arr = array_merge($ctmp, $categories_id_arr);
	}
	return $categories_id_arr;
}
/**
* 
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function get_google_category($category, $cid, $prid = 0){
	if(isset($category[$cid]) && $category[$cid]) return $category[$cid];
	if($prid && isset($category[$prid])) return $category[$prid];
	global $obj_categories;
	$parents_arr = $obj_categories->get_parent_all($cid);
	foreach ($category as $key => $val){
		if(in_array($key, $parents_arr)){
			return $val;
		}
	}
	err::set_err('google category is not exist ->'.$cid.'  '.json_encode($parents_arr));
	return '';
}
/**
* 匹配输出
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function match_output($val, $match, $str){
	if(!is_array($match)) $match = array($match);
	if(in_array($val, $match)) return $str;
	return '';
}
class err{
	private static $err = array();
	static function set_err($val){
		self::$err[] = $val;
	}
	static function get_err(){
		return self::$err;
	}
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

	$where_arr = array();//条件
	//入口
	$country_code = 'FR';//

	switch($country_code){
		case 'US':
		case 'UK':
			$select_country = array('US','UK');
			$languages_id = 1;
			break;
		case 'AU':
		case 'CA':
			$select_country = array('AU','CA');
			$where_arr[] = 'code_or_model =""';
			$languages_id = 1;
			break;
		case 'DE':
			$categories_ids = array('842','409','688','340','742','414','574','581','580','707','770');//de
			$languages_id = 5;
			$select_country =array('DE');
			break;
		case 'ES':
			$categories_ids = get_subcategories(array(523,340,688,742,442,372,770,409,734,444,842,412,748,574,687,3));
			$where_arr[] = 'c.categories_id in ('.join(',', $categories_ids).')';
			$select_country =array('ES');
			$languages_id = 4;
			break;
		case 'FR':
			$categories_ids = get_subcategories(array(523,409,688,340,733,372,770,444,748,412,842,574,3));
			$where_arr[] = 'c.categories_id in ('.join(',', $categories_ids).')';
			$select_country =array('FR');
			$languages_id = 3;
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

	$where = '';
	if($where_arr){
		$where = ' and '.join(' and ', $where_arr);
	}
	$sql = 'select p.*, products_name, products_description,c.categories_id,primary_id from '. TABLE_PRODUCTS . ' p
			left join '. TABLE_PRODUCTS_DESCRIPTION .' pd using(products_id) 
			left join '. TABLE_PRODUCTS_TO_CATEGORIES . ' ptc on p.products_id = ptc.products_id 
			left join '. TABLE_CATEGORIES . ' c on c.categories_id = ptc.categories_id 
			where products_quantity > 0 and products_status = 1 and products_price > 0 and pd.language_id = '.$languages_id.' and products_model not like "EPJAMMER%"'.$where;

	if($debug) $sql.= ' and p.products_id in (1300,56377,58815,62338)';
	$query = tep_db_query($sql);
	//echo $sql;die();

	/**
	* 目录资料 取顶级目录资料
	*/
	if($languages_id == 2){
	}elseif($languages_id == 3){
		$google_product_category = array(
								'3'=>'Appareils électroniques &gt; Accessoires électroniques &gt; Alimentation &gt; Batteries et piles &gt; Batteries pour ordinateurs portables',
								'574'=>'Véhicules et accessoires &gt; Pièces détachées véhicules',
								'842'=>'Vêtements et accessoires &gt; Sacs à main',
								'412'=>'Arts et loisirs &gt; Loisirs et arts créatifs &gt; Travaux manuels et loisirs &gt; Dessin et peinture &gt; Peinture',
								'748'=>'Appareils électroniques &gt; Accessoires électroniques &gt; Alimentation &gt; Batteries et piles',
								'444'=>'Appareils photo, caméras et instruments d\'optique &gt; Accessoires pour appareils photo, caméras et instruments d\'optique &gt; Objectifs d\'appareils photo et d\'instruments d\'optique &gt; Objectifs de caméra de surveillance',
								'770'=>'Entreprise et industrie &gt; Tatouages et piercings &gt; Matériel de tatouage',
								'372'=>'Appareils électroniques &gt; Communications &gt; Accessoires pour interphone',
								'733'=>'Appareils électroniques &gt; Réseaux &gt; Ponts et routeurs',
								'340'=>'Appareils électroniques &gt; Communications &gt; Téléphonie &gt; Téléphones mobiles &gt; Smartphones',
								'688'=>'Appareils électroniques &gt; Ordinateurs',
								'409'=>'Appareils électroniques &gt; Accessoires électroniques &gt; Alimentation &gt; Chargeurs &gt; Chargeurs solaires',
								'523'=>'Appareils électroniques &gt; Vidéo &gt; Télévision par câble et par satellite &gt; Récepteurs de télévision par satellite'
								);
	}elseif($languages_id == 4){
		$google_product_category = array(
								'3'=>'Electrónica &gt; Accesorios electrónicos &gt; Energía &gt; Baterías &gt; Baterías para portátiles',
								'687'=>'Ropa y accesorios &gt; Disfraces y accesorios &gt; Pelucas',
								'574'=>'Vehículos y recambios &gt; Piezas y accesorios para vehículos &gt; Vídeo y audio para coche',
								'748'=>'Electrónica &gt; Accesorios electrónicos &gt; Energía &gt; Baterías',
								'412'=>'Arte y ocio &gt; Hobbies y artes creativas &gt; Artesanía y aficiones &gt; Dibujo y pintura',
								'842'=>'Ropa y accesorios &gt; Bolsos',
								'444'=>'Casa y jardín &gt; Seguridad del hogar &gt; Cámaras y monitores de seguridad',
								'734'=>'Equipamiento deportivo &gt; Actividades al aire libre &gt; Golf',
								'409'=>'Electrónica &gt; Accesorios electrónicos &gt; Energía &gt; Cargadores &gt; Cargadores solares',
								'770'=>'Economía e industria &gt; Piercings y tatuajes &gt; Instrumentos para tatuajes &gt; Máquinas para tatuajes',
								'372'=>'Casa y jardín &gt; Decoración &gt; Puertas y ventanas &gt; Timbres',
								'442'=>'Electrónica &gt; Vídeo &gt; Proyectores',
								'742'=>'Óptica y fotografía &gt; Cámaras &gt; Cámaras de vigilancia',
								'688'=>'Electrónica &gt; Ordenadores &gt; Tablets',
								'340'=>'Electrónica &gt; Comunicación &gt; Telefonía &gt; Teléfonos móviles &gt; Teléfonos inteligentes',
								'523'=>'Electrónica &gt; Vídeo &gt; Televisión por cable y satélite &gt; Receptores satélite'
								);

	}elseif($languages_id == 5){
	}else{
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
	}


	//按国家分类
	$country_arr = array(
			'US' => array('currency'=>'USD','country'=>'US','language_id'=>1),
			'AU' => array('currency'=>'AUD','country'=>'AU','language_id'=>1),
			'UK' => array('currency'=>'GBP' ,'country'=>'UK','language_id'=>1),
			'FR' => array('currency'=>'EUR' ,'country'=>'FR','language_id'=>3),
			'DE' => array('currency'=>'EUR' ,'country'=>'DE','language_id'=>5),
			'ES' => array('currency'=>'EUR' ,'country'=>'ES','language_id'=>4),
			'CA' => array('currency'=>'CAD' ,'country'=>'CA','language_id'=>1)
	);


    foreach ($select_country as $key => $val){
		$languages_id = $country_arr[$val]['language_id'];
		$currency = $country_arr[$val]['currency'];
		$country = $country_arr[$val]['country'];
		if($where_arr){
			$file_name = 'Product'.$country.'Feed_';
		}else{
			$file_name = 'ALLProduct'.$country.'Feed_';
		}
		include(GOOGLE_TEMPLATE_PATH.'merchant_feed.php');

		mysql_data_seek($query, 0);
    }

	tep_db_close();
	$costTime = microtime(true)-$startTime;
	//header('location:./google_merchant_feed.php?time='.$costTime);exit();
	echo $costTime / 60;
	echo '<pre>';
	print_r(err::get_err());
	echo '</pre>';
 ?>
