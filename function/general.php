<?php
/*
  $Id: general.php,v 1.1.1.1 2004/03/04 23:40:50 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
function tep_return_broken($bad_string) {
  $char_count = 35;
  $formatted_string = '';
  $countbefore = 0;
  $i = 0;
  while ($i < strlen($bad_string)) {
    $formatted_string .= $bad_string[$i];
    $countbefore ++;
    if ($countbefore > $char_count) {
      $formatted_string .= chr(13);
      $countbefore = 0;
    }
    $i ++;
  } // End of while loop on strlen of bad string
  return $formatted_string;
}
////
// Stop from parsing any further PHP code
  function tep_exit() {
   tep_session_close();
   exit();
  }

////
// Redirect to another page or site
  function tep_redirect($url) {
    if ( (strstr($url, "\n") != false) || (strstr($url, "\r") != false) ) { 
      tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
    }
/*
    if ( (ENABLE_SSL == true) && (getenv('HTTPS') == 'on') ) { // We are loading an SSL page
      if (substr($url, 0, strlen(HTTP_SERVER . DIR_WS_HTTP_CATALOG)) == HTTP_SERVER . DIR_WS_HTTP_CATALOG) { // NONSSL url
        $url = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG . substr($url, strlen(HTTP_SERVER . DIR_WS_HTTP_CATALOG)); // Change it to SSL
      }
    }*/
    
    $url =  str_replace("&amp;", "&", $url);
    
    header('Location: ' . $url);
    
    tep_exit();
  }

////
// Parse the data used in the html tags to ensure the tags will not break
	function tep_parse_input_field_data($data, $parse) {
		return strtr(trim($data), $parse);
	}

  function tep_output_string($string, $translate = false, $protected = false) {
    if ($protected == true) {
      return htmlspecialchars($string);
    } else {
      if ($translate == false) {
        return tep_parse_input_field_data($string, array('"' => '&quot;'));
      } else {
        return tep_parse_input_field_data($string, $translate);
      }
    }
  }

  function tep_output_string_protected($string) {
    return tep_output_string($string, false, true);
  }

  function tep_sanitize_string($string) {
    $string = ereg_replace(' +', ' ', trim($string));

    return preg_replace("/[<>]/", '_', $string);
  }
  //alex 2009-9-17 modified : add the delete javascript code function
  function tep_sanitize_javascript($str){
    $farr = array(
        "/\s+/",  //过滤多余的空白
        "/<(\/?)(script|i?frame|style|html|body|title|link|meta|object|\?|\%)([^>]*?)>/isU",//过滤script等
        "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",    //过滤on开头的时间，防止js调用
        "/(<[^>]*)(href|src)\s*=.*(javascript|vbscript).*([^>]*)(>)/isU",//去掉链接中调用js
        "/expression\((.*)\);?/is" //去掉css调用js
    );
    $tarr = array(
        " ",
        "＜\\1\\2\\3＞",
        "\\1\\2",
        "\\1\\5",
        ""
    );

    $str = preg_replace( $farr,$tarr,$str);
    return $str;
  }

////
// Return a random row from a database query
  function tep_random_select($query) {
    $random_product = '';
    $random_query = tep_db_query($query);
    $num_rows = tep_db_num_rows($random_query);
    if ($num_rows > 0) {
      $random_row = tep_rand(0, ($num_rows - 1));
      tep_db_data_seek($random_query, $random_row);
      $random_product = tep_db_fetch_array($random_query);
    }

    return $random_product;
  }

////
// Return a product's name
// TABLES: products
  function tep_get_products_name($product_id, $language = '') {
    global $languages_id;

    if (empty($language)) $language = $languages_id;

    $product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_name'];
  }
//
// Return a product's name
// TABLES: products
function tep_get_products_image($product_id) {
	global $languages_id;
	$product_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
	$product = tep_db_fetch_array($product_query);
	return $product['products_image'];
}
//gets manufacurs name for a manufacture
function tep_get_manufacturers_name($manufacturers_id) {
	$manufactures_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
	$manufactures = tep_db_fetch_array($manufactures_query);
	return $manufactures['manufacturers_name'];
}
////
// Return a product's special price (returns nothing if there is no offer)
// TABLES: products
function tep_get_products_special_price($product_id) {
	$product_query = tep_db_query("select products_price, products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
	if (tep_db_num_rows($product_query)) {
		$product = tep_db_fetch_array($product_query);
		$product_price = $product['products_price'];
	} else {
		return false;
	}
	// Eversun mod for sppc and qty price breaks
	// $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $product_id . "' and status");
	global $sppc_customer_group_id;
	if(!tep_session_is_registered('sppc_customer_group_id')) {
		$customer_group_id = '0';
	} else {
		$customer_group_id = $sppc_customer_group_id;
	}
	$specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "' and status and customers_group_id = '" . (int)$customer_group_id . "'");
	// Eversun mod for sppc and qty price breaks
	if (tep_db_num_rows($specials_query)) {
		$special = tep_db_fetch_array($specials_query);
		$special_price = $special['specials_new_products_price'];
	} else {
		$special_price = false;
	}
	if(substr($product['products_model'], 0, 4) == 'GIFT') {    //Never apply a salededuction to Ian Wilson's Giftvouchers
		return $special_price;
	}

	$product_to_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
	$product_to_categories = tep_db_fetch_array($product_to_categories_query);
	$category = $product_to_categories['categories_id'];
	
	$sale_query = tep_db_query("select sale_specials_condition, sale_deduction_value, sale_deduction_type from " . TABLE_SALEMAKER_SALES . " where sale_categories_all like '%," . $category . ",%' and sale_status = '1' and (sale_date_start <= now() or sale_date_start = '0000-00-00') and (sale_date_end >= now() or sale_date_end = '0000-00-00') and (sale_pricerange_from <= '" . $product_price . "' or sale_pricerange_from = '0') and (sale_pricerange_to >= '" . $product_price . "' or sale_pricerange_to = '0')");
	if (tep_db_num_rows($sale_query)) {
		$sale = tep_db_fetch_array($sale_query);
	} else {
		return $special_price;
	}

		if (!$special_price) {
			$tmp_special_price = $product_price;
		} else {
			$tmp_special_price = $special_price;
		}

		switch ($sale['sale_deduction_type']) {
			case 0:
				$sale_product_price = $product_price - $sale['sale_deduction_value'];
				$sale_special_price = $tmp_special_price - $sale['sale_deduction_value'];
				break;
			case 1:
				$sale_product_price = $product_price - (($product_price * $sale['sale_deduction_value']) / 100);
				$sale_special_price = $tmp_special_price - (($tmp_special_price * $sale['sale_deduction_value']) / 100);
				break;
			case 2:
				$sale_product_price = $sale['sale_deduction_value'];
				$sale_special_price = $sale['sale_deduction_value'];
				break;
			default:
				return $special_price;
		}

		if ($sale_product_price < 0) {
			$sale_product_price = 0;
		}
		
		if ($sale_special_price < 0) {
			$sale_special_price = 0;
		}
		
		if (!$special_price) {
			return number_format($sale_product_price, 4, '.', '');
		} else {
			switch($sale['sale_specials_condition']){
				case 0:
					return number_format($sale_product_price, 4, '.', '');
					break;
				case 1:
					return number_format($special_price, 4, '.', '');
					break;
				case 2:
					return number_format($sale_special_price, 4, '.', '');
					break;
				default:
					return number_format($special_price, 4, '.', '');
			}
		}
}

// Return a product ID from a product ID with attributes
/*function tep_get_prid($uprid) {
    $pieces = explode('{', $uprid);

    if (is_numeric($pieces[0])) {
      return $pieces[0];
    } else {
      return false;
    }
  }

*/////


////
// Return a product's stock
// TABLES: products
  function tep_get_products_stock($products_id) {
    $products_id = tep_get_prid($products_id);
    $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
    $stock_values = tep_db_fetch_array($stock_query);

    return $stock_values['products_quantity'];
  }

////
// Check if the required stock is available
// If insufficent stock is available return an out of stock message
  function tep_check_stock($products_id, $products_quantity) {
    $stock_left = tep_get_products_stock($products_id) - $products_quantity;
    $out_of_stock = '';

    if ($stock_left < 0) {
      $out_of_stock = '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
    }

    return $out_of_stock;
  }

////
// Break a word in a string if it is longer than a specified length ($len)
  function tep_break_string($string, $len, $break_char = '-') {
    $l = 0;
    $output = '';
    for ($i=0, $n=strlen($string); $i<$n; $i++) {
      $char = substr($string, $i, 1);
      if ($char != ' ') {
        $l++;
      } else {
        $l = 0;
      }
      if ($l > $len) {
        $l = 1;
        $output .= $break_char;
      }
      $output .= $char;
    }

    return $output;
  }

////
// Return all HTTP GET variables, except those passed as a parameter
function tep_get_all_get_params($exclude_array = '') {
	global $HTTP_GET_VARS;
	if (!is_array($exclude_array)) $exclude_array = array();
	$get_url = '';
	if (is_array($HTTP_GET_VARS) && (sizeof($HTTP_GET_VARS) > 0)) {
		reset($HTTP_GET_VARS);
		while (list($key, $value) = each($HTTP_GET_VARS)) {
			if ( (strlen($value) > 0) && ($key != tep_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array)) && ($key != 'x') && ($key != 'y') ) {
				$get_url .= rawurlencode(stripslashes($key)) . '=' . rawurlencode(stripslashes($value)) . '&';
			}
		}
	}
	return $get_url;
}

