<?php
include("init.php");
require_once 'Excel/reader.php';
header('Content-type:text/html;charset=utf-8');
//header('Content-type:text/html;charset=ISO-8859-1');
define('LANGUAGE_ID', 5);
/**
* 
* @authoer nathan 
* @access public 
* @param 
* @return 
*/
function update_membership_point_rule($file_path){
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('UTF-8');
	$data->read($file_path);
	for ( $s=0; $s<count($data->boundsheets); $s++){
		for ($i=2; $i <= $data->sheets[$s]['numRows']; $i++){
				$id = $data->sheets[$s]['cells'][$i][1];
				$name = mysql_real_escape_string($data->sheets[$s]['cells'][$i][2]);
				if(empty($id)) continue;
				echo 'update membership_point_rule_description set rule_title="'.$name.'" where rule_id="'.$id.'" and language_id="'.LANGUAGE_ID.'";'.'<br/>';
				//tep_db_query('update categories_description set categories_name="'.mysql_real_escape_string($name).'", categories_heading_title="'.mysql_real_escape_string($title).'" where categories_id="'.$id.'" and language_id="2"');
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
function update_categories($file_path){
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('UTF-8');
	$data->read($file_path);
	for ( $s=0, $len = count($data->boundsheets); $s< $len; $s++){
		for ($i=2; $i <= $data->sheets[$s]['numRows']; $i++){
				$id = $data->sheets[$s]['cells'][$i][1];
				$name = mysql_real_escape_string($data->sheets[$s]['cells'][$i][2]);
				$title = mysql_real_escape_string($data->sheets[$s]['cells'][$i][3]);
				if(empty($id)) continue;
				echo 'update categories_description set categories_name="'.$name.'", categories_heading_title="'.$title.'" where categories_id="'.$id.'" and language_id="'.LANGUAGE_ID.'";'.'<br/>';
				//tep_db_query('update categories_description set categories_name="'.mysql_real_escape_string($name).'", categories_heading_title="'.mysql_real_escape_string($title).'" where categories_id="'.$id.'" and language_id="2"');
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
function update_faq($file_path){
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('utf-8');
	$data->read($file_path);
	for ( $s=0; $s<count($data->boundsheets); $s++){
		for ($i=2; $i <= $data->sheets[$s]['numRows']; $i++){
				$id = $data->sheets[$s]['cells'][$i][1];
				$question = mysql_real_escape_string($data->sheets[$s]['cells'][$i][3]);
				$answer = mysql_real_escape_string($data->sheets[$s]['cells'][$i][4]);
				if(empty($id)) continue;
				//$sql = 'insert into faq_description (faq_id, language_id, question, answer) values ("'. $id.'","6","'.mysql_real_escape_string($question).'","'.mysql_real_escape_string($answer).'")';
				$sql = 'update faq_description set question = "'.$question.'", answer = "'.$answer.'" where faq_id = "'.(int)$id.'" and language_id = "'.LANGUAGE_ID.'"';
				echo $sql.';<br/>';
				//tep_db_query($sql);
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
function update_faq_categories($file_path){
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('UTF-8');
	$data->read($file_path);
	for ( $s=0; $s<count($data->boundsheets); $s++){
		for ($i=2; $i <= $data->sheets[$s]['numRows']; $i++){
				$id = $data->sheets[$s]['cells'][$i][1];
				$lang_id = $data->sheets[$s]['cells'][$i][2];
				$name = mysql_real_escape_string($data->sheets[$s]['cells'][$i][3]);
				$description = mysql_real_escape_string($data->sheets[$s]['cells'][$i][4]);
				if(empty($id)) continue;
				$sql = 'update faq_categories_description set categories_name = "'.$name.'", categories_description = "'.$description.'" where categories_id = "'.$id.'" and language_id="'.LANGUAGE_ID.'"';
				echo $sql.';<br/>';
				//tep_db_query($sql);
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
function insert_sql($file_path){
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('UTF-8');
	$data->read($file_path);
	$sql = 'insert into products_weight_203 (id,products_model,weight) values ';
	for ( $s=0; $s<count($data->boundsheets); $s++){
		for ($i=2; $i <= $data->sheets[$s]['numRows']; $i++){
				$id = $data->sheets[$s]['cells'][$i][1];
				$model = $data->sheets[$s]['cells'][$i][2];
				$weight = $data->sheets[$s]['cells'][$i][3];
				if(empty($id)) continue;
				$sql.= ' ("'.$id.'","'.$model.'","'.$weight.'"),';
				//tep_db_query($sql);
		}
	}
	echo trim($sql, ',').';<br/>';
}

function update_products_option($file_path, $language_id = LANGUAGE_ID){
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('UTF-8');
	$data->read($file_path);
	for ( $s=0; $s<count($data->boundsheets); $s++){
		for ($i=2; $i <= $data->sheets[$s]['numRows']; $i++){
			$id = $data->sheets[$s]['cells'][$i][1];
			$name = mysql_real_escape_string($data->sheets[$s]['cells'][$i][2]);
			$instruct = mysql_real_escape_string($data->sheets[$s]['cells'][$i][3]);

			if(empty($id)) continue;
			$sql = "insert into products_options_text (products_options_text_id, language_id, products_options_name, products_options_instruct) values ('{$id}','{$language_id}','{$name}','{$instruct}') on duplicate key update products_options_name = values(products_options_name), products_options_instruct = values(products_options_instruct)";
			echo $sql.';<br/>';
		}
	}
}
function update_products_option_values($file_path, $language_id = LANGUAGE_ID){
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('UTF-8');
	$data->read($file_path);
	for ( $s=0; $s<count($data->boundsheets); $s++){
		for ($i=2; $i <= $data->sheets[$s]['numRows']; $i++){
			$id = $data->sheets[$s]['cells'][$i][1];
			$name = mysql_real_escape_string($data->sheets[$s]['cells'][$i][2]);

			if(empty($id)) continue;
			$sql = "insert into products_options_value_text (options_value_id, language_id, options_value_name) values ('{$id}','{$language_id}','{$name}') on duplicate key update options_value_name = values(options_value_name)";
			echo $sql.';<br/>';
		}
	}
}

/*
//payment payvision
$file_path = 'iso_country.xls';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('UTF-8');
$data->read($file_path);
for ( $s=0; $s<count($data->boundsheets); $s++){
	for ($i=3; $i <= $data->sheets[$s]['numRows']; $i++){
			$name = $data->sheets[$s]['cells'][$i][1];
			$iso_2 = $data->sheets[$s]['cells'][$i][2];
			$iso_3 = $data->sheets[$s]['cells'][$i][3];
			$numerice = $data->sheets[$s]['cells'][$i][7];
			if(empty($numerice)) continue;
			$sql = 'insert into payvision_countries (countries_name, countries_iso_code_2, countries_iso_code_3, countries_numeric) values ("'. mysql_real_escape_string($name) .'","'. $iso_2.'","'. $iso_3 .'","'.$numerice.'")';
			echo $sql.';<br/>';
			//tep_db_query($sql);
	}
}
//
$file_path = 'iso_currency.xls';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('UTF-8');
$data->read($file_path);
$arr = array();
for ( $s=0; $s<count($data->boundsheets); $s++){
	for ($i=4; $i <= $data->sheets[$s]['numRows']; $i++){
			$name = $data->sheets[$s]['cells'][$i][2];
			$code = $data->sheets[$s]['cells'][$i][3];
			$numerice = $data->sheets[$s]['cells'][$i][4];
			if(in_array($code, $arr) || empty($numerice)) continue;
			$arr[] = $code;
			$sql = 'insert into payvision_currency (currency_title, currency_code, currency_numeric) values ("'. mysql_real_escape_string($name) .'","'. $code.'","'.$numerice.'")';
			echo $sql.';<br/>';
			//tep_db_query($sql);
	}
}
*/
/*
$file_path = iconv('UTF-8', 'GBK','D:/yu/桌面/资料/uk_usa-battery.xls');
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('UTF-8');
$data->read($file_path);
$arr = array();
for ( $s=0; $s<count($data->boundsheets); $s++){
	for ($i=2; $i <= $data->sheets[$s]['numRows']; $i++){
			$model = $data->sheets[$s]['cells'][$i][1];
			$price = $data->sheets[$s]['cells'][$i][7];
			$discount = $data->sheets[$s]['cells'][$i][8] * 100;
			$model = $data->sheets[$s]['cells'][$i][1];
			$price = $data->sheets[$s]['cells'][$i][3];
			$discount = $data->sheets[$s]['cells'][$i][4];
			if(!($model && $price && $discount)){
				$err[] = $i;
				continue;
			}
			$arr[] = $code;
			$sql = 'update products set products_price = "'.$price.'",products_discount = "'.$discount.'" where products_model = "'.$model.'"';
			echo $sql.';<br/>';
			//tep_db_query($sql);
	}
}

$file_path = iconv('UTF-8', 'GBK','D:/yu/桌面/记录/products/iphone_access.xls');
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('UTF-8');
$data->read($file_path);
for ( $s=0; $s<count($data->boundsheets); $s++){
	for ($i=2; $i <= $data->sheets[$s]['numRows']; $i++){
			$model = $data->sheets[$s]['cells'][$i][1];
			$price = $data->sheets[$s]['cells'][$i][2];
			$discount = $data->sheets[$s]['cells'][$i][4];
			if(!($model)){
				$err[] = $i;
				continue;
			}
			//$price = number_format($price * 0.5,2);
			$sql = 'update products set products_price = "'.$price.'",products_discount = "'.$discount.'" where products_model = "'.$model.'"';
			echo $sql.';<br/>';
			//tep_db_query($sql);
	}
}
var_dump($err);die();
*/

//$file_path = iconv('UTF-8', 'GBK','D:/yu/桌面/翻译/德语/');
//$file_path = 'excel_langauge/fr/';
//update_membership_point_rule($file_path . 'membership_point_rule_description_2012-04-16_114645.xls');
//update_categories($file_path . 'categories_description_2012-07-02_121712.xls');//227
//update_categories($file_path . 'categories_description_2012-06-13_121755.xls');//
//update_categories($file_path . 'french_categories_description_2012-03-21.xls');
//update_faq($file_path . 'faq_description_2012-04-16_114627_re.xls');
//update_faq_categories($file_path . 'faq_categories_description_2012-04-16_114636.xls');
//update_products_option($file_path . 'products_options_text_2012-05-23_122650.xls');
//update_products_option_values($file_path .'products_options_value_text_2012-05-23_122917.xls');

//insert_sql('203_products.xls');

/*
$sql = "SELECT products_model,products_cell FROM products_parameter WHERE products_type = 'b' AND products_cell != ''";
$query = tep_db_query($sql);
while($row  = tep_db_fetch_array($query)){
	echo "UPDATE products_parameter SET products_cell = '".$row['products_cell']."'  WHERE products_model='".$row['products_model']."';<br/>";
}*/

$sql = '';
$invail = array();
$file_path = iconv('UTF-8', 'GBK','D:/yu/桌面/记录/接收/2012/产品重量20121203.xls');
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('UTF-8');
$data->read($file_path);
for ( $s=0; $s<count($data->boundsheets); $s++){
	for ($i=2; $i <= $data->sheets[$s]['numRows']; $i++){
			$model = $data->sheets[$s]['cells'][$i][1];
			$weight = $data->sheets[$s]['cells'][$i][2];
			if(!($model)){
				$err[] = $i;
				continue;
			}
			//$price = number_format($price * 0.5,2);
			$check_query = tep_db_query('select products_model from products where products_model ="'.$model.'"');
			if(tep_db_num_rows($check_query) > 0){
				$sql .= 'update products set products_weight = "'.$weight.'" where products_model = "'.$model.'";'."\n";
			}else{
				$invail[] = $model;
			}
			//tep_db_query($sql);
	}
}
file_put_contents(iconv('UTF-8', 'GBK','D:/yu/桌面/记录/接收/2012/产品重量20121203_sql.txt'),$sql);
echo '<pre>';
print_r($err);
print_r($invail);
echo '</pre>';
?>