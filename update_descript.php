<?php
	include('init.php');
	$sql = 'SELECT * FROM products_description 
			WHERE products_description LIKE "%http://192.168.0.22/espow.com%"';
	$query = tep_db_query($sql);
	$update_sql = array();
	while($row = tep_db_fetch_array($query)){
		$descript = str_replace('http://192.168.0.22/espow.com/admin/','http://www.espow.com/', $row['products_description']);
		$update_sql[] = 'update products_description set products_description="'.addslashes($descript).'" where products_id ='.$row['products_id'].' and language_id = '.$row['language_id'];
	}
	echo join(";\n",$update_sql);
?>