////
// Returns an array with countries
// TABLES: countries
function tep_get_countries($countries_id = '', $with_iso_codes = false) {
	$countries_array = array();
	if (tep_not_null($countries_id)) {
		if ($with_iso_codes == true) {
			$countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "' order by countries_name");
			$countries_values = tep_db_fetch_array($countries);
			$countries_array = array('countries_name' => $countries_values['countries_name'],
									 'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
									 'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
		} else {
			$countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "'");
			$countries_values = tep_db_fetch_array($countries);
			$countries_array = array('countries_name' => $countries_values['countries_name']);
		}
	} else {
		$countries = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
		while ($countries_values = tep_db_fetch_array($countries)) {
			$countries_array[] = array('countries_id' => $countries_values['countries_id'],
									   'countries_name' => $countries_values['countries_name']);
		}
	}
	return $countries_array;
}
// Alias function to tep_get_countries()
function tep_get_country_name($country_id) {
	$country_array = tep_get_countries($country_id);
	return $country_array['countries_name'];
}
// Alias function to tep_get_countries, which also returns the countries iso codes
function tep_get_countries_with_iso_codes($countries_id) {
	return tep_get_countries($countries_id, true);
}

////
// Returns the zone (State/Province) name
// TABLES: zones
function tep_get_zone_name($country_id, $zone_id, $default_zone) {
	$zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
	if (tep_db_num_rows($zone_query)) {
		$zone = tep_db_fetch_array($zone_query);
		return $zone['zone_name'];
	} else {
		return $default_zone;
	}
}
////
/**
* get_zone_list
* @authoer nathan 2011-5-18
* @access public
* @param $contry_id int 国家ID 默认值 美国
* @param $select bool 默认选择省份
* @return Array
*/
function tep_get_zone($contry_id = STORE_COUNTRY ,$select = true){
	$ret = null;
	$zones_query = tep_db_query("select * from ".TABLE_ZONES." where zone_country_id = '".(int)$contry_id."' order by zone_name");
	if(tep_db_num_rows($zones_query)){
		if($select){
			$ret = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
			while($rows = tep_db_fetch_array($zones_query)){
				$ret[] = array('id'=>$rows['zone_id'],'text'=>$rows['zone_name']);
			}
		}else{
			$ret = tep_db_fetch_all_reslt($zones_query);
		}
	}
	return $ret;
}
// Returns the zone (State/Province) code
// TABLES: zones
function tep_get_zone_code($country_id, $zone_id, $default_zone) {
	$zone_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
	if (tep_db_num_rows($zone_query)) {
		$zone = tep_db_fetch_array($zone_query);
		return $zone['zone_code'];
	} else {
		return $default_zone;
	}
}
////

////
// Generate a path to categories
function tep_get_path($current_category_id = '') {
	global $cPath_array, $obj_categories;
	if (tep_not_null($current_category_id)) {
		$cp_size = sizeof($cPath_array);
		if ($cp_size == 0) {
			$cPath_new = $current_category_id;
		} else {
			$cPath_new = '';

			$last_category_parent_id = $obj_categories->get_parent_id((int)$cPath_array[($cp_size-1)]);
		    $current_category_parent_id = $obj_categories->get_parent_id((int)$current_category_id);

			if ($last_category_parent_id == $current_category_parent_id) {
				for ($i=0; $i<($cp_size-1); $i++) {
					$cPath_new .= '_' . $cPath_array[$i];
				}
			} else {
				for ($i=0; $i<$cp_size; $i++) {
					$cPath_new .= '_' . $cPath_array[$i];
				}
			}
			$cPath_new .= '_' . $current_category_id;
			if (substr($cPath_new, 0, 1) == '_') {
				$cPath_new = substr($cPath_new, 1);
			}
		}
	} else {
		$cPath_new = implode('_', $cPath_array);
	}
	return 'cPath=' . $cPath_new;
}
// 2010-09-10 ȡľ·
function tep_get_abscPath($current_category_id,$abscPath = ""){
	global $obj_categories;
	if ($current_category_id){ 
		if ( empty($abscPath) ) {
			$abscPath = $current_category_id;
		}else{
			$abscPath = $current_category_id.'_'.$abscPath;
		}
		$parent_id = $obj_categories->get_parent_id($current_category_id);
		if ( $parent_id ){
			$abscPath = tep_get_abscPath($parent_id, $abscPath);
		}else{
			$abscPath = 'cPath=' . $abscPath ;
		}
		return $abscPath;
	}else{ 
		return '';
	}
}
// 2010-09-10 ȡҳ浼
function tep_get_pageNavi(){};

////
// Returns the clients browser
function tep_browser_detect($component) {
	global $HTTP_USER_AGENT;
	return stristr($HTTP_USER_AGENT, $component);
}

////


////
// Wrapper function for round()
  function tep_round($number, $precision) {
    if (strpos($number, '.') && (strlen(substr($number, strpos($number, '.')+1)) > $precision)) {
      $number = substr($number, 0, strpos($number, '.') + 1 + $precision + 1);

      if (substr($number, -1) >= 5) {
        if ($precision > 1) {
          $number = substr($number, 0, -1) + ('0.' . str_repeat(0, $precision-1) . '1');
        } elseif ($precision == 1) {
          $number = substr($number, 0, -1) + 0.1;
        } else {
          $number = substr($number, 0, -1) + 1;
        }
      } else {
        $number = substr($number, 0, -1);
      }
    }

    return $number;
  }

////
// Returns the tax rate for a zone / class
// TABLES: tax_rates, zones_to_geo_zones
function tep_get_tax_rate($class_id, $country_id = -1, $zone_id = -1) {

    
//Eversun mod for sppc and qty price breaks
//  global $customer_zone_id, $customer_country_id;
    global $customer_zone_id, $customer_country_id, $sppc_customer_group_tax_exempt;

     if(!tep_session_is_registered('sppc_customer_group_tax_exempt')) {
     $customer_group_tax_exempt = '0';
     } else {
     $customer_group_tax_exempt = $sppc_customer_group_tax_exempt;
     }

     if ($customer_group_tax_exempt == '1') {
       return 0;
     }
//Eversun mod end for sppc and qty price breaks
    if ( ($country_id == -1) && ($zone_id == -1) ) {
      if (!tep_session_is_registered('customer_id')) {
        $country_id = STORE_COUNTRY;
        $zone_id = STORE_ZONE;
      } else {
        $country_id = $customer_country_id;
        $zone_id = $customer_zone_id;
      }
    }

    $tax_query = tep_db_query("select sum(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' group by tr.tax_priority");
    if (tep_db_num_rows($tax_query)) {echo "}}}}{{{{{";
      $tax_multiplier = 1.0;
      while ($tax = tep_db_fetch_array($tax_query)) {
        $tax_multiplier *= 1.0 + ($tax['tax_rate'] / 100);
      }
      return ($tax_multiplier - 1.0) * 100;
    } else {
      return 0;
    }
  }

////
// Return the tax description for a zone / class
// TABLES: tax_rates;
  function tep_get_tax_description($class_id, $country_id, $zone_id) {
    $tax_query = tep_db_query("select tax_description from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' order by tr.tax_priority");
    if (tep_db_num_rows($tax_query)) {
      $tax_description = '';
      while ($tax = tep_db_fetch_array($tax_query)) {
        $tax_description .= $tax['tax_description'] . ' + ';
      }
      $tax_description = substr($tax_description, 0, -3);

      return $tax_description;
    } else {
      return TEXT_UNKNOWN_TAX_RATE;
    }
  }

////
// Add tax to a products price
  function tep_add_tax($price, $tax) {
    global $currencies, $sppc_customer_group_show_tax;

    if ( (DISPLAY_PRICE_WITH_TAX == 'true') && ($tax > 0)  && $sppc_customer_group_show_tax == '1') {
      return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']) + tep_calculate_tax($price, $tax);
    } else {
      return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
    }
  }

