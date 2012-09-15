<?php
/*
  $Id: html_output.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
// The HTML href link wrapper function

function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
	// global $request_type, $session_started, $SID, $spider_flag;
	global $request_type, $session_started, $SID, $spider_flag,$g_seo_url;
	if (!tep_not_null($page)) {
		die('</td></tr></table></td></tr></table><br><br><font color="#ff0000">' . TEP_HREF_LINK_ERROR1);
	}
	if ($connection == 'NONSSL') {
		$link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
	} elseif ($connection == 'SSL') {
		if (ENABLE_SSL == true) {
			$link = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG;
		} else {
			$link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
		}
	} else {
		die('</td></tr></table></td></tr></table><br><br><font color="#ff0000">' . TEP_HREF_LINK_ERROR2);
	}
	if (tep_not_null($parameters)) {
		while ( (substr($parameters, -5) == '&amp;') ) $parameters = substr($parameters, 0, strlen($parameters)-5);
		$link .= $page . '?' . tep_output_string($parameters);
		$separator = '&amp;';
	} else {
		$link .= $page;
		$separator = '?';
	}
    // Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
    // there is a minor logic problem with the original osCommerce code
    // the SESSION_FORCE_COOKIE_USE must not be honored if changing from nonssl to ssl
    // if session is not started or requested not to add session, skip it
    if ( ($add_session_id == true) && ($session_started == true) ){

		// if cookies are not set and not forced, then add the session info incase the set cookie fails 
		if ( ! isset($_COOKIE[tep_session_name()]) && (SESSION_FORCE_COOKIE_USE == 'False') ) {
			$_sid = tep_session_name() . '=' . tep_session_id();
			// if we are chaning modes and cookie domains differ, we need to add the session info
		} elseif ( HTTP_COOKIE_DOMAIN . HTTP_COOKIE_PATH != HTTPS_COOKIE_DOMAIN . HTTPS_COOKIE_PATH
                 &&
                 (
                   ( $request_type == 'NONSSL' && $connection == 'SSL' && ENABLE_SSL == true )
                   ||
                   ( $request_type == 'SSL' && $connection == 'NONSSL' )
                 )
               ) {
			$_sid = tep_session_name() . '=' . tep_session_id();
		}
    
    }
    //-MS- SEO-G Added
    if( $connection == 'NONSSL' || SEO_PROCESS_SSL == 'true' ){
		$link = $g_seo_url->get_seo_url($link, $separator);
	}
	//-MS- SEO-G Added EOM
	if (isset($_sid) && !$spider_flag) {
		$link .= $separator . tep_output_string($_sid);
	}
	return $link;
}
// Peter 2010-07-20 
function tep_href_link_page($page = '', $parameters = '',$parameters2 = ''){
	if (tep_not_null($parameters)) {$separator = '&amp;';}else{$separator = '?';}
	$link = tep_href_link($page, $parameters);
	if (strstr($link,'?')){
		return $link.'&'.$parameters2;
	}else{
		return $link.'?'.$parameters2;
	}
}


// The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '') {
    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<img src="' . tep_output_string($src) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
      $image .= ' title=" ' . tep_output_string($alt) . ' "';
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && tep_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = $image_size[0] * $ratio;
        } elseif (tep_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = $image_size[1] * $ratio;
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

    if (tep_not_null($width) && tep_not_null($height)) {
      $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
    }

    if (tep_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= ' />';

    return $image;
  }


// The Javascript Image wrapper build a image tag for a dummy picture,
// then uses javascript to load the actual picure.  This approach prevents spiders from stealing images.
  function tep_javascript_image($src, $name, $alt = '', $width = '', $height = '', $parameters = '', $popup = false) {
    global $product_info;
    $image = '';
    if ( empty($name) || ((empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false')) ) {
      return false;
    }
    // Do we need to add the pop up link code?
    if ( $popup ) {
      $image .= '<div align="center"><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id'] . '&image=0') . '\')">' . "\n";
    }
    // alt is added to the img tag even if it is null to prevent browsers from outputting
    // the image filename as default
    $image .= '<img name="' . tep_output_string($name) . '" src="' . DIR_WS_IMAGES . 'pixel_trans.gif" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
      $image .= ' title=" ' . tep_output_string($alt) . ' "';
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && tep_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = $image_size[0] * $ratio;
        } elseif (tep_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = $image_size[1] * $ratio;
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

    if (tep_not_null($width) && tep_not_null($height)) {
      $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
    }

    if (tep_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= ' />' . "\n";
    
    if ( $popup ) {
      $image .= '<br>' . tep_template_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a></div>' . "\n";
    }
    
    // Now for the Javascript loading code
    $image .= '<script type="text/javascript"><!-- ' . "\n";
    $image .= "document['" . tep_output_string($name) . "'].src = '" . tep_output_string($src) . "'" . "\n";
    $image .= ' //--></script>' ."\n";

    return $image;
  }


  
////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_submit($image, $alt = '', $parameters = '') {
    global $language;

    $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/'. $language .'/buttons/' .  $image) . '" alt="' . tep_output_string($alt) . '"';

// EOM
    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= ' />';
    return $image_submit;

  }
////
// Output a function button in the selected language
  function tep_image_button($image, $alt = '', $parameters = '') {
    global $language;
      $image_button = tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/'. $language .'/buttons/'.  $image, $alt, '', '', $parameters);

 return $image_button;
// EOM
  }
/**
* 
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function tep_image_url($image){
	global $language;
	return DIR_WS_TEMPLATES . TEMPLATE_NAME .'/images/'. $language . '/'. $image;
}
////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_nontemplate_submit($image, $alt = '', $parameters = '') {
    global $language;
// BOM Mod: force all buttons to come from the tempalte folders
  $image_submit = '<input type="image" src="' . tep_output_string($image) . '" alt="' . tep_output_string($alt) . '"';
//    $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/buttons/' . $language . '/' .  $image) . '" alt="' . tep_output_string($alt) . '"';
// EOM
    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= ' />';

    return $image_submit;
  }

////
// Output a function button in the selected language
  function tep_image_nontemplate_button($image, $alt = '', $parameters = '') {
    global $language;
// BOM Mod: force all buttons to come from the tempalte folders
    return tep_image($image, $alt, '', '', $parameters);
//     return tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/buttons/' . $language . '/' .  $image, $alt, '', '', $parameters);
// EOM
  }

////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
  }

////
// Output a form
  function tep_draw_form($name, $action, $method = 'post', $parameters = '') {
    $form = '<form name="' . tep_output_string($name) . '" action="' . tep_output_string($action) . '" method="' . tep_output_string($method) . '"';

    if (tep_not_null($parameters)) $form .= ' ' . $parameters;

    $form .= '>';

    return $form;
  }

////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true) {
    $field = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
    } elseif (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= ' />';

    return $field;
  }

////
// Output a form password field
  function tep_draw_password_field($name, $value = '', $parameters = 'maxlength="40"') {
    return tep_draw_input_field($name, $value, $parameters, 'password', false);
  }

////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
    $selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';

    if ( ($checked == true) || ( isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) && ( ($GLOBALS[$name] == 'on') || (isset($value) && (stripslashes($GLOBALS[$name]) == $value)) ) ) ) {
      $selection .= ' CHECKED';
    }

    if (tep_not_null($parameters)) $selection .= ' ' . $parameters;

    $selection .= ' />';

    return $selection;
  }

////
// Output a form checkbox field
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
  }

////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'radio', $value, $checked, $parameters);
  }

////
// Output a form textarea field
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    $field = '<textarea name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= tep_output_string_protected(stripslashes($GLOBALS[$name]));
    } elseif (tep_not_null($text)) {
      $field .= tep_output_string_protected($text);
    }

    $field .= '</textarea>';

    return $field;
  }

////
// Output a form hidden field
  function tep_draw_hidden_field($name, $value = '', $parameters = '') {
    $field = '<input type="hidden" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    } elseif (isset($GLOBALS[$name])) {
      $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= ' />';

    return $field;
  }

////
// Hide form elements
  function tep_hide_session_id() {
    global $session_started, $SID;

    if (($session_started == true) && tep_not_null($SID)) {
      return tep_draw_hidden_field(tep_session_name(), tep_session_id());
    }
  }

////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    $field = '<select name="' . tep_output_string($name) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' SELECTED';
      }

      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

////
// Creates a pull-down list of countries
  function tep_get_country_list($name, $selected = '', $parameters = '') {
	//静态化国家列表 nathan 2012-2-16
	include(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME.'/box/country_list.php');
	return $country_html;

    $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
    $countries = tep_get_countries();
    if(isset($_POST[$name])) $selected = '';
    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }

    return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
  }
  
// modified by benny 2009/03/07
/**
* 获取省份下拉列表
* @authoer nathan 2011-5-18
* @access public
* @param $name 下拉框NAME
* @param $selected 选中的值
* @param $expand 下拉框额外信息
* @return HTML SELECT
*/
function tep_get_zone_list($name, $selected = '', $expand = null, $countries = 'country'){
	if(isset($_POST[$countries])){
		$zone_option = tep_get_zone($_POST[$countries]);
	}else{
		$zone_option = tep_get_zone();
	}
	return tep_draw_pull_down_menu($name,$zone_option,$selected,$expand);
}
// add seo keyword for  categories name ,products name 
function tep_add_seo_keyword($str){
	//define('INDEX_MAIN_SEO_KEYWORD','');
	if(is_string($str)){
		//return INDEX_MAIN_SEO_KEYWORD . $str;
		return $str;
	}
}
//string trim for products name 
function tep_trim_string($str,$max=''){
	if(tep_not_null($max)){
		if(strlen($str) > $max){$str = substr($str,0,$max) . '...';}
	}
	return $str;
}
	
	function tep_clipped_string($string,$needle=' ',$strlen='24'){
		$final_string ='';
		$temp_array = split($needle, $string);
		$word_count = sizeof($temp_array);
		if(strlen($string) <= $strlen){
			return $string;
		}else{
			if($word_count == 0) return substr($string, 0, $strlen);

			for($i = 0 ; $i < $word_count ; $i++){
				if(strlen($final_string . $temp_array[$i]) > $strlen){
					return $final_string . '...';
					break;
				}else{
					$final_string .= ' '.$temp_array[$i];
				}
			}
			return $final_string;
		}
	}
	
  // modified by benny youtube functions
  function ConvertToOnlineVideo($match) 
  {
    switch($match[1]) 
    {
      case "GV":
            $code = "<object class='onlineVideo' type='application/x-shockwave-flash' data='http://video.google.com/googleplayer.swf?docId={$match[2]}'><param name='allowScriptAccess' value='sameDomain' /><param name='movie' value='http://video.google.com/googleplayer.swf?docId={$match[2]}' /><param name='quality' value='best' /><param name='bgcolor' value='#eaeaea' /><param name='scale' value='scale' /><param name='wmode' value='window' /><param name='salign' value='TL' /><param name='FlashVars' value='playerMode=embedded' /></object>";
        break;
      case "YT":
            $code = "<object class='onlineVideo' type='application/x-shockwave-flash' data='http://www.youtube.com/watch?v={$match[2]}'><param name='allowScriptAccess' value='sameDomain' /><param name='movie' value='http://www.youtube.com/watch?v={$match[2]}' /><param name='wmode' value='window' /><param name='FlashVars' value='playerMode=embedded' /><param name='bgcolor' value='#eaeaea' /><param name='scale' value='scale' /></object>";
        break;
      default:
            $code = "";
    }
    return $code;
  }

	function UnscrubText($text) 
	{
		$pattern = '/<img [\s]*class="onlineVideo ov(GV|YT)" [\s]*alt="([-a-zA-Z0-9_]+)"[^>]*>/si';
	  $text = preg_replace_callback($pattern, 'ConvertToOnlineVideo', $text);
	  return $text;
	}
