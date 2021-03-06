<?php
/**
* 分类的树型类
* @author espow team nathan 2011-8-24
* @package
* @licese http://www.oscommerce.com
* @version 1.1
* @copyright (c) 2003 osCommerce
*/
Class categories extends tree{

	public function categories($all_access = false){
		global $customer_group_id;

		if(empty($customer_group_id)){
			$customer_group_id = 'G';
		}
		if($all_access){
			$query = tep_db_query("select c.categories_id, c.parent_id from " . TABLE_CATEGORIES .' c order by c.parent_id,c.category_toptab');
		}else{
			$query = tep_db_query("select c.categories_id, c.parent_id from " . TABLE_CATEGORIES .' c where products_group_access like "%'.$customer_group_id.'%" order by category_toptab,categories_id,parent_id ');
		}
		while($row = tep_db_fetch_array($query)){
			//设置树型结构数据
			$this->set_node($row['categories_id'], $row['parent_id'], $row['categories_id']);
		}
	}
	/**
	* 返回所有N级子类分类ID
	* @authoer nathan 
	* @access public 
	* @param $id int 分类ID
	* @return Array
	*/
	public function get_subcategories($id){
		$arr = $this->get_childs($id);
		$arr[] = $id;
		return $arr;
	}
	/**
	* 返回上级分类ID
	* @authoer nathan 
	* @access public 
	* @param $id int 分类ID
	* @return int
	*/
	public function get_parent_id($id){
		return $this->get_parent($id);
	}
	/**
	* 
	* @authoer nathan 
	* @access public 
	* @param 
	* @return 
	*/
	public function get_topcategories_id($id){
		$_top_ids = $this->get_parents($id);
		if(empty($_top_ids)) return 0;
		reset($_top_ids);
		return current($_top_ids);
	}
	/**
	* 获取目录名称
	* @authoer nathan 
	* @access public 
	* @param $id int 目录ID
	* @return String
	*/
	function get_name($id){
		global $customer_group_id, $languages_id;
		if(empty($customer_group_id)){
			$customer_group_id = 'G';
		}
		$query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION .' left join '.TABLE_CATEGORIES.' using (categories_id) where products_group_access like "%'.$customer_group_id.'%" and categories_id ="'. (int)$id.'" and language_id="'. $languages_id.'"');
		$info = tep_db_fetch_array($query);
		return $info ? $info['categories_name'] : null;
	}
	/**
	* 
	* @authoer nathan 
	* @access public 
	* @param 
	* @return 
	*/
	function get_parent_all($id){
		$result = array();
		while($_cid = $this->get_parent($id)){
			$result[] = $_cid;
			$id = $_cid;
		}
		return $result;
	}
}
?>