// Calculates Tax rounding the result
  function tep_calculate_tax($price, $tax) {
    global $currencies;
    return tep_round($price * $tax / 100, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
  }

////
// Return the number of products in a category
// TABLES: products, products_to_categories, categories
  function tep_count_products_in_category($category_id, $include_inactive = false) {
    $products_count = 0;
	tep_get_subcategories($category_list,$category_id);
	$category_list[] = $category_id;
	$products_sql = "select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id in (" . join(',',$category_list) . ")";
    if ($include_inactive == false) {
		$products_sql.= " and p.products_status = '1'";
    }
	$products_query = tep_db_query($products_sql);
    $products = tep_db_fetch_array($products_query);
    $products_count = $products['total'];

    return $products_count;
  }
////
// Return true if the category has subcategories
// TABLES: categories
  function tep_has_category_subcategories($category_id) {
    $child_category_query = tep_db_query("select count(*) as count from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
    $child_category = tep_db_fetch_array($child_category_query);

    if ($child_category['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }

////
// Returns the address_format_id for the given country
// TABLES: countries;
  function tep_get_address_format_id($country_id) {
    $address_format_query = tep_db_query("select address_format_id as format_id from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$country_id . "'");
    if (tep_db_num_rows($address_format_query)) {
      $address_format = tep_db_fetch_array($address_format_query);
      return $address_format['format_id'];
    } else {
      return '1';
    }
  }

////
// Return a formatted address
// TABLES: address_format
  function tep_address_format($address_format_id, $address, $html, $boln, $eoln) {
    $address_format_query = tep_db_query("select address_format as format from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . (int)$address_format_id . "'");
    $address_format = tep_db_fetch_array($address_format_query);

    $company = tep_output_string_protected($address['company']);
    if (isset($address['firstname']) && tep_not_null($address['firstname'])) {
      $firstname = tep_output_string_protected($address['firstname']);
      $lastname = tep_output_string_protected($address['lastname']);
    } elseif (isset($address['name']) && tep_not_null($address['name'])) {
      $firstname = tep_output_string_protected($address['name']);
      $lastname = '';
    } else {
      $firstname = '';
      $lastname = '';
    }
    $street = tep_output_string_protected($address['street_address']);
    $suburb = tep_output_string_protected($address['suburb']);
    $city = tep_output_string_protected($address['city']);
    $state = tep_output_string_protected($address['state']);
    if (isset($address['country_id']) && tep_not_null($address['country_id'])) {
      $country = tep_get_country_name($address['country_id']);

      if (isset($address['zone_id']) && tep_not_null($address['zone_id'])) {
        $state = tep_get_zone_code($address['country_id'], $address['zone_id'], $state);
      }
    } elseif (isset($address['country']) && tep_not_null($address['country'])) {
      $country = tep_output_string_protected($address['country']);
    } else {
      $country = '';
    }
    $postcode = tep_output_string_protected($address['postcode']);
    $zip = $postcode;

    if ($html) {
// HTML Mode
      $HR = '<hr>';
      $hr = '<hr>';
      if ( ($boln == '') && ($eoln == "\n") ) { // Values not specified, use rational defaults
        $CR = '<br>';
        $cr = '<br>';
        $eoln = $cr;
      } else { // Use values supplied
        $CR = $eoln . $boln;
        $cr = $CR;
      }
    } else {
// Text Mode
      $CR = $eoln;
      $cr = $CR;
      $HR = '----------------------------------------';
      $hr = '----------------------------------------';
    }

    $statecomma = '';
    $streets = $street;
    if ($suburb != '') $streets = $street . $cr . $suburb;
    if ($country == '') $country = tep_output_string_protected($address['country']);
    if ($state != '') $statecomma = $state . ', ';

    $fmt = $address_format['format'];
    eval("\$address = \"$fmt\";");

    if ( (ACCOUNT_COMPANY == 'true') && (tep_not_null($company)) ) {
      $address = $company . $cr . $address;
    }

    return $address;
  }

////
// Return a formatted address
// TABLES: customers, address_book
function tep_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n") {
	$address_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$address_id . "'");
	$address = tep_db_fetch_array($address_query);
	
	$format_id = tep_get_address_format_id($address['country_id']);
	
	return tep_address_format($format_id, $address, $html, $boln, $eoln);
}

function tep_row_number_format($number) {
	if ( ($number < 10) && (substr($number, 0, 1) != '0') ) $number = '0' . $number;
	return $number;
}

function tep_get_categories($categories_array = '', $parent_id = '0', $indent = '') {
	global $languages_id, $customer_group_id;
	
	if (!is_array($categories_array)) $categories_array = array();
	
	$categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id = '" . (int)$parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.products_group_access like '%". $customer_group_id."%' order by sort_order, cd.categories_name");
	while ($categories = tep_db_fetch_array($categories_query)) {
	$categories_array[] = array('id' => $categories['categories_id'],
						  'text' => $indent . $categories['categories_name']);
	
	if ($categories['categories_id'] != $parent_id) {
	$categories_array = tep_get_categories($categories_array, $categories['categories_id'], $indent . '&nbsp;&nbsp;');
	}
	}
	
	return $categories_array;
}

function tep_get_categories2($categories_array = '', $parent_id = '0', $indent = '',$abspathPstr = '') {
	global $languages_id, $customer_group_id;
	if (!is_array($categories_array)) {$categories_array = array();}
	$categories_query = tep_db_query("select c.categories_id, c.parent_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id = '" . (int)$parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.products_group_access like '%". $customer_group_id."%' order by sort_order, cd.categories_name");
	while ($categories = tep_db_fetch_array($categories_query)) {
		# peter 20100514 add
		$products_in_category = tep_count_products_in_category($categories['categories_id']);
		if ($products_in_category > 0) {
			if ($categories['parent_id'] == 0){
				$abspathStr = $categories['categories_id'];
			}else{
				$abspathStr = $abspathPstr.'_'.$categories['categories_id'];
			}
			$categories_array[] = array('id' => $categories['categories_id'],
									'prent_id'=> $categories['parent_id'],
									'abspath' => $abspathStr,
									'text' => $indent . $categories['categories_name']);
			if ($categories['categories_id'] != $parent_id) {
				//$categories_array = tep_get_categories2($categories_array, $categories['categories_id'], $indent,$abspathPstr);
				$categories_array = tep_get_categories2($categories_array, $categories['categories_id'], $indent,$abspathStr);
			}//
		}//
    }//
	return $categories_array;
}

  function tep_get_manufacturers($manufacturers_array = '') {
    if (!is_array($manufacturers_array)) $manufacturers_array = array();

    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'], 'text' => $manufacturers['manufacturers_name']);
    }

    return $manufacturers_array;
  }

////
// Return all subcategory IDs
// TABLES: categories
/**
* 获取父级分类下的所有下级分类ID
* @authoer nathan 
* @access public 
* @param &$subcategories_array 数组 所有下级分类ID
* @param $parent_id int 分类父级ID
* @return 
*/
  function tep_get_subcategories(&$subcategories_array, $parent_id = 0) {
	  global $obj_categories;
	  $subcategories_array = $obj_categories->get_subcategories($parent_id);
  }
  /**
  * 获取分类以及下级分类的产品数量(去重复)
  * @authoer espow Team nathan 2011-4-25
  * @access public 
  * @param $categories_id Int 分类ID
  * @param $where_str String SQL条件语句
  * @return Int
  */
  function tep_get_distinct_products_num_categories($categories_id,$where_str = ''){
	global $languages_id , $customer_group_id;
	$categories_array = array();
	tep_get_subcategories($categories_array,$categories_id);

	$where_str.= " AND (c.categories_id='" . $categories_id . "' OR c.parent_id='" . $categories_id . "'";
	foreach ($categories_array as $key => $val){
		$where_str .= " or p2c.categories_id = '" . (int)$val . "'";
	}
	$where_str .= ")";
	$products_num_sql = 'select distinct p.products_model 
									from ((products p
										LEFT JOIN '.TABLE_PRODUCTS_GROUPS.' pg
										  ON (p.products_id = pg.products_id)
										  AND pg.customers_group_id = "G")),
										'.TABLE_PRODUCTS_DESCRIPTION.' pd,
										'.TABLE_CATEGORIES.' c,
										'.TABLE_PRODUCTS_TO_CATEGORIES.' p2c
									WHERE p.products_status = "1" 
									AND p.products_quantity > 0
									AND pd.products_id = p.products_id
									AND p.products_id = p2c.products_id
									AND p2c.categories_id = c.categories_id
									AND pd.language_id = "'.(int)$languages_id.'"
									AND p.products_group_access LIKE "%' . $customer_group_id . '%"'.$where_str;
	$products_num_query = tep_db_query($products_num_sql);
	return tep_db_num_rows($products_num_query);
  }
// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
  function tep_date_long($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

    $year = (int)substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    return strftime(DATE_FORMAT_LONG, mktime($hour,$minute,$second,$month,$day,$year));
  }

////
// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
// NOTE: Includes a workaround for dates before 01/01/1970 that fail on windows servers
  function tep_date_short($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || empty($raw_date) ) return false;

    $year = substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
      return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
    } else {
      return ereg_replace('2037' . '$', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
    }
  }

  /**
  * 
  * @authoer nathan 
  * @access public 
  * @param 
  * @return 
  */
  function tep_date_shorten($date){
	  return strftime('%b %d,%Y',strtotime($date));
  }

////
// Parse search string into indivual objects
  function tep_parse_search_string($search_str = '', &$objects) {
    $search_str = trim(strtolower($search_str));

// Break up $search_str on whitespace; quoted string will be reconstructed later
    $pieces = split('[[:space:]]+', $search_str);
    $objects = array();
    $tmpstring = '';
    $flag = '';

    for ($k=0; $k<count($pieces); $k++) {
      while (substr($pieces[$k], 0, 1) == '(') {
        $objects[] = '(';
        if (strlen($pieces[$k]) > 1) {
          $pieces[$k] = substr($pieces[$k], 1);
        } else {
          $pieces[$k] = '';
        }
      }

      $post_objects = array();

      while (substr($pieces[$k], -1) == ')')  {
        $post_objects[] = ')';
        if (strlen($pieces[$k]) > 1) {
          $pieces[$k] = substr($pieces[$k], 0, -1);
        } else {
          $pieces[$k] = '';
        }
      }

// Check individual words

      if ( (substr($pieces[$k], -1) != '"') && (substr($pieces[$k], 0, 1) != '"') ) {
        $objects[] = trim($pieces[$k]);

        for ($j=0; $j<count($post_objects); $j++) {
          $objects[] = $post_objects[$j];
        }
      } else {
/* This means that the $piece is either the beginning or the end of a string.
   So, we'll slurp up the $pieces and stick them together until we get to the
   end of the string or run out of pieces.
*/

// Add this word to the $tmpstring, starting the $tmpstring
        $tmpstring = trim(ereg_replace('"', ' ', $pieces[$k]));

// Check for one possible exception to the rule. That there is a single quoted word.
        if (substr($pieces[$k], -1 ) == '"') {
// Turn the flag off for future iterations
          $flag = 'off';

          $objects[] = trim($pieces[$k]);

          for ($j=0; $j<count($post_objects); $j++) {
            $objects[] = $post_objects[$j];
          }

          unset($tmpstring);

// Stop looking for the end of the string and move onto the next word.
          continue;
        }

// Otherwise, turn on the flag to indicate no quotes have been found attached to this word in the string.
        $flag = 'on';

// Move on to the next word
        $k++;

// Keep reading until the end of the string as long as the $flag is on

        while ( ($flag == 'on') && ($k < count($pieces)) ) {
          while (substr($pieces[$k], -1) == ')') {
            $post_objects[] = ')';
            if (strlen($pieces[$k]) > 1) {
              $pieces[$k] = substr($pieces[$k], 0, -1);
            } else {
              $pieces[$k] = '';
            }
          }

// If the word doesn't end in double quotes, append it to the $tmpstring.
          if (substr($pieces[$k], -1) != '"') {
// Tack this word onto the current string entity
            $tmpstring .= ' ' . $pieces[$k];

// Move on to the next word
            $k++;
            continue;
          } else {
/* If the $piece ends in double quotes, strip the double quotes, tack the
   $piece onto the tail of the string, push the $tmpstring onto the $haves,
   kill the $tmpstring, turn the $flag "off", and return.
*/
            $tmpstring .= ' ' . trim(ereg_replace('"', ' ', $pieces[$k]));

// Push the $tmpstring onto the array of stuff to search for
            $objects[] = trim($tmpstring);

            for ($j=0; $j<count($post_objects); $j++) {
              $objects[] = $post_objects[$j];
            }

            unset($tmpstring);

// Turn off the flag to exit the loop
            $flag = 'off';
          }
        }
      }
    }

// add default logical operators if needed
/*
 * alex 2010-4-10 noted 
    $temp = array();
    for($i=0; $i<(count($objects)-1); $i++) {
      $temp[] = $objects[$i];
      if ( ($objects[$i] != 'and') &&
           ($objects[$i] != 'or') &&
           ($objects[$i] != '(') &&
           ($objects[$i+1] != 'and') &&
           ($objects[$i+1] != 'or') &&
           ($objects[$i+1] != ')') ) {
        $temp[] = ADVANCED_SEARCH_DEFAULT_OPERATOR;
      }
    }
    $temp[] = $objects[$i];
    $objects = $temp;
*/
    $keyword_count = 0;
    $operator_count = 0;
    $balance = 0;
    for($i=0; $i<count($objects); $i++) {
      if ($objects[$i] == '(') $balance --;
      if ($objects[$i] == ')') $balance ++;
      if ( ($objects[$i] == 'and') || ($objects[$i] == 'or') ) {
        $operator_count ++;
      } elseif ( ($objects[$i]) && ($objects[$i] != '(') && ($objects[$i] != ')') ) {
        $keyword_count ++;
      }
    }

    if ( ($operator_count < $keyword_count) && ($balance == 0) ) {
      return true;
    } else {
      return false;
    }
  }

////
// Check date
  function tep_checkdate($date_to_check, $format_string, &$date_array) {
    $separator_idx = -1;

    $separators = array('-', ' ', '/', '.');
    $month_abbr = array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
    $no_of_days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    $format_string = strtolower($format_string);

    if (strlen($date_to_check) != strlen($format_string)) {
      return false;
    }

    $size = sizeof($separators);
    for ($i=0; $i<$size; $i++) {
      $pos_separator = strpos($date_to_check, $separators[$i]);
      if ($pos_separator != false) {
        $date_separator_idx = $i;
        break;
      }
    }

    for ($i=0; $i<$size; $i++) {
      $pos_separator = strpos($format_string, $separators[$i]);
      if ($pos_separator != false) {
        $format_separator_idx = $i;
        break;
      }
    }

    if ($date_separator_idx != $format_separator_idx) {
      return false;
    }

    if ($date_separator_idx != -1) {
      $format_string_array = explode( $separators[$date_separator_idx], $format_string );
      if (sizeof($format_string_array) != 3) {
        return false;
      }

      $date_to_check_array = explode( $separators[$date_separator_idx], $date_to_check );
      if (sizeof($date_to_check_array) != 3) {
        return false;
      }

      $size = sizeof($format_string_array);
      for ($i=0; $i<$size; $i++) {
        if ($format_string_array[$i] == 'mm' || $format_string_array[$i] == 'mmm') $month = $date_to_check_array[$i];
        if ($format_string_array[$i] == 'dd') $day = $date_to_check_array[$i];
        if ( ($format_string_array[$i] == 'yyyy') || ($format_string_array[$i] == 'aaaa') ) $year = $date_to_check_array[$i];
      }
    } else {
      if (strlen($format_string) == 8 || strlen($format_string) == 9) {
        $pos_month = strpos($format_string, 'mmm');
        if ($pos_month != false) {
          $month = substr( $date_to_check, $pos_month, 3 );
          $size = sizeof($month_abbr);
          for ($i=0; $i<$size; $i++) {
            if ($month == $month_abbr[$i]) {
              $month = $i;
              break;
            }
          }
        } else {
          $month = substr($date_to_check, strpos($format_string, 'mm'), 2);
        }
      } else {
        return false;
      }

      $day = substr($date_to_check, strpos($format_string, 'dd'), 2);
      $year = substr($date_to_check, strpos($format_string, 'yyyy'), 4);
    }

    if (strlen($year) != 4) {
      return false;
    }

    if (!settype($year, 'integer') || !settype($month, 'integer') || !settype($day, 'integer')) {
      return false;
    }

    if ($month > 12 || $month < 1) {
      return false;
    }

    if ($day < 1) {
      return false;
    }

    if (tep_is_leap_year($year)) {
      $no_of_days[1] = 29;
    }

    if ($day > $no_of_days[$month - 1]) {
      return false;
    }

    $date_array = array($year, $month, $day);

    return true;
  }

////
// Check if year is a leap year
  function tep_is_leap_year($year) {
    if ($year % 100 == 0) {
      if ($year % 400 == 0) return true;
    } else {
      if (($year % 4) == 0) return true;
    }

    return false;
  }

////
// Return table heading with sorting capabilities
  function tep_create_sort_heading($sortby, $colnum, $heading) {
    global $PHP_SELF;

    $sort_prefix = '';
    $sort_suffix = '';

    if ($sortby) {
      $sort_prefix = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('page', 'info', 'sort')) . 'page=1&sort=' . $colnum . ($sortby == $colnum . 'a' ? 'd' : 'a')) . '" title="' . tep_output_string(TEXT_SORT_PRODUCTS . ($sortby == $colnum . 'd' || substr($sortby, 0, 1) != $colnum ? TEXT_ASCENDINGLY : TEXT_DESCENDINGLY) . TEXT_BY . $heading) . '" class="productListing-heading">' ;
      $sort_suffix = (substr($sortby, 0, 1) == $colnum ? (substr($sortby, 1, 1) == 'a' ? '+' : '-') : '') . '</a>';
    }

    return $sort_prefix . $heading . $sort_suffix;
  }

////
// Recursively go through the categories and retreive all parent categories IDs
// TABLES: categories
  function tep_get_parent_categories(&$categories, $categories_id) {
	global $obj_categories;
	if(!$categories_id) return null;
	$parent_id = $obj_categories->get_parent_id($categories_id);
	if($parent_id == 0 ) return;
	$categories[sizeof($categories)] = $parent_id;
	tep_get_parent_categories($categories, $parent_id);
  }

////
// Construct a category path to the product
// TABLES: products_to_categories
  function tep_get_product_path($products_id) {
    $cPath = '';

    $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1");
    if (tep_db_num_rows($category_query)) {
      $category = tep_db_fetch_array($category_query);

      $categories = array();
      tep_get_parent_categories($categories, $category['categories_id']);

      $categories = array_reverse($categories);

      $cPath = implode('_', $categories);

      if (tep_not_null($cPath)) $cPath .= '_';
      $cPath .= $category['categories_id'];
    }

    return $cPath;
  }

////
// Return a product ID with attributes
  function tep_get_uprid($prid, $params) {
    $uprid = $prid;
    if ( (is_array($params)) && (!strstr($prid, '{')) ) {
      while (list($option, $value) = each($params)) {
        // the newer option types are passed as more complex arrays
        // check for this and handle the checkbox and text options differently
        if (!is_array($value)) {
		  if(empty($value)) continue; //@nathan 2011-10-11
          $uprid = $uprid . '{' . $option . '}' . $value;
        } else {
          while (list($subtype, $subvalue) = each($value)) {
            if ($subtype == 'c') {  // this is a checkbox
              list($chkbox, $chkboxvalue) = each($subvalue);
              $uprid = $uprid . '{' . $option . '}' . $chkbox;
            }
            if ($subtype == 't' && !empty($subvalue)) {  // this is a text input
              $crc = crc32($subvalue);
              $uprid = $uprid . '{' . $option . '}' . sprintf("%u", $crc);
            }
          }
        }
      }
    }

    return $uprid;
  }

////
// Return a product ID from a product ID with attributes
  function tep_get_prid($uprid) {
    $pieces = explode('{', $uprid);

    return $pieces[0];
  }

////
// Return a customer greeting
function tep_customer_greeting() {
	global $customer_id, $customer_first_name;
	if (tep_session_is_registered('customer_first_name') && tep_session_is_registered('customer_id')) {
		$greeting_string = sprintf(TEXT_GREETING_PERSONAL, tep_output_string_protected($customer_first_name), tep_href_link(FILENAME_PRODUCTS_NEW));
	} else {
		$greeting_string = sprintf(TEXT_GREETING_GUEST, tep_href_link(FILENAME_LOGIN, '', 'SSL'), tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
	}
	return $greeting_string;
}
////
//! Send email (text/html) using MIME
// This is the central mail function. The SMTP Server should be configured
// correct in php.ini
// Parameters:
// $to_name           The name of the recipient, e.g. "Jan Wildeboer"
// $to_email_address  The eMail address of the recipient,
//                    e.g. jan.wildeboer@gmx.de
// $email_subject     The subject of the eMail
// $email_text        The text of the eMail, may contain HTML entities
// $from_email_name   The name of the sender, e.g. Shop Administration
// $from_email_adress The eMail address of the sender,
//                    e.g. info@mytepshop.com
function tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address) {
	if (SEND_EMAILS != 'true') return false;
    $headers["From"] = $from_email_address;
    $headers["To"] = $to_email_address;
    $headers["Subject"] = $email_subject;
    $mail_object =& Mail::factory("sendmail");
	if (EMAIL_USE_HTML == 'true') {
        $headers["Content-Type"] = "text/html; charset=utf-8";
        $message = str_replace(array("\r\n", "\n", "\r"), "<br />",$email_text);
    }else{
        $headers["Content-Type"] = "text/plain; charset=utf-8";
        $message = str_replace(array("\r\n", "\n", "\r"), "\r\n",strip_tags($email_text));
    }
	tep_bh_transac_mail($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject, $message);
	// Send message
    //$mail_object->send($to_email_address, $headers, $message);
}
//// products_id
// Check if product has attributes
function tep_has_product_attributes($products_id) {
	$attributes_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "'");
	$attributes = tep_db_fetch_array($attributes_query);
	if ($attributes['count'] > 0) {
		return true;
	} else {
		return false;
	}
}
//
//check if product is really a subproduct
//returns parent_id if product is a sub, or false if it is not
function tep_subproducts_parent($products_id) {
	$product_sub_product_query = tep_db_query("select products_parent_id from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$products_id . "'");
	$product_sub_product = tep_db_fetch_array($product_sub_product_query);
	if ($product_sub_product['products_parent_id'] > 0){
		return $product_sub_product['products_parent_id'];
	} else {
		return false;
	}
}
// Check if product has subproducts
function tep_has_product_subproducts($products_id) {
	$subproducts_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS . " where products_parent_id  = '" . (int)$products_id . "'");
	$subproducts = tep_db_fetch_array($subproducts_query);
	if ($subproducts['count'] > 0) {
		return true;
	} else {
		return false;
	}
}

////
// Get the number of times a word/character is present in a string
function tep_word_count($string, $needle) {
	$temp_array = split($needle, $string);
	return sizeof($temp_array);
}

  function tep_count_modules($modules = '') {
    $count = 0;

    if (empty($modules)) return $count;

    $modules_array = split(';', $modules);

    for ($i=0, $n=sizeof($modules_array); $i<$n; $i++) {
      $class = substr($modules_array[$i], 0, strrpos($modules_array[$i], '.'));

      if (is_object($GLOBALS[$class])) {
        if ($GLOBALS[$class]->enabled) {
          $count++;
        }
      }
    }

    return $count;
  }

  function tep_count_payment_modules() {
    return tep_count_modules(MODULE_PAYMENT_INSTALLED);
  }

  function tep_count_shipping_modules() {
    return tep_count_modules(MODULE_SHIPPING_INSTALLED);
  }

  function tep_create_random_value($length, $type = 'mixed') {
    if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;

    $rand_value = '';
    while (strlen($rand_value) < $length) {
      if ($type == 'digits') {
        $char = tep_rand(0,9);
      } else {
        $char = chr(tep_rand(0,255));
      }
      if ($type == 'mixed') {
        if (eregi('^[a-z0-9]$', $char)) $rand_value .= $char;
      } elseif ($type == 'chars') {
        if (eregi('^[a-z]$', $char)) $rand_value .= $char;
      } elseif ($type == 'digits') {
        if (ereg('^[0-9]$', $char)) $rand_value .= $char;
      }
    }

    return $rand_value;
  }

  function tep_array_to_string($array, $exclude = '', $equals = '=', $separator = '&') {
    if (!is_array($exclude)) $exclude = array();

    $get_string = '';
    if (sizeof($array) > 0) {
      while (list($key, $value) = each($array)) {
        if ( (!in_array($key, $exclude)) && ($key != 'x') && ($key != 'y') ) {
          $get_string .= $key . $equals . $value . $separator;
        }
      }
      $remove_chars = strlen($separator);
      $get_string = substr($get_string, 0, -$remove_chars);
    }

    return $get_string;
  }

  function tep_not_null($value) {
    if (is_array($value)) {
      if (sizeof($value) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
        return true;
      } else {
        return false;
      }
    }
  }

////
// Output the tax percentage with optional padded decimals
  function tep_display_tax_value($value, $padding = TAX_DECIMAL_PLACES) {
    if (strpos($value, '.')) {
      $loop = true;
      while ($loop) {
        if (substr($value, -1) == '0') {
          $value = substr($value, 0, -1);
        } else {
          $loop = false;
          if (substr($value, -1) == '.') {
            $value = substr($value, 0, -1);
          }
        }
      }
    }

    if ($padding > 0) {
      if ($decimal_pos = strpos($value, '.')) {
        $decimals = strlen(substr($value, ($decimal_pos+1)));
        for ($i=$decimals; $i<$padding; $i++) {
          $value .= '0';
        }
      } else {
        $value .= '.';
        for ($i=0; $i<$padding; $i++) {
          $value .= '0';
        }
      }
    }

    return $value;
  }

////
// Checks to see if the currency code exists as a currency
// TABLES: currencies
  function tep_currency_exists($code) {
    $code = tep_db_prepare_input($code);

    $currency_code = tep_db_query("select currencies_id from " . TABLE_CURRENCIES . " where code = '" . tep_db_input($code) . "'");
    if (tep_db_num_rows($currency_code)) {
      return $code;
    } else {
      return false;
    }
  }

  function tep_string_to_int($string) {
    return (int)$string;
  }

////
// Parse and secure the cPath parameter values
  function tep_parse_category_path($cPath) {
// make sure the category IDs are integers
    $cPath_array = array_map('tep_string_to_int', explode('_', $cPath));

// make sure no duplicate category IDs exist which could lock the server in a loop
    $tmp_array = array();
    $n = sizeof($cPath_array);
    for ($i=0; $i<$n; $i++) {
      if (!in_array($cPath_array[$i], $tmp_array)) {
        $tmp_array[] = $cPath_array[$i];
      }
    }

    return $tmp_array;
  }

////
// Return a random value
  function tep_rand($min = null, $max = null) {
    static $seeded;

    if (!isset($seeded)) {
      mt_srand((double)microtime()*1000000);
      $seeded = true;
    }

    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }

  function tep_setcookie($name, $value = '', $expire = 0, $path = '/', $domain = '', $secure = 1) {
    setcookie($name, $value, $expire, $path, (tep_not_null($domain) ? $domain : ''), $secure);
  }

  function tep_get_ip_address() {
    if (isset($_SERVER)) {
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } else {
        $ip = $_SERVER['REMOTE_ADDR'];
      }
    } else {
      if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
      } elseif (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
      } else {
        $ip = getenv('REMOTE_ADDR');
      }
    }

    return $ip;
  }

  function tep_count_customer_orders($id = '', $check_session = true) {
    global $customer_id;

    if (is_numeric($id) == false) {
      if (tep_session_is_registered('customer_id')) {
        $id = $customer_id;
      } else {
        return 0;
      }
    }

    if ($check_session == true) {
      if ( (tep_session_is_registered('customer_id') == false) || ($id != $customer_id) ) {
        return 0;
      }
    }

    $orders_check_query = tep_db_query("select count(*) as total from " . TABLE_ORDERS . " where customers_id = '" . (int)$id . "'");
    $orders_check = tep_db_fetch_array($orders_check_query);

    return $orders_check['total'];
  }

  function tep_count_customer_address_book_entries($id = '', $check_session = true) {
    global $customer_id;

    if (is_numeric($id) == false) {
      if (tep_session_is_registered('customer_id')) {
        $id = $customer_id;
      } else {
        return 0;
      }
    }

    if ($check_session == true) {
      if ( (tep_session_is_registered('customer_id') == false) || ($id != $customer_id) ) {
        return 0;
      }
    }

    $addresses_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$id . "'");
    $addresses = tep_db_fetch_array($addresses_query);

    return $addresses['total'];
  }