// trans search keywords space for + 	
 	function change_transearch_keywords($string)
 	{
 		$string = trim($string);
 		return ereg_replace('[[:space:]]','+',$string);
 	}	
 
  // modified by benny get product in  category  low pirce
 function tep_get_subcategories_id($subcategories_array, $parent_id = 0) {
	tep_get_subcategories($subcategories_array, $parent_id);
	return $subcategories_array;
 }
 
// index products ,index nested  shop by price 
function tep_get_products_price($categories_id){
	global $currencies;
	$category_array = array();
	$c_array = tep_get_subcategories_id($category_array, $categories_id);
	if(is_array($c_array)&& tep_not_null($c_array)) {
		for($i=0;$i< sizeof($c_array);$i++) {
			$c_str .= $c_array[$i] . ",";
		}			
		$c_str = substr($c_str,0,-1);
	} else {
		$c_str = $categories_id;
	}
	$sql = tep_db_query("select min(p.products_price) as lowprice, MAX(p.products_price) as maxprice from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc where p.products_id=ptc.products_id and ptc.categories_id in (" . $c_str . ")");
	while($pprice = tep_db_fetch_array($sql)) {
		$price_array['low']= $pprice['lowprice'];
		$price_array['max'] = $pprice['maxprice'];
	}
	return $price_array;
}	
// end  

