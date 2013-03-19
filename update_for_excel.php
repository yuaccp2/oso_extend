<?php
include("init.php");
require_once 'Excel/reader.php';
header('Content-type:text/html;charset=utf-8');
//header('Content-type:text/html;charset=ISO-8859-1');

/**
* 根据excel更新数据
* @authoer nathan 
* @access public 
* @param int $cell_num 字段的数量
* @param int $field_row_num 字段所在的行
* @param int $primary_key 主键的列数
* @return 
*/
function update_excel($file, $table, $cell_num ,$field_row_num = 1, $primary_key = 1){
	
	$sql = '';
	$check = true;
	$rownvail = array();
	$file_path = iconv('UTF-8', 'GBK',$file);
	if(!file_exists($file_path)) die('This File is Not Exists.');
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('UTF-8');
	$data->read($file_path);
	$field_names = array();
	$table_name = $table;
	for ( $s=0; $s<count($data->boundsheets); $s++){
		if(empty($table) == true && $data->boundsheets[$s]['name'] && strpos($data->boundsheets[$s]['name'], 'Sheet') === false){
			$table_name = $data->boundsheets[$s]['name'];
			$check = false;
		}

		for ($row=1; $row <= $data->sheets[$s]['numRows']; $row++){
				if($row == $field_row_num){
					for ($cell=1; $cell<=$cell_num; $cell++){
						if($data->sheets[$s]['cells'][$row][$cell]){
							$field_names[$cell] = $data->sheets[$s]['cells'][$row][$cell];
						}
					}
				}
				if($row <= $field_row_num) continue;

				$update_data = array();
				for ($cell=1; $cell<=$cell_num; $cell++){
					if(isset($field_names[$cell]) && $primary_key != $cell){
						$update_data[] = $field_names[$cell].' = "'.$data->sheets[$s]['cells'][$row][$cell].'"';
					}
				}

				if(empty($update_data)){
					$err[] = $row;
					continue;
				}
				
				$primary_field = $field_names[$primary_key];
				$primary_value = $data->sheets[$s]['cells'][$row][$primary_key];
				if($check){
					$check_query = tep_db_query(sprintf('select %s from %s where %s = "%s"', $primary_field, $table_name, $primary_field, $primary_value));
					if(tep_db_num_rows($check_query) == 0){
						$rownvail[] = sprintf("Table:%s; Field:%s; Value:%s\n", $table_name, $primary_field, $primary_value);
						continue;
					}
				}

				$sql .= sprintf("update %s set %s \t where %s = '%s';\n", $table_name, join($update_data, ','), $primary_field, $primary_value);
				//tep_db_query($sql);
		}
	}
	unset($update_data, $field_names);
	//file_put_contents(iconv('UTF-8', 'GBK','D:/yu/桌面/记录/接收/2013/产品价格20121303_sql.txt'),$sql);
	file_put_contents($file_path.'.txt',$sql);
	echo '<pre>';
	echo "err rows:\n";
	print_r($err);
	echo "vail data:\n";
	print_r($rownvail);
	echo '</pre>';

}
//update_excel('D:/yu/桌面/记录/接收/2013/test130315.xls','',2);
update_excel('D:/yu/桌面/记录/接收/2013/test_sheet2.xls', '', 6);
?>