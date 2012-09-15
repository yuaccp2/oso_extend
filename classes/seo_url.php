<?php
/*
//----------------------------------------------------------------------------
// Copyright (c) 2006-2007 Asymmetric Software - Innovation & Excellence
// Author: Mark Samios
// http://www.asymmetrics.com
// SEO-G URL class
// Processes SEO tables and urls, generates seo links
// Support for osCommerce core and contributions:
// - Products, Categories, Manufacturers
// - Articles, Topics, Authors
// - Link Categories
// - Information Pages Unlimitied
//----------------------------------------------------------------------------
// Script is intended to be used with:
// osCommerce, Open Source E-Commerce Solutions
// http://www.oscommerce.com
// Copyright (c) 2003 osCommerce
//----------------------------------------------------------------------------
// Released under the GNU General Public License
//----------------------------------------------------------------------------
*/

class seoURL {
	var $path, $query, $params_array, $error_level, $handler_flag;
	/**
	* 分类名称列表
	* @type array
	*/
	var $seo_categories_list = array();
	/**
	* 生成分类SEO链接的列表信息
	* @type Array 二维
	*/
	var $seo_types_list = array();
	
	function seoURL() {
		$this->path = $this->query = '';
		$this->params_array = array();
		$this->query_array = array();
		$this->error_level = 0;
		//减少对数据库的查询次数，储存在当前实例的变量中
		$this->get_seo_categories_list(true);
		$this->get_seo_types_list(true);
	}
	/**
	* 获取分类名称列表
	* @authoer nathan 
	* @access public 
	* @param $anew bool 直接从数据库获取
	* @return Array
	*/
	function get_seo_categories_list($anew = false){
		if(!$anew && $this->seo_categories_list) return $this->seo_categories_list;
		$query = tep_db_query('select * from '. TABLE_SEO_TO_CATEGORIES);
		$list = array();
		while($info = tep_db_fetch_array($query)){
			$list[$info['categories_id']] = $info['seo_name'];
		}
		$this->seo_categories_list = $list;
		unset($list,$info,$query);
		return $this->seo_categories_list;
	}
	/**
	* 获取指定的分类名称
	* @authoer nathan 
	* @access public 
	* @param $id 分类ID
	* @return String
	*/
	function get_seo_categories_name($id){
		$name = '';
		$this->get_seo_categories_list();
		if(isset($this->seo_categories_list[$id]) && $this->seo_categories_list[$id]){
			$name = $this->seo_categories_list[$id];
		}
		return $name;
	}
	/**
	* 获取生成分类SEO链接的列表信息
	* @authoer nathan 
	* @access public 
	* @param $anew 直接从数据库读取
	* @return Array 二维
	*/
	function get_seo_types_list($anew = false){
		if(!$anew && $this->seo_types_list) return $this->seo_types_list;
		$query = tep_db_query("select s2c.categories_id, s2c.seo_name, st.sort_order, st.seo_types_linkage, st.seo_types_prefix, st.seo_types_handler from " . TABLE_SEO_TO_CATEGORIES . " s2c left join " . TABLE_SEO_TYPES . " st using(seo_types_id) where st.seo_types_status='1'");
		$list = array();
		while($info = tep_db_fetch_array($query)){
			$list[$info['categories_id']] = $info;
		}
		$this->seo_types_list = $list;
		unset($query,$list,$info);
		return $this->seo_types_list;
	}
	/**
	* 获取指定生成分类SEO的信息
	* @authoer nathan 
	* @access public 
	* @param $c_id 分类ID
	* @return Array
	*/
	function get_set_types_info($c_id){
		$this->get_seo_types_list();
		if(isset($this->seo_types_list[$c_id]) && $this->seo_types_list[$c_id]){
			return $this->seo_types_list[$c_id];
		}
		return null;
	}
	
	function create_safe_string($string, $separator=SEO_DEFAULT_WORDS_SEPARATOR) {
		$string = preg_replace('/\s\s+/', ' ', trim($string));
		$string = preg_replace("/[^0-9a-z\-_\/]+/i", $separator, strtolower($string));
		$string = trim($string, $separator);
		$string = str_replace($separator . $separator . $separator, $separator, $string);
		return $string;
	}

    function create_safe_name($string, $separator=SEO_DEFAULT_WORDS_SEPARATOR) {
		$string = preg_replace('/\s\s+/', ' ', trim($string));
		$string = preg_replace("/[^0-9a-z\-_]+/i", $separator, strtolower($string));
		$string = trim($string, $separator);
		$string = str_replace($separator . $separator . $separator, $separator, $string);
		if(SEO_DEFAULT_WORD_LENGTH > 1) {
			$words_array = explode($separator, $string);
			if( is_array($words_array) ) {
				for($i=0, $j=count($words_array); $i<$j; $i++) {
					if(strlen($words_array[$i]) < SEO_DEFAULT_WORD_LENGTH) {
						unset($words_array[$i]);
					}
				}
				if(count($words_array)) {$string = implode($separator, $words_array);}
			}
		}
		return $string;
    }
		