// products listing split page 
function displayPage($totalPage,$currentPage,$displayNum = 3){
	if($currentPage > $totalPage || empty($totalPage) || empty($currentPage)) return false;
	$pageNum = array();
	if($currentPage - 1 <= $displayNum){
		for($i = 1; $i < $currentPage; ++$i) {
			$pageNum[] = $i;
		}
	}else{
		$pageNum[] = 1;
		if($totalPage > 1 && $currentPage - $displayNum > 2) $pageNum[] = '...';
		for($i = $currentPage - $displayNum;$i < $currentPage; ++$i){
			$pageNum[] = $i;
		}
	}
	if($totalPage - $currentPage <= $displayNum){
		for($i = $currentPage; $i <= $totalPage; ++$i){
			$pageNum[] = $i;
		}
	}else{
		for($i = $currentPage; $i <= $currentPage + $displayNum; ++$i){
			$pageNum[] = $i;
		}
		if($currentPage + $displayNum < $totalPage) $pageNum[] = '...';
		$pageNum[] = $totalPage;
	}
	return $pageNum;
}
//end

// get products categories id 
function tep_get_products_category_id($products_id) {
	$the_products_category_query = tep_db_query("select products_id, categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "'" . " order by products_id,categories_id");
	$the_products_category = tep_db_fetch_array($the_products_category_query);
	return $the_products_category['categories_id'];
}
//end 
 
// modified by benny get category name
function tep_get_category_name($categories_id){
	global $languages_id;
	$sql = tep_db_query("select cd.categories_name  from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id=cd.categories_id and cd.language_id='" . (int)$languages_id . "' and c.categories_id='" . $categories_id . "'");
	$category = tep_db_fetch_array($sql);
	return $category['categories_name'];
}
// end
 	
function rand_param($len){ 
	$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz'; 
	$string=''; 
	for(;$len >= 1;$len--) {
		$position=rand()%strlen($chars);
		$string.=substr($chars,$position,1); 
	}
	return $string; 
}
/* Unit Conversion */
function unit_conversion($num){
	#千克 英镑 kg -> 
	global $languages_id; $currencies;
	# 默认单位为克,
	# 1磅＝16盎司＝0.4536千克=0.9072斤＝453.6克 
	$b = 1;
	$oz = 16;
	$kg = 0.4536;
	$g = 453.6;
	if (is_numeric($num)){
		$v = ceil(($num / $g) * 10) / 10;
	}
	return $v; 
}
/**
* 
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function tep_output_js($path){
	global $request_type, $languages_code;
	$http = ( $request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER;
	$index = strrpos($path, '.');
	$path = substr($path, 0, $index). '_'. $languages_code. substr($path, $index);
	echo '<script type="text/javascript" src="'. $http . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/javascript/'. $path.'"></script>';
}
/**
* facebook IFRAME
* @authoer nathan 
* @access public 
* @param 
* @return string
*/
function tep_output_facebook($width = 240, $height = 290){
	echo '<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fespowcom&amp;width='.$width.'&amp;height='.$height.'&amp;colorscheme=light&amp;show_faces=true&amp;border_color=%23DBDBDB&amp;stream=false&amp;header=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$width.'px; height:'.$height.'px;" allowTransparency="true"></iframe>';
}
/**
* VK IFRAME
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function tep_output_vk($width = 240, $height = 290){
	echo '<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?48"></script>
		  <!-- VK Widget -->
		  <div id="vk_groups"></div>
		  <script type="text/javascript">
			VK.Widgets.Group("vk_groups", {mode: 0, width: "'.$width.'", height: "'.$height.'"}, 33806427);
		  </script>';
}

/**
* Verisign代码
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function tep_get_verisign(){
	$verisign_html = '<img src="'.DIR_WS_TEMPLATES.TEMPLATE_NAME.'/images/verver.gif" border="0" style="margin-right: 10px; cursor: pointer;" alt="'.TEXT_WHOLESALE_VERISIGNVERIFY.'" title="'.TEXT_ESPOW_ACCOUNT_VERISIGVERIFY.'" onclick="javascript:window.open(\'https://trustsealinfo.verisign.com/splash?form_file=fdf/splash.fdf&dn=WWW.ESPOW.COM&lang=en\',\'olcwhatispaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=550, height=500\');"/>';

	if(HTTP_COOKIE_DOMAIN == 'www.espow.com'){
		$verisign_html = '<img src="https://seal.verisign.com/getseal?at=0&sealid=2&dn=www.espow.com&lang=en" border="0" style="margin-right: 10px; cursor: pointer;vertical-align: top;" alt="'.TEXT_WHOLESALE_VERISIGNVERIFY.'" title="'.TEXT_ESPOW_ACCOUNT_VERISIGVERIFY.'" onclick="javascript:window.open(\'https://trustsealinfo.verisign.com/splash?form_file=fdf/splash.fdf&dn=WWW.ESPOW.COM&lang=en\',\'olcwhatispaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=550, height=500\');"/>';		
		//$verisign_html = '<script type="text/javascript" src="https://seal.verisign.com/getseal?host_name=www.espow.com&amp;size=S&amp;use_flash=NO&amp;use_transparent=NO&amp;lang=en"></script>';
	}
	return $verisign_html;
}
?>
