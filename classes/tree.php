<?php
/**
* 树型操作类
* @author espow team Nathan 2011-8-24
* @package
* @licese http://www.oscommerce.com
* @version 1.1
* @copyright (c) 2003 osCommerce
*/
class tree {
	var $data = array();
	var $child = array(-1 => array());
	var $layer = array(-1 => -1);//ID所在的层级
	var $parent = array();
	var $countid = 0;
	function tree() {}

	function set_node($id, $parent, $value) {

		$parent = $parent?$parent:0;

		$this->data[$id] = $value;
		$this->child[$parent][]  = $id;
		$this->parent[$id] = $parent;

		if(!isset($this->layer[$parent])) {
			$this->layer[$id] = 0;
		} else {
			$this->layer[$id] = $this->layer[$parent] + 1;
		}
	}

	function get_list(&$tree, $root= 0) {
		if(!$this->child[$root]) return;
		foreach($this->child[$root] as $key => $id) {
			$tree[] = $id;
			if($this->child[$id]) $this->get_list($tree, $id);
		}
	}

	function get_value($id) {
		return $this->data[$id];
	}

	function re_set_layer($id) {
		if($this->parent[$id]) {
			$this->layer[$this->countid] = $this->layer[$this->countid] + 1;
			$this->re_set_layer($this->parent[$id]);
		}
	}

	function get_layer($id, $space = false) {
		$this->layer[$id] = 0;
		$this->countid = $id;
		$this->re_set_layer($id);
		return $space?str_repeat($space, $this->layer[$id]):$this->layer[$id];
	}

	function get_parent($id) {
		return $this->parent[$id];
	}

	function get_parents($id) {
		while($this->parent[$id] != -1 && $this->parent[$id] != 0) {
			$id = $parent[$this->layer[$id]] = $this->parent[$id];
		}
		if($parent){
			ksort($parent); //按照键名排序
			reset($parent); //数组指针移回第一个单元
		}

		return $parent;
	}

	function get_child($id) {
		return $this->child[$id];
	}

	function get_childs($id = 0) {
		$child = array();
		$this->get_list($child, $id);

		return $child;
	}
}

?>