    // Get osc url from a passed seo url[根据优化后的URL获取对应URL的参数]
    function get_osc_url($seo_url, &$url, &$url_params, &$url_parse) {
		// Validate REQUEST_URI in case we got a redirect from a server script. May needed with some servers
		$this -> validate_uri($seo_url);
		$url = $url_params = $url_parse = $result = false;
		$seo_left = explode('?', $seo_url);
		if( !is_array($seo_left) ) {
			$url_parse = parse_url($seo_url);
			return $result;
		}
		$key = md5($seo_left[0]);
		if( !isset($seo_left[1]) ) {$seo_left[1] = '';}
		$this->check_redirection($key, $seo_left[1]);
        $check_query = tep_db_query("select seo_url_get, seo_url_org from ".TABLE_SEO_URL." where seo_url_key = '".tep_db_input($key)."'");
        if( $seo_array = tep_db_fetch_array($check_query) ) {
            $url = $seo_array['seo_url_org'];
            $url_parse = parse_url($url);
            if( !isset($url_parse['query']) ) {
                $url_query = '';
            } else {
                $url_query = $url_parse['query'];
            }
            $url_params = explode('&', $url_query);
            if( !is_array($url_params) ) {
                $url_params = array();
            }
            tep_db_query("update ".TABLE_SEO_URL." set seo_url_hits = seo_url_hits+1 where seo_url_key = '".tep_db_input($key)."'");
            $result = true;
        
        } else {
            $url_parse = parse_url($seo_url);
        }

		return $result;
	}

	/**
	* 二次封装get_seo_url方法 用于多语言版的链接
	* @authoer nathan 
	* @access public 
	* @param 
	* @return 
	*/
	function get_seo_url($url, &$separator, $store=true){
		$url = $this->base_get_seo_url($url, $separator, $store);
		return $this->language_url_dispose($url, $_SESSION['languages_code']);
	}
	// Convert osc url to an html url. Do not pass the session name/id to this function
	function base_get_seo_url($url, &$separator, $store=true) {
		if( SEO_DEFAULT_ENABLE == 'false' ) {
			return $url;
		}

		//对程序暂不支持生成SEO链接进行过滤，比如:产品分页page｜货币currency
		if(strpos($url,'page') || strpos($url,'currency')) return $url;

		//error_log($url."\n", 3, DIR_FS_CATALOG.'debug/seo_url_acount.txt');
		// Check if the url already recorded, if so skip processing
		if( SEO_CONTINUOUS_CHECK == 'false' ) {
			$check_query = tep_db_query("select seo_url_get from " . TABLE_SEO_URL . " where seo_url_org = '" . tep_db_input(tep_db_prepare_input($url)) . "'");
			if( $seo_array = tep_db_fetch_array($check_query) ) {
			  $separator = '?';
			  return $seo_array['seo_url_get'];
			}
		}

		$seo_url = '';
		$result = $this->parse_params($url, $seo_url);
		if( !$result || $store !== true ) {
			return $url;
		}

		$key = md5($seo_url);
		
		// Redirection double-check. Do not build url if a redirect exists.
		if( $this->check_redirection($key, '', true) ) {return $url;}

		$check_query = tep_db_query("select seo_url_get, seo_url_org from " . TABLE_SEO_URL . " where seo_url_key = '" . tep_db_input($key) . "'");
		if( $seo_array = tep_db_fetch_array($check_query) ) {
			// Note: SEO_CONTINUOUS_CHECK switch = true, should be used for short periods of time as it significantly increases latency.
			if( $seo_array['seo_url_org'] != $url && SEO_CONTINUOUS_CHECK == 'true' ) {
				tep_db_query("delete from " . TABLE_SEO_URL . " where seo_url_key = '" . tep_db_input($key) . "'");
				$sql_data_array = array(
									  'seo_url_key' => tep_db_prepare_input($key),
									  'seo_url_get' => tep_db_prepare_input($seo_url),
									  'seo_url_org' => tep_db_prepare_input($url),
									  'date_added' => 'now()'
									 );
				tep_db_perform(TABLE_SEO_URL, $sql_data_array);
			}
		} else {
			$sql_data_array = array(
									'seo_url_key' => tep_db_prepare_input($key),
									'seo_url_get' => tep_db_prepare_input($seo_url),
									'seo_url_org' => tep_db_prepare_input($url),
									'date_added' => 'now()'
								   );
			tep_db_perform(TABLE_SEO_URL, $sql_data_array);
			
		}
		$separator = '?';
		return $seo_url;
	}

