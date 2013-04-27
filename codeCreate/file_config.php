<?php
	//选项文件
	$item_config = array('control'=>'控制器', 'model'=>'业务模型','view'=>'视图', 'langage'=>'语言包');
	
	//模版文件
	$template_path = array(
						'espow_admin' => array(
							'control' => 'template/espow_admin.php',
							'model' => 'template/espow_admin_model.php',
							'langage' => 'template/espow_admin_langage.php'
						)
					);
	//目标文件
	$target_path = array(
				'espow_admin' => array(
									'name_fix' => array(
										'control'=>'.php',
										'model'=>'_model.php',
										'langage'=>'.php',
									),
									'path' => array(
										'control' => 'D:/phpProject/espow.com/admin/',
										'model' => 'D:/phpProject/espow.com/admin/includes/classes/',
										'langage' => 'D:/phpProject/espow.com/admin/includes/languages/english/'
									)
							)
			);

?>