// nl2br() prior PHP 4.2.0 did not convert linefeeds on all OSs (it only converted \n)
  function tep_convert_linefeeds($from, $to, $string) {
    if ((PHP_VERSION < "4.0.5") && is_array($from)) {
      return ereg_replace('(' . implode('|', $from) . ')', $to, $string);
    } else {
      return str_replace($from, $to, $string);
    }
  }

// BOF: WebMakers.com Added: Downloads Controller
//require(DIR_WS_FUNCTIONS . 'downloads_controller.php');
//require(DIR_WS_FUNCTIONS . FILENAME_DOWNLOADS_CONTROLLER);
//require(dirname(__FILE__) . '/'.FILENAME_DOWNLOADS_CONTROLLER);
// EOF: WebMakers.com Added: Downloads Controller
////
//CLR 030228 Add function tep_decode_specialchars
// Decode string encoded with htmlspecialchars()
  function tep_decode_specialchars($string){
    $string=str_replace('&gt;', '>', $string);
    $string=str_replace('&lt;', '<', $string);
    $string=str_replace('&#039;', "'", $string);
    $string=str_replace('&quot;', "\"", $string);
    $string=str_replace('&amp;', '&', $string);

    return $string;
  }

////
// saved from old code
  function tep_output_warning($warning) {
  return  (tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . ' ' . $warning);
  }
  
  // Draw a pulldown for Option Types
  function draw_optiontype_pulldown($name, $default = '') {
    $values = array();
    $values[] = array('id' => 0, 'text' => 'Select');
    $values[] = array('id' => 1, 'text' => 'Text');
    $values[] = array('id' => 2, 'text' => 'Radio');
    $values[] = array('id' => 3, 'text' => 'Checkbox');
    $values[] = array('id' => 4, 'text' => 'Text Area');
    
    return tep_draw_pull_down_menu($name, $values, $default);
  }
  
  //CLR 030312 add function to translate type_id to name
  // Translate option_type_values to english string
  function translate_type_to_name($opt_type) {
    if ($opt_type == 0) return 'Select';
    if ($opt_type == 1) return 'Text';
    if ($opt_type == 2) return 'Radio';
    if ($opt_type == 3) return 'Checkbox';
    if ($opt_type == 4) return 'Text Area';
    return 'Error ' . $opt_type;
  }

  function tep_get_box_heading($infobox_id, $languages_id) {
      $configuration_query12 = tep_db_query("select box_heading from " . TABLE_INFOBOX_HEADING . " where infobox_id = '" . (int)$infobox_id . "' and languages_id = '" . (int)$languages_id . "'");
      $configuration12 = tep_db_fetch_array($configuration_query12);
  
      return $configuration12['box_heading'];
    }
  
