<?php
	include('init.php');
/**
* @author espow team
* @package
* @licese http://www.oscommerce.com
* @version 1.1
* @copyright (c) 2003 osCommerce
* eg:
*    $attriModel = new AttributeModel;
*    if($products_model = $attriModel->get_model($val)) 
*/
class AttributeModel{
	var $product_attr_list = array();
	var $error = array();
	var $product_list = array();

	function AttributeModel(){
		$products_sql = 'SELECT products_id,products_model FROM products_attributes WHERE price_prefix <>""  GROUP BY products_model';
		$products_query = tep_db_query($products_sql);
		while($product = tep_db_fetch_array($products_query)){
			$key = substr($product['products_model'], 0, 4);
			$this->product_attr_list[$key][] = $product['products_model'];
		}
	}

	/**
	* 返回原始产品编码，否则返回false
	* @authoer nathan 
	* @access public 
	* @param string $model 编码
	* @return mix string|bool
	*/
	function get_model($model){
		if($index = strpos($model,'/')) return substr($model, 0, $index);

		$prefix_model = substr($model, 0, 4);
		if(isset($this->product_attr_list[$prefix_model]) && $this->product_attr_list[$prefix_model]){
			echo $model.'<br/>';
			echo '<pre>';
			print_r($this->product_attr_list[$prefix_model]);
			echo '</pre>';
			die();
			foreach ($this->product_attr_list[$prefix_model] as $key => $val){
				if($model == $val) return false;
				if(strpos($model, $val)!==false){
					$is_product = false;
					if(in_array($model, $this->product_list)){
						$is_product = true;
					}else{
						$check_query = tep_db_query('select products_model from products where products_model = "'.$model.'"');
						if(tep_db_num_rows($check_query) >0){
							$this->product_list[] = $model;
							$is_product = true;
						}
					}
					if($is_product) return false;
					return $val;
				}
			}

			//可能是原编码
			/*
			$check_query = tep_db_query('select products_model from products where products_model = "'.$model.'"');
			if(tep_db_num_rows($check_query) >0){
				echo $model.'<br/>';
				return false;
			}else{
				return null;
			}
			*/
			return false;
		}
	}
}
	
	include('excel/reader.php');
	$attriModel = new AttributeModel;
	$a = $attriModel->get_model('EFASHION19G');
	var_dump($a);die();
	$file_name = iconv('utf-8', 'gbk','D:\yu\桌面\记录\资料\products\清仓产品201212270.xls');
	//$file_name = iconv('utf-8', 'gbk','D:\yu\桌面\记录\资料\products\清仓产品20121227.xls');
	//$file_name = iconv('utf-8', 'gbk','D:\yu\桌面\记录\资料\products\清仓产品20121228.xls');
	//$file_name = iconv('utf-8', 'gbk','D:\yu\桌面\记录\资料\products\清仓产品去属性20121227.xls');
	$excel_data = new Spreadsheet_Excel_Reader();
	$excel_data->setOutputEncoding('UTF-8');
	$excel_data->read($file_name);
	$a = array();
	//var_dump($excel_data->boundsheets,$excel_data->sheets[0]['numRows'], $excel_data->sheets[0]['cells'][2]);die();
	for ( $s=0; $s<count($excel_data->boundsheets); $s++){
		for ($i=2; $i <= $excel_data->sheets[$s]['numRows']; $i++){
			$model = $excel_data->sheets[$s]['cells'][$i][1];
			if($tmp = $attriModel->get_model($model)){
				$a[] = $tmp;
				//echo $tmp.'<br>';
			}
		}
	}
	unset($excel_data);
	echo count($a).'<br/>378';
?>
