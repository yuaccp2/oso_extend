<?php

/*
	include('reader.php');
	$excel_data = new Spreadsheet_Excel_Reader();
	$excel_data->setOutputEncoding('UTF-8');
	$excel_data->read($file_name);
	//var_dump($excel_data->boundsheets,$excel_data->sheets[0]['numRows'], $excel_data->sheets[0]['cells'][2]);die();
	for ( $s=0; $s<count($excel_data->boundsheets); $s++){
		for ($i=2; $i <= $excel_data->sheets[$s]['numRows']; $i++){
			$model = $excel_data->sheets[$s]['cells'][$i][1];
		}
	}
*/

$filename = 'example.xls';
$table_data = array(
				array('user_name'=>'yu','password'=>'123456'),
				array('user_name'=>'wsx','password'=>'123456'),
				array('user_name'=>'edc','password'=>'123456')
			);
$field_list = current(array_keys($table_data));

//require_once 'Excel/Writer.php';
require_once 'Writer.php';
$workbook = new Spreadsheet_Excel_Writer();
$workbook->setVersion(8);
$worksheet = & $workbook->addWorksheet("Sheet1");
//$worksheet->setInputEncoding('UTF-8');
$worksheet->hideGridLines();
$worksheet->setMargins(0);	//margin 
//set the col width
foreach ($field_list as $key => $val){
	$worksheet->setColumn(0,$key,30); 
}

//set the col format
$format_title =& $workbook->addFormat(array('VAlign' => 'vcenter',
									   'Align' => 'center',
									   'FontFamily' => 'Georgia',
									   'Bold' => 1,
									   'Border' => 1,
									   'Size' => 11));
$format_title->setFgColor(22);
$format1 = &$workbook->addFormat(array('VAlign' => 'vcenter',
									   'FontFamily' => 'Arial',
									   'Border' => 1,
									   'Size' => 11));
$format1->setFgColor(9);
$format2 = &$workbook->addFormat(array('VAlign' => 'vcenter',
									   'FontFamily' => 'Arial',
									   'Border' => 1,
									   'Bold' => 1,
									   'Color' => 'red',
									   'Size' => 11));
$format2->setFgColor(9);


//set the title
foreach ($field_list as $key => $val){
	$worksheet->writeString(0, $key, $val, $format_title);    
}
//set the content
$index = 1;
foreach ($table_data as $key => $info){
	$i = 0;
	foreach ($info as $field => $val){
		$worksheet->writeString($index, $i, $val, $format1);    
		$i++;
	}
	$index++;
}

$format_title->setTextWrap();
$format1->setTextWrap();
$format2->setTextWrap();

$workbook->send($file_name);
$workbook->close();

?>