// Contact Us Email Subject : DMG
// PassionSeed Contact Us Email Subject begin
  function tep_get_email_subjects_list($subjects_array = '') {
    if (!is_array($subjects_array)) $subjects_array = array();

    $subjects_query = tep_db_query("select email_subjects_id, email_subjects_name, email_subjects_category from " . TABLE_EMAIL_SUBJECTS . " where email_subjects_category = '2' order by email_subjects_name");
    while ($subjects = tep_db_fetch_array($subjects_query)) {
      $subjects_array[] = array('id' => $subjects['email_subjects_name'], 'text' => $subjects['email_subjects_name']);
    }

    return $subjects_array;
  }
// PassionSeed Contact us Email Subject end

// Randomizer for specials modules ($products_id, $max_displayed)
  function cre_random_array($random,$i){
    srand((float) microtime() * 10000000);
    $rand_keys = array_rand($random, $i);
    $res = array();
    if($i > 1){
      for($a=0;$a<$i;$a++){
       $res[] = $random[$rand_keys[$a]];
      }
    }else{
      $res[] = $random[$rand_keys];   
    }
    return $res;
  }
// search heightlight modified by benny 2009/04/02
function tep_find_and_highlight($source_string, $search_keywords){
	$changed = false;
	$lower_string = strtolower($source_string);
	$position_array = array();
	for ($i = 0, $n = strlen($lower_string); $i < $n; $i++) {
		$position_array[$i] = 0;
	}
	for ($i = 0, $n = sizeof($search_keywords); $i < $n; $i++) {
		$length_keywords = strlen($search_keywords[$i]);
		if ($length_keywords > 0) {
			$keyword = strtolower($search_keywords[$i]);
			$curr_position = 0;
			do {
				$str_pos = strpos ($lower_string, $keyword, $curr_position);
				if ($str_pos !== false){
					for ($j = $str_pos, $m = $str_pos + $length_keywords; $j < $m; $j++) {
						$position_array[$j] = 1;
					}
					$curr_position = $str_pos + $length_keywords;
					$changed = true;
				}
			} while ($str_pos !== false);
		}
	}
	if ($changed == true) {
		$open_tag = false;
		$result_string = '';
		for ($i = 0, $n = sizeof($position_array); $i < $n; $i++) {
			if ($position_array[$i] == 1) {
				if ($open_tag == false) {
					$result_string .= '<span style="color:red;font-weight:bold;">';
					$open_tag = true;
				}
			}else {
				if ($open_tag == true) {
					$result_string .= '</span>';
					$open_tag = false;
				}
			}
			$result_string .= $source_string{$i};
		}
		if ($open_tag == true) { $result_string .= '</span>'; }
		return $result_string;
	}else {
		return $source_string;
	}
}
 //end  
 //alex 2010-4-26 added the function : get the small image to show
