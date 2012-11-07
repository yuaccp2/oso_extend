<?php
date_default_timezone_set('UTC');
header('content-type:text/html; charset=utf-8');
function _mkdir($dir){
	if(is_dir($dir) || @mkdir($dir)) return true;
	if(!_mkdir(dirname($dir))) return false;
	return @mkdir($dir);
}

function getfiles($path, &$arr)  
 {  
    if(!is_dir($path)) return;  
    $handle  = opendir($path);  
    while( false !== ($file = readdir($handle)))  
    {  
        if($file != '.'  &&  $file!='..')  
        {  
            $path2= $path.'\\\\'.$file;  
            if(is_dir($path2))  
            {  
               getfiles($path2, $arr);  
            }else 
            {  
                $arr[] =  iconv('GBK','UTF-8',$path.'\\\\'.$file);  
            }  
        }  
    }  
}  
$arr = array();

$tar = dirname(__FILE__).'\\products_images\\';
//$orc = 'D:\\phpProject\\espow.com\\';
$orc = addslashes($_POST['path']);
$desc_flag = $_POST['desc_flag'];
$ext = date('Ymd').'\\';
if(isset($_POST['sub_dir']) && $_POST['sub_dir']){
	$ext = $_POST['sub_dir'].'\\';
}
if(isset($_POST['content'])){
	$all_file = array();
	getfiles($orc,$all_file);

	$content = str_replace('/','\\',$_POST['content']);
	$arr = explode("\r\n",trim($content,"\r\n"));
	foreach ($arr as $key => $val){
		$val = trim($val, '\\');
		$val = trim($val);
		$dir = trim(str_replace(basename($val), '', $val), '\\').'\\';
		_mkdir($tar.$ext.$dir);
		foreach ($all_file as $_key => $file_path){
			$file = basename($file_path);
			$true = false;
			if($desc_flag){
				$true = (strlen($file) == strlen($val)+2+4) && substr($file,0,strlen($file)-4) == $val;
			}
			if($file == $val.'.jpg' || stripos($file,$val.'_') || $true){
				var_dump($file,substr($file,0,strlen($file)-4),$val);die();
				if(is_file($file_path)){
					copy($file_path, $tar . $ext . $file);
				}else{
					echo $file_path.' is not file.<br/>';
				}
			}
		}
	}
	//header('location:'.basename($_SERVER['PHP_SELF']));
}
?>

<form method="post" action="">
<pre style="color:#ccc">
源目录：<input type="text" name="path" value="E:\espow_desc">
默认目标目录：<?=$tar.$ext?> 
eg.上传列表：

</pre>
	desc<input type="checkbox" name="desc_flag"><br/>
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
	<textarea name="content" rows="18" cols="90">EBATTER008</textarea>
	<input type="submit">
</form>