	function parse_params(&$url, &$seo_url) {
		$this -> error_level = 0;
		$result = false;
		$seo_url = '';
		$url = trim($url, '&');
		$seo_array = parse_url($url);
		// Validate result
		if( !is_array($seo_array) || !isset($seo_array['path']) ) {
			return $result;
		}
		$this-> path = basename($seo_array['path']);
		// Process the query part.
		$query = isset($seo_array['query']) ? $seo_array['query'] : '';
		
		if( tep_not_null($query) ) {
			$query = htmlspecialchars(urldecode($query));
			$query = str_replace('&amp;', '&', $query);
		}
		$this->query = $query;
		
		// Check exclusion list scripts and parameters
		if( $this->exclude_script() ) {
			return $result;
		}
		// Store original query
		$osc_query = $query;
		
		$fragment = isset($seo_array['fragment'])?$seo_array['fragment']:'';
		$osc_path = $path = $seo_array['path'];
		if( tep_not_null($query) ) {
			if( count($this->params_array) ) {
				$other = false;
				$result = $this->translate_params($other, $query);
				// Check if safe mode is on and unknown parameters were detected, in which case abort.
				if( $other && SEO_DEFAULT_SAFE_MODE == 'true') {
					return false;
				}
				if($result == 2) {
					$this->error_level = 2;
					return false;
				}
			}
			$query = $this->create_safe_string($query, SEO_DEFAULT_PARTS_SEPARATOR);
		}

		if( tep_not_null($fragment) ) {
			$fragment = SEO_DEFAULT_PARTS_SEPARATOR . $fragment;
		}
		if( tep_not_null($path) ) {
			if( tep_not_null($query) || tep_not_null($fragment) ) {
				if($result == 1) {
					$tmp_array = explode('/', $path);
					$count = is_array($tmp_array)?count($tmp_array):0;
					if( $count ) {
						unset($tmp_array[$count-1]);
						$path = implode('/', $tmp_array);
					} else {
						$path = '';
					}
					$path .= '/';
				} else {
					$path = str_replace('.php', SEO_DEFAULT_INNER_SEPARATOR, $path);
				}
			} else {
				$path = str_replace('.php', '', $path);
			}
		}

		if( tep_not_null($osc_query) ) {
			$this->eliminate_session();
			if( count($this->params_array) ) {
				$osc_query = '?' . implode('&', $this->params_array);
			} else {
				$osc_query = '';
			}
		}
		
		$url = $seo_array['scheme'] . '://' .  $seo_array['host'] . $osc_path . $osc_query;
		$seo_url = $seo_array['scheme'] . '://' .  $seo_array['host'] . $path . $query . $fragment . SEO_DEFAULT_EXTENSION;
		$seo_url = str_replace('___', '_', $seo_url);
		return true;
	}

