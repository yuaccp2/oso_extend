<?php
/*
small:
E30USB9-s.JPG
E30USB9_2-s.JPG
.....
E30USB9_5-s.JPG

large:
E30USB9-l.JPG
E30USB9_2-l.JPG
.....
E30USB9_5-l.JPG

xxs:
E30USB9.JPG
E30USB9_2.JPG
.....
E30USB9_5.JPG
*/

include 'init.php';	

//$sql = 'select products_model from products where products_status = 1 group by products_model order by products_model';
$sql = 'SELECT products_model FROM products WHERE products_model NOT IN (SELECT products_code FROM products_images) and products_status = 1 GROUP BY products_model';
$query = tep_db_query($sql);
$type = $_GET['type'];
if($type == 'l'){
	$image_flag = '-l';
	$image_dir = 'large';
}elseif($type == 'x'){
	$image_flag = '';
	$image_dir = 'xxs';
}elseif($type == 'xs'){
	$image_flag = '';
	$image_dir = 'xs';
}else{
	$image_flag = '-s';
	$image_dir = 'small';
}
if($_SERVER['HTTP_HOST'] == 'web.com'){
	$image_path = 'D:\\phpProject\\espow.com\\images\\'.$image_dir.'\\';//EMOHDD01-s.JPG
}else{
	$image_path = 'E:\\xampp\\htdocs\\espow.com\\images\\'.$image_dir.'\\';
}
$content = '';
$error = array();
$sql_arr = array();
$all_rows = 0;
while($info = tep_db_fetch_array($query)){
	$name = $info['products_model'] .$image_flag.'.JPG';
	if(is_file($image_path . $name)){
		//$content.= 'INSERT INTO products_images (products_code, products_images, default_flag, images_sort)	VALUES	("'.$info['products_model'].'", "'.$name.'", "y", "1");'."\n";
		$sql_arr[] = '("'.$info['products_model'].'", "'.$name.'", "y", "1")';
		for ($i=2; ; $i++){
			if(is_file($image_path . $info['products_model'] .'_'.$i.$image_flag.'.JPG')){
				//$content.= 'INSERT INTO products_images (products_code, products_images, default_flag, images_sort)	VALUES	("'.$info['products_model'].'", "'.$info['products_model'] .'_'.$i.$image_flag.'.JPG'.'", "n", "'.$i.'");'."\n";
				$sql_arr[] = '("'.$info['products_model'].'", "'.$name.'", "y", "1")';

			}else{
				break;
			}
		}
	}else{
		$error[] = $info['products_model'];
	}
}
if($sql_arr && $image_dir == 'small'){
	$content = 'INSERT INTO products_images (products_code, products_images, default_flag, images_sort)	VALUES '. join(',',$sql_arr).';';
	//file_put_contents('products_images_'.$image_dir.'_'.date('YmdHis').'.sql', $content);
	tep_db_query($content);
	$rows = mysql_affected_rows();
	echo '<h2>Success excute '.$rows.' rows</h2>';
}else{
	echo '<h2>Success excute 0 rows</h2>';
}
if($error){
echo '<h1>no images:</h1>';
echo join(',',$error);
}
?>