function tep_get_dest_image($small_image_source,$folder = 'xxs',$suffix=''){
	return str_replace('-s',$suffix,str_replace('small',$folder,$small_image_source));
}
/* Get Product Sale Code ---- Peter */ 
function tep_get_products_sale_code($product_id) {
	$product_query = tep_db_query("select products_model from ".TABLE_PRODUCTS." where products_id =".(int)$product_id );
	if (tep_db_num_rows($product_query)) {
		$product = tep_db_fetch_array($product_query);
		return $product['products_model'];
	} else {
		return false;
	}
}
/* Get Product Price ---- Peter */ 
function tep_get_productPrice($product_id){
	if ($product_id){
		global $languages_id,$pf;
		$product_query = tep_db_query("select p.products_id,p.products_price,products_discount from " . TABLE_PRODUCTS . " p where p.products_id=".(int)$product_id );
		if (tep_db_num_rows($product_query)) {
			$product = tep_db_fetch_array($product_query);
			$product_price = $product['products_price'];
			$rata = $product['products_discount'] > 0 ? number_format((1/(1-$product['products_discount']/100)),2,'.','') : PRODUCTS_RATE;//( $product['products_discount'] > 0 )? number_format( (1 - ($product['products_discount'] / 100)) , 2) : PRODUCTS_RATE;
			//$retail_price = $product['products_price'] * PRODUCTS_RATE;// * $rata;//
			//echo $rata;
			$pf -> loadProduct($product['products_id'],$languages_id);
			$result = array();
			$result['rsPrice'] = $pf -> getRetailSinglePrice($rata);
			$result['sPrice'] = $pf -> getSinglePrice();
			return $result;
		} else {
			return false;
		}
	}else{
		return false;
	}
}
/* Get Sub Category */
function tep_get_subCategory($tagValue){
	global $languages_id,$customer_group_id;
	//禁用的电池分类ID　@nathan 2011-11-2
	//$disable_batteries = array(1,3,21,23,25,73,111,307,314,499);
	$disable_batteries = array(23,25,73,111,307,499);
	$disable_batteries_three = array(188,191,192,193,194,195,502,556,112,115,117,126,129,136,139,141,172,175,601,181,306,312,313,500,501,562,197,198,202,204,205,206,208,210,211,212,213,217,218,220,503,504,505,521,557,558,559,560,561,563,507,508,509,510,511,512,513,525,526,527,528,529,530,531,532,533,534,535,536,537,538,539,540,541,542,543,544,545,546,547,548,549,550,551,552,553,554,555,741);
	$jsonData = array();
	if (isset($tagValue) && !empty($tagValue)){
		# 
		# URL
		# Ʒ
		# |_| |=|
		$topNavi_categories_sql = sprintf("SELECT DISTINCT C.categories_id,C.parent_id, CD.categories_name FROM %s AS CD INNER JOIN %s AS C ON CD.categories_id=C.categories_id WHERE CD.language_id=%u AND C.products_group_access like '%s' AND C.parent_id=%u ORDER BY C.sort_order ASC",TABLE_CATEGORIES_DESCRIPTION,TABLE_CATEGORIES,(int)$languages_id,"%".$customer_group_id."%",(int)$tagValue);
		$topNavi_categories_query = tep_db_query($topNavi_categories_sql);
		if (tep_db_num_rows($topNavi_categories_query) > 0){
			$i = 0;
			while ($topNavi_categories = tep_db_fetch_array($topNavi_categories_query)) {
				//禁止显示的电池分类　@nathan 2011-11-2
				if($tagValue == 408 && in_array($topNavi_categories['categories_id'], $disable_batteries)) continue;

				$products_in_category = tep_count_products_in_category($topNavi_categories['categories_id']);
				if ($products_in_category > 0){
					$jsonData[$i]['name'] = $topNavi_categories['categories_name'];
					$jsonData[$i]['url'] = tep_href_link(FILENAME_DEFAULT,tep_get_abscPath($topNavi_categories['categories_id']),'NONSSL',false);
					$jsonData[$i]['parent'] = '0';
					$jsonData[$i]['num'] = tep_count_products_in_category($topNavi_categories['categories_id']);
					$i++;
					$sub_categories_sql = sprintf("SELECT DISTINCT C.categories_id,C.parent_id, CD.categories_name FROM %s AS CD INNER JOIN %s AS C ON CD.categories_id=C.categories_id WHERE CD.language_id=%u AND C.products_group_access like '%s' AND C.parent_id=%u ORDER BY C.sort_order ASC",TABLE_CATEGORIES_DESCRIPTION,TABLE_CATEGORIES,(int)$languages_id,"%".$customer_group_id."%",(int)$topNavi_categories['categories_id']);
					$sub_categories_query = tep_db_query($sub_categories_sql);
					if (tep_db_num_rows($sub_categories_query) > 0){
						while ($sub_categories = tep_db_fetch_array($sub_categories_query)) {
							if(in_array($sub_categories['categories_id'], $disable_batteries_three)) continue;
							$products_in_subcategory = tep_count_products_in_category($sub_categories['categories_id']);
							if ($products_in_subcategory > 0){
								$jsonData[$i]['name'] = $sub_categories['categories_name'];
								$jsonData[$i]['url'] = tep_href_link(FILENAME_DEFAULT,tep_get_abscPath($sub_categories['categories_id']),'NONSSL',false);
								$jsonData[$i]['parent'] = $sub_categories['categories_id'];
								$jsonData[$i]['num'] = tep_count_products_in_category($sub_categories['categories_id']);
								$i++;
							}unset($products_in_subcategory);
						}unset($sub_categories);
					}unset($sub_categories_query,$sub_categories_sql);
				}unset($products_in_category);
			}unset($topNavi_categories);
		}unset($topNavi_categories_query,$topNavi_categories_sql);
		//echo json_encode($jsonData);
		return $jsonData;
	}else{
		return false;
	}
}
//create_html add by sblack
function create_html($html,$filename){
	$filename = DIR_FS_CATALOG_TMP.$filename;
	if(@file_put_contents($filename,$html)>0){
		return true;
	}else{
		return false;
	}
}
//取seo_url
function tep_get_seo_url($page,$params){
	//page name
	$b = basename($page);
	if(tep_not_null($params)){
		parse_str($params,$arr_params);
		//about products_id,cpath,activities
		if(count($arr_params)==1){
			foreach($arr_params as $key=>$v){
				switch($key){
					case "products_id" :
						$url_org = HTTP_SERVER.DIR_WS_HTTP_CATALOG.$b."?products_id=".$v;
						break;
					case "cPath" :
						$url_org = HTTP_SERVER.DIR_WS_HTTP_CATALOG.$b."?cPath=".$v;
						break;
					case "activities" :
						$url_org = HTTP_SERVER.DIR_WS_HTTP_CATALOG.$b."?activities=".$v;
						break;
					default :
						$url_org = '';
				}
			}
		}
	}else{	//no param url
		$url_org = HTTP_SERVER.DIR_WS_HTTP_CATALOG.$b;
	}
	//select seo_url by url_org
	if(tep_not_null($url_org)){
		$sql = "select seo_url_get from seo_url where seo_url_org='".$url_org."'";
		$result = tep_db_query($sql);
		$arr_url = tep_db_fetch_array($result);
		return $arr_url["seo_url_get"];
	}else{
		return '';
	}
}

 
/**
 * 递归方式的对变量中的html编码进行过滤
 * 
 * @author Alex 2011-5-7
 * @access public 
 * @param mix $value 
 * @return mix 
 */