    // Convert supported url parameters
    function translate_params(&$other, &$query) {
      $this->handler_flag = $other = false;
      $result = 0;
      $flags_array = array('other' => false);
      $seo_params_array = array();
      $params_array = array();
      $array_and = $this->params_array;
      foreach ($array_and as $key => $value) {
        $inner = explode('=', $value);
        if( !is_array($inner) || count($inner) != 2) {
          if( SEO_STRICT_VALIDATION == 'false' ) {
            $this->assign_default($params_array, $value);
          }
          $flags_array['other'] = true;
          continue;
        }
        // No Sessions should ever passed to this class and this is going to be enforced.
        if( $inner[0] == tep_session_name() ) {
          continue;
        }
        switch($inner[0]) {
			//case 'pID':
			case 'products_id':
				if( isset($flags_array['products_id']) || !tep_not_null($inner[1]) ) {break;}
				// Do not handle attributes. If detected signal abort. This is effective for Safe Mode only
				if(stristr($inner[1], '{') ) {
					$flags_array['other'] = true;
				} elseif( !is_numeric($inner[1]) ) {
					return 2;
				}

				$this->auto_builder($inner[0], $inner[1]);
				$params_query_raw = "select s2p.seo_name, st.sort_order, st.seo_types_linkage, st.seo_types_prefix, st.seo_types_handler from " . TABLE_SEO_TO_PRODUCTS . " s2p left join " . TABLE_SEO_TYPES . " st on (s2p.seo_types_id=st.seo_types_id) where st.seo_types_status='1' and s2p.products_id = '" . (int)$inner[1] . "'";
				if( !$this->set_id($params_query_raw, $seo_params_array) ) {
					$this->assign_default($params_array, $value);
				}
				$flags_array['products_id'] = $inner[1];
				break;
			case 'cPath':
				if( isset($flags_array['cpath']) || !tep_not_null($inner[1]) ) break;
				$path_flag = false;
				$path_link = explode('_', $inner[1]);
				$tmp_array = array();
				$depth = 0;
				$sort_order = 0;
				foreach ($path_link as $key2 => $value2 ) {
					if(!$value2) {continue;}
					if( !is_numeric($value2) ) {
						return 2;
					}
					$this -> auto_builder($inner[0], $value2);
					$params_query_raw = "select s2c.seo_name, st.sort_order, st.seo_types_linkage, st.seo_types_prefix, st.seo_types_handler from " . TABLE_SEO_TO_CATEGORIES . " s2c left join " . TABLE_SEO_TYPES . " st on (s2c.seo_types_id=st.seo_types_id) where st.seo_types_status='1' and s2c.categories_id = '" . (int)$value2 . "'";
					$types_info = $this->get_set_types_info($value2);
					$path_flag = $this->set_path($types_info, $tmp_array, $depth, $sort_order);
					if( !$path_flag ) {
						break;
					}
				}
				if( $path_flag ) {
					$final_path = implode(SEO_DEFAULT_INNER_SEPARATOR, $tmp_array);
					$seo_params_array = array_merge( array($final_path => $sort_order), $seo_params_array);
				} else {  
                    //alex 2011-10-5 modified: if seo_to_categories has no record
                    if( !$this->set_id($params_query_raw, $seo_params_array) ) {
                        $this->assign_default($params_array, $value);
                    }
					//$this->assign_default($params_array, $value);
				}
				$flags_array['cpath'] = $inner[1];
				break;
          case 'manufacturers_id':
            if( isset($flags_array['manufacturers_id']) || !tep_not_null($inner[1]) ) break;
            if( !is_numeric($inner[1]) ) {
              return 2;
            }
            
            $this->auto_builder($inner[0], $inner[1]);
            $params_query_raw = "select s2m.seo_name, st.sort_order, st.seo_types_linkage, st.seo_types_prefix, st.seo_types_handler from " . TABLE_SEO_TO_MANUFACTURERS . " s2m left join " . TABLE_SEO_TYPES . " st on (s2m.seo_types_id=st.seo_types_id) where st.seo_types_status='1' and s2m.manufacturers_id = '" . (int)$inner[1] . "'";
            if( !$this->set_id($params_query_raw, $seo_params_array) ) {
              $this->assign_default($params_array, $value);
            }
            $flags_array['manufacturers_id'] = $inner[1];
            break;
          case 'page':
            if( isset($flags_array['page']) || !tep_not_null($inner[1]) ) break;
            if( !is_numeric($inner[1]) ) {
              return 2;
            }
            $handler = '';
            if( !$this->handler_flag && count($flags_array) == 1 ) {
              $handler = str_replace('.php', SEO_DEFAULT_INNER_SEPARATOR, $this->path);
              $this->handler_flag = true;
            }
            $seo_params_array[$handler . 'p' . $inner[1]] = '99' . '_' . '-1';
            $flags_array['page'] = $inner[1];
            break;
				// 2011-01-12 Peter
				case 'activities': 
					if( isset($flags_array['activities']) || !tep_not_null($inner[1]) ) break;
					$handler = '';
					if( !$this->handler_flag && count($flags_array) == 1 ) {
						$handler = str_replace('.php', SEO_DEFAULT_INNER_SEPARATOR, $this->path);
						$this->handler_flag = true;
					}
					$seo_params_array[$handler . $inner[1]] = 'ultrasonic_cleaner';
					$flags_array['activities'] = $inner[1];
					break;

//-MS- Use only if the articles manager is fully installed
/*
          case 'articles_id':
            if( isset($flags_array['articles_id']) || !tep_not_null($inner[1]) ) break;
            if( !is_numeric($inner[1]) ) {
              return 2;
            }
            $this->auto_builder($inner[0], $inner[1]);
            $params_query_raw = "select s2a.seo_name, st.sort_order, st.seo_types_linkage, st.seo_types_prefix, st.seo_types_handler from " . TABLE_SEO_TO_ARTICLES . " s2a left join " . TABLE_SEO_TYPES . " st on (s2a.seo_types_id=st.seo_types_id) where st.seo_types_status='1' and s2a.articles_id = '" . (int)$inner[1] . "'";
            if( !$this->set_id($params_query_raw, $seo_params_array) ) {
              $this->assign_default($params_array, $value);
            }
            $flags_array['articles_id'] = $inner[1];
            break;
          case 'authors_id':
            if( isset($flags_array['authors_id']) || !tep_not_null($inner[1]) ) break;
            if( !is_numeric($inner[1]) ) {
              return 2;
            }
            $this->auto_builder($inner[0], $inner[1]);
            $params_query_raw = "select s2a.seo_name, st.sort_order, st.seo_types_linkage, st.seo_types_prefix, st.seo_types_handler from " . TABLE_SEO_TO_AUTHORS . " s2a left join " . TABLE_SEO_TYPES . " st on (s2a.seo_types_id=st.seo_types_id) where st.seo_types_status='1' and s2a.authors_id = '" . (int)$inner[1] . "'";
            if( !$this->set_id($params_query_raw, $seo_params_array) ) {
              $this->assign_default($params_array, $value);
            }
            $flags_array['authors_id'] = $inner[1];
            break;
          case 'tPath':
            if( isset($flags_array['tpath']) || !tep_not_null($inner[1]) ) break;
            $path_link = explode('_', $inner[1]);
            $tmp_array = array();
            $depth = 0;
            $sort_order = 0;
            foreach ($path_link as $key2 => $value2 ) {
              if(!$value2) continue;
              if( !is_numeric($value2) ) {
                return 2;
              }
              $this->auto_builder($inner[0], $value2);
              $params_query_raw = "select s2t.seo_name, st.sort_order, st.seo_types_linkage, st.seo_types_prefix, st.seo_types_handler from " . TABLE_SEO_TO_TOPICS . " s2t left join " . TABLE_SEO_TYPES . " st on (s2t.seo_types_id=st.seo_types_id) where st.seo_types_status='1' and s2t.topics_id = '" . (int)$value2 . "'";
              $path_flag = $this->set_path($params_query_raw, $tmp_array, $depth, $sort_order);
              if( !$path_flag ) {
                break;
              }
            }
            if( $path_flag ) {
              $final_path = implode(SEO_DEFAULT_INNER_SEPARATOR, $tmp_array);
              $seo_params_array = array_merge( array($final_path => $sort_order), $seo_params_array);
            } else {
              $this->assign_default($params_array, $value);
            }
            $flags_array['tpath'] = $inner[1];
            break;
*/
//-MS- Use only if the articles manager is fully installed EOM

//-MS- Use only if the information pages unlimited is fully installed
/*
          case 'info_id':
            if( isset($flags_array['info_id']) || !tep_not_null($inner[1]) ) break;
            if( !is_numeric($inner[1]) ) {
              return 2;
            }
            $this->auto_builder($inner[0], $inner[1]);
            $params_query_raw = "select s2i.seo_name, st.sort_order, st.seo_types_linkage, st.seo_types_prefix, st.seo_types_handler from " . TABLE_SEO_TO_INFORMATION . " s2i left join " . TABLE_SEO_TYPES . " st on (s2i.seo_types_id=st.seo_types_id) where st.seo_types_status='1' and s2i.information_id = '" . (int)$inner[1] . "'";
            if( !$this->set_id($params_query_raw, $seo_params_array) ) {
              $this->assign_default($params_array, $value);
            }
            $flags_array['info_id'] = $inner[1];
            break;
*/
//-MS- Use only if the information pages unlimited is fully installed EOM

//-MS- Use only if the Links Manager is fully installed
/*
          case 'lPath':
            if( isset($flags_array['lpath']) || !tep_not_null($inner[1]) ) break;
            if( !is_numeric($inner[1]) ) {
              return 2;
            }
            $this->auto_builder($inner[0], $inner[1]);
            $params_query_raw = "select s2l.seo_name, st.sort_order, st.seo_types_linkage, st.seo_types_prefix, st.seo_types_handler from " . TABLE_SEO_TO_LINKS . " s2l left join " . TABLE_SEO_TYPES . " st on (s2l.seo_types_id=st.seo_types_id) where st.seo_types_status='1' and s2l.link_categories_id = '" . (int)$inner[1] . "'";
            if( !$this->set_id($params_query_raw, $seo_params_array) ) {
              $this->assign_default($params_array, $value);
            }
            $flags_array['info_id'] = $inner[1];
            break;
*/
//-MS- Use only if the Links Manager is fully installed EOM
          default:
            $this->assign_default($params_array, $value);
            $flags_array['other'] = true;
            break;
        }
      }
      if( count($seo_params_array) ) {
        $this->resolve_linkage($seo_params_array);
        asort($seo_params_array, SORT_NUMERIC);
        $seo_params_array = array_keys($seo_params_array);
        $params_array = array_merge($seo_params_array, $params_array);
        $result = 1;
      }
      $query = implode('&', $params_array);
      $other = $flags_array['other'];
      return $result;
    }

