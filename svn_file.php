<?php
header('content-type:text/html; charset=utf-8');
function _mkdir($dir){
	if(is_dir($dir) || @mkdir($dir)) return true;
	if(!_mkdir(dirname($dir))) return false;
	return @mkdir($dir);
}

$tar = dirname(__FILE__).'\\version\\';
$orc = 'D:\\phpProject\\espow.com\\';
$ext = date('Ymd').'\\';
if(isset($_POST['sub_dir']) && $_POST['sub_dir']){
	$ext = $_POST['sub_dir'].'\\';
}
if(isset($_POST['content'])){
	$content = str_replace('/','\\',$_POST['content']);
	$arr = explode("\r\n",trim($content,"\r\n"));
	foreach ($arr as $key => $val){
		$val = trim($val, '\\');
		$val = trim($val);
		$dir = trim(str_replace(basename($val), '', $val), '\\');
		_mkdir($tar.$ext.$dir);
		if(is_file($orc . $val)){
			copy($orc . $val, $tar . $ext . $val);
		}else{
			echo $orc . $val.' is not file.<br/>';
		}
	}
	header('location:'.basename($_SERVER['PHP_SELF']));
}
?>

<form method="post" action="">
<pre style="color:#ccc">
源目录：<?=$orc?> 
默认目标目录：<?=$tar.$ext?> 
eg. svn上传列表：
	admin\customers.php  
	admin\reviews.php  
	includes\database_tables.php  
	includes\filenames.php  
	includes\application_top.php  
</pre>
设定目标目录：<?=$tar?><br/>
	<?php
		if($handle = opendir($tar)){
			while(false !== ($file = readdir($handle))){
				if($file != '.' && $file!= '..' && is_dir($tar.$file)){
					echo sprintf('%s<input type="radio" name="sub_dir" value="%s"><br/>',$file,$file);
				}
			}
		}
	?>
	<textarea name="content" rows="18" cols="90"></textarea>
	<input type="submit">
</form>