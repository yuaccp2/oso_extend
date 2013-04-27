<?=tag('php')."\r\n"?>
/**
* <?=basename($filename)?>
* @author espow team nathan
* @date <?=date('Y-m-d')?>
* @package
* @licese http://www.oscommerce.com
* @version 1.1
* @copyright (c) 2003 osCommerce
*/

class <?=ucfirst($classname)?>{
	var $table_name = TABLE_<?=strtoupper(str_replace('_model','',$classname))?>;
	var $_key = '<?=$prime_key?>';
	var $_id = null;

	//构造函数
	function __construct(){
		
	}
	/**
	* 获取信息
	* @authoer nathan 
	* @access public 
	* @param $id int
	* @param $field string
	* @return mix array|String|null
	*/
	function get_info($id, $field = null){		
		$sql = 'select * from ' . $this->table_name . ' where ' . $this->_key . ' = "' . $id . '"';
		$info = tep_db_fetch_array(tep_db_query($sql));
		if($field && isset($info[$field])) return $info[$field];
		return $info;
	}
	/**
	* 检查ID是否存在
	* @authoer nathan 
	* @access public 
	* @param $id int
	* @return bool
	*/
	function check_exists_id($id){
		$sql = 'select ' . $this->_key .' from ' . $this->table_name . ' where ' . $this->_key . ' ="' . $id . '"';
		$ret = tep_db_num_rows(tep_db_query($sql));
		if($ret) $this->_id = $id;
		return $ret ? true : false;
	}
	/**
	* 添加信息
	* @authoer nathan 
	* @access public 
	<?php
		foreach ($fields as $key => $val){
	?>
	* @param $<?=$val?>			string	
	<?php
		}
	?>
	* @return 
	*/
	function do_insert(<?=$fields_str?>){
		$data = array(
				<?php
					$key_index = count($fields) - 1;
					foreach ($fields as $key => $val){
				?>
					'<?=$val?>' => $<?=$val?><?=$key == $key_index ? '' : ','?><?="\r\n"?>
				<?php
					}
				?>
				);
		tep_db_perform($this->table_name, $data);
		return tep_db_insert_id();
	}
	/**
	* 更新信息
	* @authoer nathan 
	* @access public 
	<?php
		foreach ($fields as $key => $val){
	?>
	* @param $<?=$val?>			string	
	<?php
		}
	?>
	* @inner_param			int		$this->_id 	调用check_exists_id()
	* @return bool
	*/
	function do_update(<?=$fields_str?>){
		if(!$this->_id) return false;
		$data = array(
				<?php
					$key_index = count($fields) - 1;
					foreach ($fields as $key => $val){
				?>
				'<?=$val?>' => $<?=$val?><?=$key == $key_index ? '' : ','?><?="\r\n"?>
				<?php
					}
				?>
				);

		$where = $this->_key . " = '" . $this->_id . "'";
		return tep_db_perform($this->table_name, $data, 'update', $where);
	}
	/**
	* 删除记录
	* @authoer nathan 
	* @access public 
	* @param $id int
	* @return bool
	*/
	function do_delete($id){
		if(!$this->check_exists_id($id)) return false;
		$sql = 'delete from ' . $this->table_name . ' where ' . $this->_key . ' = "' . $this->_id .'"';
		return tep_db_query($sql);
	}
	/**
	* 获取总行数
	* @authoer nathan 
	* @access public 
	* @param 
	* @return int
	*/
	function get_row_total(){
		$sql = 'select count(' . $this->_key .') acount from ' . $this->table_name;
		$row = tep_db_fetch_array(tep_db_query($sql));
		return $row['acount'];
	}
	<?php
		if(strpos($fields_str, '_status')){

	?>

	/**
	* 设置状态
	* @authoer nathan 
	* @access public 
	* @param 
	* @return int
	*/
	function set_status($val, $id){
		$status = 0;
		if($val == 1) $status = 1;
		$sql = 'update '. $this->table_name .' set _status= "'. $status .'" where ' . $this->_key .' = "'. $id .'"';
		return tep_db_query($sql);
	}	
	<?php
	}
	?>

}
?>