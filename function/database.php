<?php
/*
  $Id: database.php,v 1.1.1.1 2004/03/04 23:40:48 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;

    if (USE_PCONNECT == 'true') {
      $$link = @mysql_pconnect($server, $username, $password);
    } else {
      $$link = @mysql_connect($server, $username, $password);
    }

    if ($$link){ @mysql_select_db($database); }
    
    //else{ tep_db_connect_bak(); }

    return $$link;
  }
  //alex added : use bak db
  function tep_db_connect_bak($server = DB_SERVER2, $username = DB_SERVER_USERNAME2, $password = DB_SERVER_PASSWORD2, $database = DB_DATABASE2, $link = 'db_link') {
    global $$link;

    if (USE_PCONNECT == 'true') {
      $$link = @mysql_pconnect($server, $username, $password);
    } else {
      $$link = @mysql_connect($server, $username, $password) or die('connect');
    }

    if ($$link) @mysql_select_db($database) or die('select');

    define('DB_BAK_USED',1);

    return $$link;
  }

  function tep_db_close($link = 'db_link') {
    global $$link;

    return mysql_close($$link);
  }

  function tep_db_error($query, $errno, $error) {
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">'.TEP_DB_ERRORR.'</font></small><br><br></b></font>');
  }

  function tep_db_query($query, $link = 'db_link') {
    global $$link;
	runtime();
    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    $result = @mysql_query($query, $$link) or tep_db_error($query, mysql_errno(), mysql_error());

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
       $result_error = mysql_error();
       error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }
	sql_log($query);
	return $result;
  }

  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return tep_db_query($query, $link);
  }

  function tep_db_fetch_array($db_query) {
    return mysql_fetch_array($db_query, MYSQL_ASSOC);
  }

  function tep_db_num_rows($db_query) {
    return mysql_num_rows($db_query);
  }

  function tep_db_data_seek($db_query, $row_number) {
    return mysql_data_seek($db_query, $row_number);
  }

  function tep_db_insert_id() {
    return mysql_insert_id();
  }

  function tep_db_free_result($db_query) {
    return mysql_free_result($db_query);
  }

  function tep_db_fetch_fields($db_query) {
    return mysql_fetch_field($db_query);
  }

  function tep_db_output($string) {
    return htmlspecialchars($string);
  }

  function tep_db_input($string) {
    return addslashes($string);
  }

  function tep_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(tep_sanitize_string(mysql_real_escape_string(stripslashes($string))));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_db_prepare_input(mysql_real_escape_string($value));
      }
      return $string;
    } else {
      return $string;
    }
  }

  //Eversun mod for sppc and qty price breaks
 function tep_db_table_exists($table, $link = 'db_link') {
    //alex 2010-9-17 modified 
    $db = DB_DATABASE;
    if(DB_BAK_USED == 1){
        $db = DB_DATABASE2;
    }
    $result = tep_db_query("show table status from `" . $db . "`");
    //alex 2010-9-17 modified 
    while ($list_tables = tep_db_fetch_array($result)) {
    if ($list_tables['Name'] == $table) {
      return true;
    }
    }
    return false;
  }

  function tep_db_check_age_specials_retail_table() {
    //alex 2010-9-17 modified 
    $db = DB_DATABASE;
    if(DB_BAK_USED == 1){
        $db = DB_DATABASE2;
    }
    $result = tep_db_query("show table status from `" . $db . "`");
    //alex 2010-9-17 modified 
    $last_update_table_specials = "2000-01-01 12:00:00";
    $table_srp_exists = false;
    while ($list_tables = tep_db_fetch_array($result)) {
    if ($list_tables['Name'] == TABLE_SPECIALS_RETAIL_PRICES) {
    $table_srp_exists = true;
    $last_update_table_srp = $list_tables['Update_time'];
    }
    if ($list_tables['Name'] == TABLE_SPECIALS) {
    $last_update_table_specials = $list_tables['Update_time'];
    }
    } // end while

    if(!$table_srp_exists || ($last_update_table_specials > $last_update_table_srp)) {
       if ($table_srp_exists) {
         $query1 = "truncate " . TABLE_SPECIALS_RETAIL_PRICES . "";
         if (tep_db_query($query1)) {
     $query2 = "insert into " . TABLE_SPECIALS_RETAIL_PRICES . " select s.products_id, s.specials_new_products_price, s.status, s.customers_group_id from " . TABLE_SPECIALS . " s where s.customers_group_id = '0'";
     $result =  tep_db_query($query2);
     }
       } else { // table specials_retail_prices does not exist
         $query1 = "create table " . TABLE_SPECIALS_RETAIL_PRICES . " (products_id int NOT NULL default '0', specials_new_products_price decimal(15,4) NOT NULL default '0.0000', status tinyint, customers_group_id smallint, primary key (products_id) )" ;
         $query2 = "insert into " . TABLE_SPECIALS_RETAIL_PRICES . " select s.products_id, s.specials_new_products_price, s.status, s.customers_group_id from " . TABLE_SPECIALS . " s where s.customers_group_id = '0'";
         if( tep_db_query($query1) && tep_db_query($query2) ) {
      ; // execution succesfull
        }
       } // end else
    } // end if(!$table_srp_exists || ($last_update_table_specials....
  }

  function tep_db_check_age_products_group_prices_cg_table($customer_group_id) {
    //alex 2010-9-17 modified 
    $db = DB_DATABASE;
    if(DB_BAK_USED == 1){
        $db = DB_DATABASE2;
    }
    $result = tep_db_query("show table status from `" . $db . "`");
    //alex 2010-9-17 modified 
    $last_update_table_pgp = strtotime('2000-01-01 12:00:00');
    $table_pgp_exists = false;
    while ($list_tables = tep_db_fetch_array($result)) {
    if ($list_tables['Name'] == TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id) {
    $table_pgp_exists = true;
    $last_update_table_pgp = strtotime($list_tables['Update_time']);
    } elseif ($list_tables['Name'] == TABLE_SPECIALS ) {
    $last_update_table_specials = strtotime($list_tables['Update_time']);
    } elseif ($list_tables['Name'] == TABLE_PRODUCTS ) {
    $last_update_table_products = strtotime($list_tables['Update_time']);
    } elseif ($list_tables['Name'] == TABLE_PRODUCTS_GROUPS ) {
    $last_update_table_products_groups = strtotime($list_tables['Update_time']);
    }
    } // end while

   if ($table_pgp_exists == false) {
      $create_table_sql = "create table " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id . " (products_id int NOT NULL default '0', products_price decimal(15,4) NOT NULL default '0.0000', specials_new_products_price decimal(15,4) default NULL, status tinyint, primary key (products_id) )" ;
      $fill_table_sql1 = "insert into " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." select p.products_id, p.products_price, NULL as specials_new_products_price, NULL as status FROM " . TABLE_PRODUCTS . " p";
      $update_table_sql1 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_PRODUCTS_GROUPS . " pg using(products_id) set ppt.products_price = pg.customers_group_price where ppt.products_id = pg.products_id and pg.customers_group_id ='" . $customer_group_id . "'";
      $update_table_sql2 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_SPECIALS . " s using(products_id) set ppt.specials_new_products_price = s.specials_new_products_price, ppt.status = s.status where ppt.products_id = s.products_id and s.customers_group_id = '" . $customer_group_id . "'";
      if ( tep_db_query($create_table_sql) && tep_db_query($fill_table_sql1) && tep_db_query($update_table_sql1) && tep_db_query($update_table_sql2) ) {
         return true;
              }
   } // end if ($table_pgp_exists == false)

   if ( ($last_update_table_pgp < $last_update_table_products && (time() - $last_update_table_products > (int)MAXIMUM_DELAY_UPDATE_PG_PRICES_TABLE * 60) ) || $last_update_table_specials > $last_update_table_pgp || $last_update_table_products_groups > $last_update_table_pgp ) { // then the table should be updated
      $empty_query = "truncate " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id . "";
      $fill_table_sql1 = "insert into " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." select p.products_id, p.products_price, NULL as specials_new_products_price, NULL as status FROM " . TABLE_PRODUCTS . " p";
      $update_table_sql1 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_PRODUCTS_GROUPS . " pg using(products_id) set ppt.products_price = pg.customers_group_price where ppt.products_id = pg.products_id and pg.customers_group_id ='" . $customer_group_id . "'";
      $update_table_sql2 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_SPECIALS . " s using(products_id) set ppt.specials_new_products_price = s.specials_new_products_price, ppt.status = s.status where ppt.products_id = s.products_id and s.customers_group_id = '" . $customer_group_id . "'";
      if ( tep_db_query($empty_query) && tep_db_query($fill_table_sql1) && tep_db_query($update_table_sql1) && tep_db_query($update_table_sql2) ) {
         return true;
              }
   } else { // no need to update
     return true;
   } // end checking for update

  }
  //Eversun mod end for sppc and qty price breaks
  
  function tep_db_decoder($string) {
    $string = str_replace('&#39;', "'", $string);
    $string = str_replace('&#39', "'", $string); //backword compatabiliy
    return $string;
  }
	/**
	* 
	* @authoer nathan 
	* @access public 
	* @param 
	* @return 
	*/
	function sql_log($sql){
		$runtime = runtime(1,1);
		//select update insert 
		if(!isset($GLOBALS['sql_count'])){
			$GLOBALS['sql_count'] = array('select'=>0,'update'=>0,'insert'=>0,'delete'=>0);
		}
		if(!isset($GLOBALS['sql_str'])){
			$GLOBALS['sql_str'] = array('select'=>array(),'update'=>array(),'insert'=>array(),'delete'=>array(),'other'=>array());
		}

		$execute_type = strtolower(substr(trim($sql),0,6));
		$log_time = 200;//MS
		//if($execute_type != 'select') write_log_file('login_sql.txt', $sql, false);
		$exception = $execute_type != 'select';
		$exception_table = array('whos_online');
		foreach ($exception_table as $key => $val){
			if(strpos($sql,$val)){
				$exception = false;
				break;
			}
		}
		if($execute_type == 'select'){
			preg_match("/.*\sfrom\s(.*).*?/",strtolower($sql),$sql_arr);
			$temp_arr = explode(' ',$sql_arr[1]);
			$table_name = str_replace('(','',current($temp_arr));
			$GLOBALS['select_table'][$table_name]++;
			$GLOBALS['all_select'][$table_name][] = array('sql'=>$sql, 'time'=>$runtime);
			if(strpos($sql,' products ')){
				$GLOBALS['log']['select_products'][] = $sql . "[{$runtime}ms]";
			}
		}
		$expired_time = $runtime > $log_time;
		if($expired_time || $exception){
			if(array_key_exists($execute_type,$GLOBALS['sql_str'])){
				$GLOBALS['sql_str'][$execute_type][] = array('sql'=>$sql,'time'=>$runtime);
			}else{
				$GLOBALS['sql_str']['other'][] = array('sql'=>$sql,'time'=>$runtime);
			}
		}
		if(!isset($GLOBALS['sql_time'])) $GLOBALS['sql_time'] = 0;
		$GLOBALS['sql_time']+= $runtime;
		$GLOBALS['sql_count'][$execute_type]++;
		$GLOBALS['sql_count']++;
	}
	function tep_sql_log() {
		$message = '';
		if(!isset($GLOBALS['sql_str']) && empty($GLOBALS['sql_str'])) return false;
		if($GLOBALS['sql_str']['delete']){
			$message = '----------This is delete '.count($GLOBALS['sql_str']['delete']).'nums----------'."\n";
			foreach ($GLOBALS['sql_str']['delete'] as $key => $val){
				$message.=$val['sql']."          [{$val['time']} ms] \n";
			}
		}
		if($GLOBALS['sql_str']['update']){
			$message.= '----------This is update '.count($GLOBALS['sql_str']['update']).'nums----------'."\n";
			foreach ($GLOBALS['sql_str']['update'] as $key => $val){
				$message.=$val['sql']."          [{$val['time']} ms] \n";
			}
		}
		if($GLOBALS['sql_str']['insert']){
			$message.= '----------This is insert '.count($GLOBALS['sql_str']['insert']).'nums----------'."\n";
			foreach ($GLOBALS['sql_str']['insert'] as $key => $val){
				$message.=$val['sql']."          [{$val['time']} ms] \n";
			}
		}
		if($GLOBALS['sql_str']['select']){
			$message.= '----------This is select '.count($GLOBALS['sql_str']['select']).'nums----------'."\n";
			foreach ($GLOBALS['sql_str']['select'] as $key => $val){
				$message.=$val['sql']."          [{$val['time']} ms] \n";
			}
		}
		if($GLOBALS['sql_str']['other']){
			$message.= '----------This is other '.count($GLOBALS['sql_str']['other']).'nums ----------'."\n";
			foreach ($GLOBALS['sql_str']['other'] as $key => $val){
				$message.=$val['sql']."          [{$val['time']} ms] \n";
			}
		}
		if($message){
			$log_file = 'sql.txt';
			write_log_file($log_file,$message);
		}
		if($GLOBALS['select_table']){
			$select_sum = 0;
			arsort($GLOBALS['select_table']);
			$query_string = $_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '';
			$message = "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'].$query_string ."\n";
			foreach ($GLOBALS['select_table'] as $key => $val){
				$message.= "table [{$key}] execute_number: {$val}\n";
				$select_sum+=$val;
			}
			$message = " total select execute_number: {$select_sum} \n".$message;
			write_log_file("select.txt",$message);
		}
		if($GLOBALS['all_select']){
			$message = '';
			/*
			$len = count($GLOBALS['all_select']);
			for ($i=0; $i<$len; $i++){
				for ($j=0; $j<=$len-$i; $j++){
					if($GLOBALS['all_select'][$j]['time'] < $GLOBALS['all_select'][$j+1]['time']){
						$tmp = $GLOBALS['all_select'][$j+1];
						$GLOBALS['all_select'][$j+1] = $GLOBALS['all_select'][$j];
						$GLOBALS['all_select'][$j] = $tmp;
					}
				}
			}*/
			$_temp = array();
			$_time = 10;
			foreach ($GLOBALS['all_select'] as $key => $info){
				foreach ($info as $_key => $val){
					if($val['time'] > $_time) $_temp[] = $val;
					$message.= $val['sql'] . "\t[" . $val['time'] . " ms]\n";
				}
			}
			if($_temp){
				$message.="-------------------------毫秒数大于{$_time}ms num:".count($_temp)."-------------------------------\n";
				foreach ($_temp as $key => $val){
					$message.= $val['sql'] . "\t[" . $val['time'] . " ms]\n";					
				}
			}
			write_log_file('all_select_log.txt', $message, false, false);
		}
		if(isset($GLOBALS['log']) && $GLOBALS['log']){
			foreach ($GLOBALS['log'] as $key => $val){
				write_log_file($key.'.txt', join("\n",$val), false, false);
			}
		}
	}
	function set_function_log($name, $message){
		//if(!isset($GLOBALS[$name]))
		$GLOBALS['function'][$name][] = $message;
	}
	function tep_function_log(){
		if(!(isset($GLOBALS['function']) && $GLOBALS['function'])) return;
		$log_file = 'function/fun_'.date('Y-m-d').'.txt';
		$message = '';
		foreach ($GLOBALS['function'] as $fname => $list){
			$message .= $fname . ":\n";
			foreach ($list as $key => $val){
				$message .= $val . "\n";
			}
			$message .= "\n";
		}
		write_log_file($log_file,$message,false);
	}
	function runtime($mode=0, $val = false)   {
		static $t;   
		if(!$mode) {   
			$t = microtime();
			return;
		}   
		$t1 = microtime();   
		list($m0,$s0) = explode(" ",$t);   
		list($m1,$s1) = explode(" ",$t1); 
		if($val) return sprintf("%.3f",($s1+$m1-$s0-$m0)*1000);
		return sprintf("%.3f ms",($s1+$m1-$s0-$m0)*1000);
	}
	function write_log_file($file, $message, $date_assort = true, $append = true){
		if(!($file && $message)) return false;
		if($date_assort){
			$_path = 'logs/'.date('Y-m');
			if(!is_dir($_path)) mkdir($_path);
			$file_name = basename($file,'.txt');
			$file = $_path.'/'.$file_name.'_'.date('Y-m-d').'.txt';
		}else{
			$file = 'logs/' . $file;
		}
		$mod = 'a';
		if(!$append) $mod = 'w';
		$fp = @fopen($file, $mod);
		@fwrite($fp, '-------------------------Log Time: ' . date('Y-m-d H:i:s') . "----------------------------\n");
		@fwrite($fp,  $message. "\n");
		@fclose($fp);
	} 
?>