function htmlspecialchars_deep($value) {
	if (empty($value)) {
		return $value;
	} else {
		// htmlspecialchars
		return is_array($value) ? array_map('htmlspecialchars_deep', $value) : htmlentities(tep_sanitize_javascript($value));
	} 
} 
/**
* 创建验证码
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function create_verify_code(){
	$visual_verify_code = ""; 
	for ($i = 1; $i <= rand(3,6); $i++){
		$visual_verify_code = $visual_verify_code . substr(VISUAL_VERIFY_CODE_CHARACTER_POOL, rand(0, strlen(VISUAL_VERIFY_CODE_CHARACTER_POOL)-1), 1);
	}
	$vvcode_oscsid = tep_session_id();
	tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . $vvcode_oscsid . "'");
	$sql_data_array = array('oscsid' => $vvcode_oscsid, 'code' => $visual_verify_code);
	tep_db_perform(TABLE_VISUAL_VERIFY_CODE, $sql_data_array);
	return $visual_verify_code;
}
/**
* 获取验证码
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function get_verify_code(){
	$code_query = tep_db_query("select code from " . TABLE_VISUAL_VERIFY_CODE . "  where oscsid = '" . tep_session_id() . "'");
	$code_array = tep_db_fetch_array($code_query);
	return $code_array['code'];
}
/**
* 检查验证码是否正确
* @authoer nathan 2011-5-18
* @access public 
* @param $input_code String 验证码
* @return boolean
*/
function check_verify_code($input_code){
	$code = get_verify_code();
	if($code && $input_code && strcasecmp($input_code, $code) == 0) return true;
	return false;
}
/**
* 删除验证码
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function delete_verify_code(){
	tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . tep_session_id() . "'"); 
}
/**
* 判断客户邮箱是否存在
* @authoer nathan 2011-5-18
* @access public 
* @param $email_address String 邮箱地址
* @return bollean
*/
function check_customer_email_exist($email_address){
	$check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $email_address. "'");
	$check_email = tep_db_fetch_array($check_email_query);
	return (isset($check_email['total']) && $check_email['total'] > 0 ) ? true : false;
}
/**
* 验证客户是否有效
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function check_customer_valid($email, $pwd){
	$ret = false;
	$query = tep_db_query("select customers_password from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $email. "'");
	$row = tep_db_fetch_array($query);
	if($row){
		$ret = tep_validate_password($pwd,$row['customers_password']);
	}
	return $ret;
}
/**
* 获取客户收货地址列表
* @authoer nathan 2011-5-18
* @access public 
* @param $customer_id Int 客户ID
* @return bollean
*/
function tep_customer_address_format($customer_id){
	$addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "'");
	$result = null;
	while($rows = tep_db_fetch_array($addresses_query)){
		$format_id = tep_get_address_format_id($rows['country_id']);
		$result[$rows['address_book_id']] = tep_address_format($format_id, $rows, true, ' ', ', ');
	}
	return $result;
}
/**
* 释放资源
* @authoer nathan 2011-5-18
* @access public 
* @param 
* @return 
*/
function ajax_die(){
	tep_session_close();
	tep_db_close();
	die();
}
/**
* 获取产品图片路径资料
* @authoer nathan 
* @access public 
* @param $products_model String 产品编码
* @param $products_info Array 产品信息 非必填值
* @return Array
*/
function tep_get_products_images($products_model, $products_info = null){
	$images = array();
	$images['products_image_med'] = 'medium/'.$products_model.'-m.JPG';
	$images['products_image_lrg'] = 'large/'.$products_model.'-l.JPG';
	for ($i=1; $i<=6; $i++){
		$images['products_image_sm_' . $i] = 'small/'.$products_model.'_'.$i.'-s.JPG';
		$images['products_image_xl_' . $i] = 'large/'.$products_model.'_'.$i.'-l.JPG';
	}
	if($products_info) $images = array_merge($products_info, $images);
	return $images;
}
/**
* 获取指定类型的图片路径
* @authoer nathan 
* @access public 
* @param $products_model String 产品编码
* @param $type string 图片类型 small|medium|large
* @return 
*/
function tep_get_products_image_str($products_model, $type = 'small'){
	$image = 'small/'.$products_model.'-s.JPG';
	switch ($type){
		case 'medium':
			$image = 'medium/'.$products_model.'-m.JPG';
			break;
		case 'large':
			$image = 'large/'.$products_model.'-l.JPG';
			break;
	}
	return $image;
}

