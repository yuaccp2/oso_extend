<?php
/**
* 模版自成类
* @author nathan
* @package
* @licese 
* @version 1.1
* @copyright (c) 2011
*/
Class Create_code_class{
	var $fields_list = array();
	var $fields = array();
	var $prime_key = '';

	function __construct($db_config){
		$con = mysql_connect($db_config['host'], $db_config['root'], $db_config['pwd']);
		mysql_select_db($db_config['database'], $con);
	}
	/**
	* 
	* @authoer nathan 
	* @access public 
	* @param 
	* @return 
	*/
	function get_table_list(){
		$tables = array();
		$res = mysql_query('show tables');
		while($row = mysql_fetch_array($res,MYSQL_ASSOC)){
			$tables[] = current($row);
		}
		return $tables;
	}
	/**
	* 
	* @authoer nathan 
	* @access public 
	* @param 
	* @return 
	*/
	function get_field($table_name){
		if($this->fields_list) return $this->fields_list;
		$default_field = array('date_created', 'date_modified');
		$fields = array();
		$fields_list = array();
		$res = mysql_query('show full fields from '. $table_name);
		while($row = mysql_fetch_array($res,MYSQL_ASSOC)){
			$fields_list[] = $row;
			if($row['Key'] == 'PRI') $this->prime_key = $row['Field'];
			if($row['Extra'] == 'auto_increment' || in_array($row['Field'],$default_field)) continue;
			$fields[] = $row['Field'];
		}
		$this->fields_list = $fields_list;
		$this->fields = $fields;
		unset($fields,$fields_list);
		return $this->fields_list;
	}
	/**
	* 
	* @authoer nathan 
	* @access public 
	* @param 
	* @return 
	*/
	function create_file($template_file, $filename){
		if(!$this->fields_list) return false;
		
		$classname = basename($filename,'.php');
		$filename_href = 'FILENAME_'.strtoupper($classname);
		$model_name = $classname . '_model';
		$uc_classname = format_word($classname, true);
		$prime_key = $this->prime_key;
		$fields_list = $this->fields_list;
		$fields = $this->fields;
		$fields_str = '';
		$list_show_num = 5;
		foreach ($fields as $key => $val){
			if($val == $prime_key) continue;
			$fields_str .= '$'.$val.',';
		}
		$fields_str = trim($fields_str, ',');
		ob_start();
		require($template_file);
		$content = ob_get_contents();
		ob_end_clean();
		file_put_contents($filename,$content);
	}
	/**
	* 
	* @authoer nathan 
	* @access public 
	* @param 
	* @return 
	*/
	function parse_type($fields){
		$ret = array('type'=>'','value'=>'');
		$type = $fields['Type'];
		if(strpos($type, 'int') !== false){
			$verif_rule .= 'patterns[integer]|';
		}elseif(strpos($type, 'char') !== false){
			preg_match('/.*char\((\d+)\)/', $type, $arr);
		}elseif($fields['Type'] == 'numeric'){
		}elseif(strpos($type, 'time') !== false){
		}elseif(strpos($type, 'enum') !== false){
			preg_match('/^enum\(.*\)/', $type, $arr);
		}
		return $ret;
	}
}
?>