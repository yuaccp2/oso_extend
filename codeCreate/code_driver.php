<?php
header('Content-Type:text/html; charset=utf-8;');


	require('db_config.php');
	require('file_config.php');
	require('function.php');
	require('create_code_class.php');

	$code_class = new Create_code_class($default_datebase);
	$table_list = $code_class->get_table_list();
	
	if(isset($_POST['table_name']) && $_POST['table_name']){
		$table_name = strip_tags($_POST['table_name']);
		$item_list = $_POST['item_name'];
		$template_name = strip_tags($_POST['template_name']);
		if($item_list && $template_name){
			$code_class->get_field($table_name);
			
			$target_info = $target_path[$template_name];
			$template_info = $template_path[$template_name];
			
			foreach ($item_list as $key => $val){
				if(!isset($template_info[$val])) continue;
				$code_class->create_file($template_info[$val], $target_info['path'][$val] . $table_name . $target_info['name_fix'][$val]);
			}
			echo 'define(\'TABLE_'.strtoupper($table_name).'\', \''. $table_name . '\');'.'<br/>';
			echo 'define(\'FILENAME_'.strtoupper($table_name).'\', \''. $table_name .'.php\');'.'<br/>';
		}
	}
	mysql_close();
?>

<form method="post" action="">
	<table>
	<tr>
		<td>表名：</td>
		<td>
			<select name="table_name">
			<?php
				foreach ($table_list as $key => $val){
			?>
				<option value="<?=$val?>"><?=$val?></option>
			<?php
				}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td>模版：</td>
		<td>
			<select name="template_name">
			<?php
				foreach ($template_path as $key => $val){
			?>
				<option value="<?=$key?>" selected><?=$key?></option>
			<?php
				}
			?>
			</select>		
		</td>
	</tr>
	<tr>
		<td>文件：</td>
		<td>
			<?php
				foreach ($item_config as $key => $val){
			?>
			<input type="checkbox" name="item_name[]" value="<?=$key?>" checked><?=$val?><br/>
			<?php
				}
			?>
		
		</td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit"></td>
	</tr>
	</table>
	
</form>