/**
* 检查秒杀产品每个客户购买的最大数量
* @authoer nathan 
* @access public 
* @param $products_id	Int 产品ID
* @param $buy_num		Int 购买数量
* @return bool
*/
function tep_check_seckill_user_num($products_id, $buy_num){
	if($seckill_user_num = tep_seckill_user_num($products_id)){
		if($buy_num > $seckill_user_num){
			return false;
		}
	}
	return true;
}
/**
* 获取秒杀产品每个客户购买的最大数量
* @authoer nathan 
* @access public 
* @param $products_id Int 产品ID
* @return 
*/
function tep_seckill_user_num($products_id){
	$num = 0;
	$seckill_query = tep_db_query('select user_per_qty from '. TABLE_PRODUCTS_SECKILL . ' where products_id = "' . $products_id . '" and seckill_status = "y" and seckill_exptime > now()');
	if(tep_db_num_rows($seckill_query) > 0){
		$seckill_info = tep_db_fetch_array($seckill_query);
		$num = $seckill_info['user_per_qty'];
	}
	return $num;
}
/**
* 秒转化为时间
* @authoer nathan 
* @access public 
* @param $time int		秒数
* @param $text String	异常时显示的描述
* @return String
*/
function format_time_str($time, $text = ''){
	if($time <=0){
		if($text) return $text;
		return '00:00:00';
	}

	$s = $time % 60;
	$m = $time / 60 % 60;
	$h = floor($time / 3600);
	return sprintf('%d:%d:%d', $h, $m, $s);
}
/**
* 返回图片宽高值最大的HTML属性代码
* @authoer nathan 
* @access public 
* @param String $img_url 图片地址
* @param int $size 指定长度
* @return 
*/
function get_image_max($img_url, $size){
	list($width, $height) = getimagesize($img_url);
	if($width > $height){
		$str = 'width="'.$size.'"';
	}else{
		$str = 'height="'.$size.'"';
	}
	return $str;
}
/**
* 发送通用的模版邮件
* @authoer nathan 
* @access public 
* @param Int	$customers_id 客户ID
* @param String	$title 标题
* @param String	$content 内容 
* @return Bool
*/
function tep_general_email($customers_id, $title, $content){
	$file = DIR_FS_CATALOG . DIR_WS_TEMPLATES . 'email/general.html';
	if(!file_exists($file)) return false;
	$c_sql = 'select customers_email_address, concat(customers_firstname," ",customers_lastname) customers_name from ' . TABLE_CUSTOMERS . ' where customers_id = "'. $customers_id .'"';
	$c_info = tep_db_fetch_array(tep_db_query($c_sql));
	if(!$c_info) return false;
	$customers_name = $c_info['customers_name'];
	$email = $c_info['customers_email_address'];
	$email_template = file_get_contents($file);
	$email_content = str_replace(array('{EMAIL_CUSTOMER_NAME}','{EMAIL_CONTNET}',"\r\n","\n","\r"), array($customers_name ,$content, '', '', ''), $email_template);
	return tep_mail($customers_name, $email, $title, $email_content, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
}
/**
* 获取当前使用语言的CODE
* @authoer nathan 
* @access public 
* @param G-String $GLOBALS['language'] eg: english
* @return 
*/
function tep_get_language_code(){
	$code = 'en';
	$sql = 'select * from '. TABLE_LANGUAGES . ' where directory="'. $GLOBALS['language'] .'"';
	$info = tep_fetch_array(tep_db_query($sql));
	if($info){
		$code = $info['code'];
	}
	return $code;
}

/**
* 获取缩略图地址
* @authoer nathan 
* @access public 
* @param String $path 图片地址
* @param Mix $path 排除的字符串
* @return String
*/
function tep_get_image_thumb($path, $except = null){
	if($except){
		if(!is_array($except)) $except = array($except);
		foreach ($except as $key => $val){
			if(strpos($path, $val) !== false) return $path;
		}
	}
	$arr = pathinfo($path);
	return $arr['dirname'] . '/' . $arr['filename'] . '_thumb.' . $arr['extension'];
}
/**
* 获取YouTube的视频code
* @authoer nathan 
* @access public 
* @param String $url YouTube视频地址
* @return String
*/
function tep_get_youtube_vcode($url){
	$info = pathinfo($url);
	if(empty($info)) return '';
	parse_str($info['filename'],$arr);
	if(isset($arr['watch?v'])) return $arr['watch?v'];
	return '';
}
/**
* 换行符转换为<br/>
* @authoer nathan 
* @access public 
* @param  String $str 字符串
* @return String
*/
function tep_nl2br($str){
	while(strpos($str, '\r\n\r\n') !== false){
		$str = str_replace('\r\n\r\n', '\r\n', $str);
	}
	return str_replace('\r\n', '<br/>', $str);
}
/**
* 获取指定语言的链接
* @authoer nathan 
* @access public 
* @param String $uri 链接地址
* @param String $language_code 语言代码 eg: en,ru
* @return String
*/
function tep_langauge_url($uri, $language_code){
	$url = $uri;
	if(strpos($uri, '.html') !== FALSE){
		if($language_code == 'en'){
			if(strlen(DIR_WS_HTTP_CATALOG) > 1)	$url = DIR_WS_HTTP_CATALOG . trim(str_replace(DIR_WS_HTTP_CATALOG, '/', $uri), '/');
			
		}else{
			$url = DIR_WS_HTTP_CATALOG . $language_code . str_replace(DIR_WS_HTTP_CATALOG, '/', $uri);
		}
	}else{
		if(strpos($uri, '?') !== false){
			$url = tep_url_param_exclude($uri, 'language').'&language='.$language_code;
		}else{
			$url = $uri.'?language='.$language_code;
		}
	}
	return $url;
}
/**
* 获取去掉指定参数的链接
* @authoer nathan 
* @access public 
* @param String $url_str 链接地址
* @param String $exclude_key 去掉换参数键值
* @return String
*/
function tep_url_param_exclude($url_str, $exclude_key){
	$result = '';
	$url_arr = parse_url($url_str);
	if($url_param = $url_arr['query']){
		parse_str($url_param, $arr_param);
		if(array_key_exists($exclude_key, $arr_param)) unset($arr_param[$exclude_key]);
		$result = '?'.http_build_query($arr_param);
	}
	return $url_arr['path'].$result;
}
/**
* 从其他语言版链接转换成正常的SEO链接
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function tep_translate_seo_url($url){
	$http_param = parse_url($url);
	if(empty($http_param)) return $url;
	$path = substr($http_param['path'], strrpos($http_param['path'], '/'));
	return $http_param['scheme'] .'://'. $http_param['host'] . $path;
}
/**
* 处理支付的货币
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function check_currency_type($currency_val){
	$currency_change_usd_arr = array('RUB');
	if(in_array($currency_val, $currency_change_usd_arr)){
		$currency_val = 'USD';
	}
	return $currency_val;
}

/**
* 
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function is_russia_language(){
	global $languages_code;
	return $languages_code == 'ru' ? true : false;
}

/**
* 调用BlueHornet API的事务功能，给指定邮箱发送邮件
*
* @authoer clock
* @access public 
* @param string $to_name    收件人名称
* @param string $to_email_address    收件人邮箱地址
* @param string $from_name    发件人名称
* @param string $from_email_address    发件人邮箱地址
* @param string $email_subject    邮件主题
* @param string $email_content    邮件内容
* @param string $template_id='830133'    预先创建好的事务邮件模板id
* @return boolean
* @last modify 2012-03-15 10:42
*/
function tep_bh_transac_mail($to_name, $to_email_address, $from_name, $from_email_address, $email_subject, $email_content, $template_id='830133'){
	$sendArr = array();
	//处理ESPOW<cs@espow.com>格式
	if($to_name == '' && strpos($to_email_address,'<')){
		preg_match('/(.*)\<(.*)\>/', $to_email_address, $match_ret);
		if(count($match_ret) == 3){
			$to_name = $match_ret[1];
			$to_email_address = $match_ret[2];
		}else{
			return false;
		}
	}
	//组织需要发送的数据
	$sendArr['template_id'] = $template_id;
	$sendArr['name'] = $to_name;
	$sendArr['email'] = $to_email_address;
	$sendArr['from_description'] = $from_name;
	$sendArr['from_email'] = $from_email_address;
	$sendArr['reply_email'] = $from_email_address;
	$sendArr['subject'] = $email_subject;
	$sendArr['html_content'] = $email_content;
	$sendArr['plain_content'] = strip_tags(str_replace(array('<br />','<br>','<br/>'), array("\n","\n","\n"),$email_content));
	

	//print_r($sendArr);die();
	//调用BH类，发送组织好的数据
	$bh = new BlueHornetAPICall(BH_SERVER_URL,BH_API_KEY,BH_SHARED_SECRET);
	//调用BH API中的事务邮件功能
	$bh->addmethodCall('transactional.sendtransaction',$sendArr);		
	//提交数据
	$bh->postXML();
	//获得提交结果并解析后的数组
	$resArr = $bh->parseResponse();
	//通过执行状态判断结果
	if($resArr[0]['state'] == true){
		$ret = true;
	}else{
		$ret = false;
	}
	unset($bh,$resArr);
	return $ret;
}
/**
* 
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function check_utf8($str){
	if(!mb_check_encoding($str, 'utf-8')){
		return utf8_encode($str);
	}
	return $str;
}

function tep_get_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false, $customer_group_id = NULL) {
    global $languages_id;

    if (!is_array($category_tree_array)) $category_tree_array = array();
    if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);

    if ($include_itself) {
      $category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '" . (int)$languages_id . "' and cd.categories_id = '" . (int)$parent_id . "'");
      $category = tep_db_fetch_array($category_query);
      $category_tree_array[] = array('id' => $parent_id, 'text' => $category['categories_name']);
    }

	if ($customer_group_id == NULL){
		$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' order by c.sort_order, cd.categories_name");
	   }else{
		$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' and (c.products_group_access like \"%,".$customer_group_id.",%\" or c.products_group_access like \"".$customer_group_id.",%\" or c.products_group_access like \"%,".$customer_group_id."\" or c.products_group_access='".$customer_group_id."') order by c.sort_order, cd.categories_name");
	}

   while ($categories = tep_db_fetch_array($categories_query)) {
      if ($exclude != $categories['categories_id']) $category_tree_array[] = array('id' => $categories['categories_id'], 'text' => $spacing . $categories['categories_name']);
      $category_tree_array = tep_get_category_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
    }

    return $category_tree_array;
  }

/**
* 
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function get_currency_list(){
    $currency_query = tep_db_query("select * from " . TABLE_CURRENCIES);
	$list = array();
	while($row = tep_db_fetch_array($currency_query)){
		$list[] = array('id' => $row['code'], 'text' => $row['title']);
	}
	return $list;
}
?>