	function resolve_linkage(&$seo_params_array) {
		$tmp_array = array();
		foreach($seo_params_array as $key => $value) {
			list($sort, $link) = split("_", $value, 2);
			$seo_params_array[$key] = $sort;
			$tmp_array[$key] = $link;
		}
		asort($tmp_array, SORT_NUMERIC);
		foreach($tmp_array as $key => $value) {
			if( $value < 0 ) {continue;}
			
			if( !isset($reduce) ) {
				$reduce = $value;
				continue;
			}
			if($reduce != $value) {
				unset($seo_params_array[$key]);
			}
		}
	}


	function auto_builder($entity, $id) {
		if( SEO_AUTO_BUILDER == 'false' ){return;}
	
		switch($entity) {
			case 'products_id':
				$check_query = tep_db_query("select products_id from " . TABLE_SEO_TO_PRODUCTS . " where products_id = '" . (int)$id . "'");
				if( tep_db_num_rows($check_query) ) { return;}
				$name_query = tep_db_query("select products_name as name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$id . "' and language_id = '1'");
				if( $names_array = tep_db_fetch_array($name_query) ) {
					$types_query = tep_db_query("select seo_types_id from " . TABLE_SEO_TYPES . " where seo_types_class = 'seo_products'");
					if( $types_array = tep_db_fetch_array($types_query) ) {
						$seo_name = $this->create_safe_name($names_array['name']);
						$sql_data_array = array(
											  'seo_types_id' => (int)$types_array['seo_types_id'],
											  'products_id' => (int)$id,
											  'seo_name' => tep_db_prepare_input($seo_name),
											  );
						tep_db_perform(TABLE_SEO_TO_PRODUCTS, $sql_data_array, 'insert');
					}
				}
				break;
			case 'cPath':
				if($this->get_seo_categories_name($id)) return;

                //check seo_to_categories alex : if has record, don't excute insert sql    
				$categories_seo_name_query = tep_db_query("select seo_name from " . TABLE_SEO_TO_CATEGORIES . " where categories_id = '" . (int)$id . "'");
				if( tep_db_num_rows($categories_seo_name_query) == 0) {
                    $name_query = tep_db_query("select categories_name as name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$id . "' and language_id = '1'");
                    if( $names_array = tep_db_fetch_array($name_query) ) {
                        $types_query = tep_db_query("select seo_types_id from " . TABLE_SEO_TYPES . " where seo_types_class = 'seo_categories'");
                        if( $types_array = tep_db_fetch_array($types_query) ) {
                            $seo_name = $this->create_safe_name($names_array['name']);
                            $sql_data_array = array(
                                                  'seo_types_id' => (int)$types_array['seo_types_id'],
                                                  'categories_id' => (int)$id,
                                                  'seo_name' => tep_db_prepare_input($seo_name),
                                                  );
                            tep_db_perform(TABLE_SEO_TO_CATEGORIES, $sql_data_array, 'insert');
                        }
                    }

                }

				break;
			case 'manufacturers_id':
				$check_query = tep_db_query("select manufacturers_id from " . TABLE_SEO_TO_MANUFACTURERS . " where manufacturers_id = '" . (int)$id . "'");
				if( tep_db_num_rows($check_query) ) { return;}
				$name_query = tep_db_query("select manufacturers_name as name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$id . "'");
				if( $names_array = tep_db_fetch_array($name_query) ) {
					$types_query = tep_db_query("select seo_types_id from " . TABLE_SEO_TYPES . " where seo_types_class = 'seo_manufacturers'");
					if( $types_array = tep_db_fetch_array($types_query) ) {
						$seo_name = $this->create_safe_name($names_array['name']);
						$sql_data_array = array(
											  'seo_types_id' => (int)$types_array['seo_types_id'],
											  'manufacturers_id' => (int)$id,
											  'seo_name' => tep_db_prepare_input($seo_name),
											  );
						tep_db_perform(TABLE_SEO_TO_MANUFACTURERS, $sql_data_array, 'insert');
					}
				}
				break;
//-MS- Use only if the articles manager is fully installed
/*
			case 'articles_id':
				$check_query = tep_db_query("select articles_id from " . TABLE_SEO_TO_ARTICLES . " where articles_id = '" . (int)$id . "'");
				if( tep_db_num_rows($check_query) ) {return;}
				$name_query = tep_db_query("select articles_name as name from " . TABLE_ARTICLES_DESCRIPTION . " where articles_id = '" . (int)$id . "' and language_id = '1'");
				if( $names_array = tep_db_fetch_array($name_query) ) {
					$types_query = tep_db_query("select seo_types_id from " . TABLE_SEO_TYPES . " where seo_types_class = 'seo_articles'");
					if( $types_array = tep_db_fetch_array($types_query) ) {
						$seo_name = $this->create_safe_name($names_array['name']);
						$sql_data_array = array(
										  'seo_types_id' => (int)$types_array['seo_types_id'],
										  'articles_id' => (int)$id,
										  'seo_name' => tep_db_prepare_input($seo_name),
										  );
						tep_db_perform(TABLE_SEO_TO_ARTICLES, $sql_data_array, 'insert');
					}
				}
				break;
			case 'authors_id':
				$check_query = tep_db_query("select authors_id from " . TABLE_SEO_TO_AUTHORS . " where authors_id = '" . (int)$id . "'");
				if( tep_db_num_rows($check_query) ) {return;}
				$name_query = tep_db_query("select authors_name as name from " . TABLE_AUTHORS . " where authors_id = '" . (int)$id . "'");
				if( $names_array = tep_db_fetch_array($name_query) ) {
					$types_query = tep_db_query("select seo_types_id from " . TABLE_SEO_TYPES . " where seo_types_class = 'seo_authors'");
					if( $types_array = tep_db_fetch_array($types_query) ) {
						$seo_name = $this->create_safe_name($names_array['name']);
						$sql_data_array = array(
											  'seo_types_id' => (int)$types_array['seo_types_id'],
											  'authors_id' => (int)$id,
											  'seo_name' => tep_db_prepare_input($seo_name),
											  );
						tep_db_perform(TABLE_SEO_TO_AUTHORS, $sql_data_array, 'insert');
					}
				}
				break;
			case 'tPath':
				$check_query = tep_db_query("select topics_id from " . TABLE_SEO_TO_TOPICS . " where topics_id = '" . (int)$id . "'");
				if( tep_db_num_rows($check_query) ) {return;}
				$name_query = tep_db_query("select topcis_name as name from " . TABLE_TOPICS_DESCRIPTION . " where topics_id = '" . (int)$id . "' and language_id = '1'");
				if( $names_array = tep_db_fetch_array($name_query) ) {
					$types_query = tep_db_query("select seo_types_id from " . TABLE_SEO_TYPES . " where seo_types_class = 'seo_topics'");
					if( $types_array = tep_db_fetch_array($types_query) ) {
						$seo_name = $this->create_safe_name($names_array['name']);
						$sql_data_array = array(
											  'seo_types_id' => (int)$types_array['seo_types_id'],
											  'topics_id' => (int)$id,
											  'seo_name' => tep_db_prepare_input($seo_name),
											  );
						tep_db_perform(TABLE_SEO_TO_TOPICS, $sql_data_array, 'insert');
					}
				}
				break;
*/
//-MS- Use only if the articles manager is fully installed EOM

//-MS- Use only if the information pages unlimited is fully installed
/*
			case 'info_id':
				$check_query = tep_db_query("select information_id from " . TABLE_SEO_TO_INFORMATION . " where information_id = '" . (int)$id . "'");
				if( tep_db_num_rows($check_query) ) {return;}
				$name_query = tep_db_query("select information_title as name from " . TABLE_INFORMATION . " where information_id = '" . (int)$id . "' and language_id = '1'");
				if( $names_array = tep_db_fetch_array($name_query) ) {
					$types_query = tep_db_query("select seo_types_id from " . TABLE_SEO_TYPES . " where seo_types_class = 'seo_information'");
					if( $types_array = tep_db_fetch_array($types_query) ) {
						$seo_name = $this->create_safe_name($names_array['name']);
						$sql_data_array = array(
											  'seo_types_id' => (int)$types_array['seo_types_id'],
											  'information_id' => (int)$id,
											  'seo_name' => tep_db_prepare_input($seo_name),
											  );
						tep_db_perform(TABLE_SEO_TO_INFORMATION, $sql_data_array, 'insert');
					}
				}
				break;
*/
//-MS- Use only if the information pages unlimited is fully installed EOM

//-MS- Use only if the Links Manager is fully installed
/*
			case 'lPath':
				$check_query = tep_db_query("select link_categories_id from ".TABLE_SEO_TO_LINKS." where link_categories_id = '".(int)$id."'");
				if( tep_db_num_rows($check_query) ) {return;}
				$name_query = tep_db_query("select link_categories_name as name from ".TABLE_LINK_CATEGORIES_DESCRIPTION." where link_categories_id = '".(int)$id."' and language_id = '1'");
				if( $names_array = tep_db_fetch_array($name_query) ) {
					$types_query = tep_db_query("select seo_types_id from " . TABLE_SEO_TYPES . " where seo_types_class = 'seo_links'");
					if( $types_array = tep_db_fetch_array($types_query) ) {
						$seo_name = $this->create_safe_name($names_array['name']);
						$sql_data_array = array(
											  'seo_types_id' => (int)$types_array['seo_types_id'],
											  'link_categories_id' => (int)$id,
											  'seo_name' => tep_db_prepare_input($seo_name),
											  );
						tep_db_perform(TABLE_SEO_TO_LINKS, $sql_data_array, 'insert');
					}
				}
				break;
*/
//-MS- Use only if the Links Manager is fully installed EOM
			default:
				break;
		}
    }

	function set_id($query_raw, &$seo_params_array) {
		$result = $handler = false;
		$params_query = tep_db_query($query_raw);
		if( $entry = tep_db_fetch_array($params_query) ) {
			if( tep_not_null($entry['seo_types_handler']) ) {
				$handler_array = explode(',', $entry['seo_types_handler']);
				foreach($handler_array as $key => $value ) {
					$value = trim($value);
					if( $this->path == $value ) {
						$handler = $value;
						break;
					}
				}
			}
			if( $handler && !$this->handler_flag) {
				$handler = str_replace('.php', SEO_DEFAULT_INNER_SEPARATOR, $handler);
				$seo_params_array[$handler . $entry['seo_name']] = $entry['sort_order'] . '_' . $entry['seo_types_linkage'];
				$this->handler_flag = true;
			} else {
				$seo_params_array[$entry['seo_types_prefix'] . $entry['seo_name']] = $entry['sort_order'] . '_' . $entry['seo_types_linkage'];
			}
			$result = true;
		}
		return $result;
	}
	
	function set_path($info, &$tmp_array, &$depth, &$sort_order) {
		$result = $handler = false;
		if( $entry = $info ) {
			if( !$depth ) {
				if( tep_not_null($entry['seo_types_handler']) ) {
					$handler_array = explode(',', $entry['seo_types_handler']);
					foreach($handler_array as $key => $value) {
						if( $this->path == $value ) {
							$handler = $value;
							break;
						}
					}
				}
				if( $handler && !$this->handler_flag ) {
					$handler = str_replace('.php', SEO_DEFAULT_INNER_SEPARATOR, $handler);
					$tmp_array[] = $handler . $entry['seo_name'];
					$this->handler_flag = true;
				} else {
					$tmp_array[] = $entry['seo_types_prefix'] . $entry['seo_name'];
				}
				$sort_order = $entry['sort_order'] . '_' . $entry['seo_types_linkage'];
			} else {
				$tmp_array[] = $entry['seo_name'];
			}
			$depth++;
			$result = true;
		}
		return $result;
	}

	function assign_default(&$params_array, $value) {
		$value = $this->create_safe_string($value);
		$params_array[$value] = $value;
	}

	function exclude_script() {
		// Make sure this is a php script otherwise exclude it.
		if( strlen($this->path) < 5 || substr($this->path, -4, 4) != '.php') {
			return true;
		}
		$result = false;
		$key = md5($this->path);
		
		$check_query = tep_db_query("select seo_exclude_key from " . TABLE_SEO_EXCLUDE . " where seo_exclude_key = '" . tep_db_input($key) . "'");
		if( tep_db_num_rows($check_query) ) {
			return true;
		}
		
		$this->params_array = explode('&', $this->query );
		return $result;
	}

    // Validate REQUEST_URI in case we got a redirect from a server script. May needed with some servers
	function validate_uri(&$seo_url) {
		global $g_relpath; 
		$request_uri = explode('?', $_SERVER['REQUEST_URI']);
		
		$self = basename($_SERVER['PHP_SELF']);
		$self_count = strlen($self);
		if( is_array($request_uri) && isset($request_uri[1]) && strlen($request_uri[0]) > $self_count && $self == substr($request_uri[0], -$self_count, $self_count) ) {
			$this->params_array = explode('&', $request_uri[1]);
			if( is_array($this->params_array) ) {
				$seo_url = $_SERVER['REQUEST_URI'] = $this->params_array[0];
				unset($this->params_array[0]);
				$query_string = implode('&',$this->params_array);
				if( $query_string != '' ) {
					$seo_url .= '?' . $query_string;
					$_SERVER['REQUEST_URI'] = $seo_url;
				}
				// Rectify seo url
				$seo_url = $g_relpath . $_SERVER['REQUEST_URI'];
			}
		}
	}

    // Scan redirection table for matches against incoming urls.
	function check_redirection($key, $seo_right, $check_only=false) {
		if( SEO_REDIRECT_TABLE == 'false' ) {
			return false;
		}
	
		$check_query = tep_db_query("select seo_url_org, seo_redirect from " . TABLE_SEO_REDIRECT . " where seo_url_key = '" . tep_db_input($key) . "'");
		if( $seo_array = tep_db_fetch_array($check_query) ) {
			if( $check_only ) { return true;}
			
			$separator = '';
			$url = $seo_array['seo_url_org'];
			$url_parse = parse_url($url);
			if( !isset($url_parse['query']) ) {
				if( $seo_right != '' ) {
					$separator = '?';
				}
				$url_query = '';
			} else {
				if( $seo_right != '' ) {
					$separator = '&';
				}
				$url_query = '?' . $url_parse['query'];
			}

			// Abort on duplicates
			$double_query = tep_db_query("select seo_url_key from " . TABLE_SEO_URL . " where seo_url_key = '" . tep_db_input($key) . "'");
			if(tep_db_num_rows($double_query)){return false;}
			
			tep_db_query("update " . TABLE_SEO_REDIRECT . " set seo_url_hits = seo_url_hits+1, last_modified=now() where seo_url_key = '" . tep_db_input($key) . "'");
			$url_redirect = $url . $url_query . $separator . $seo_right;
			// Issue Redirect
			header("HTTP/1.1 " . $seo_array['seo_redirect']);
			header('Location: ' . $url_redirect);
			exit();
		}
		return false;
	}

	function eliminate_session($remove_name=false) {
		if( !$remove_name ) {
			$remove_name = tep_session_name();
		}
		if( is_array($this->params_array) ) {
			for($i=0, $j=count($this->params_array); $i<$j; $i++ ) {
				if(stristr($this->params_array[$i], $remove_name) ) {
					unset($this->params_array[$i]);
				}
			}
		}
	}

	/**
	* 
	* @authoer nathan 
	* @access public 
	* @param 
	* @return 
	*/
	function language_url_dispose($url, $lang_code){
		//动态页面不进行处理
		if(strpos($url,'.php') !== false) return $url;
		if($url && $lang_code && $lang_code != 'en') {
			$url = str_replace(DIR_WS_HTTP_CATALOG,'/',$url);
			$_index = strrpos($url, '/');
			$url = substr($url, 0, $_index) . DIR_WS_HTTP_CATALOG . $_SESSION['languages_code']. substr($url, $_index);
		}
		return $url;
	}
}
?>
