<?php
	include("D:/phpProject/espow.com/admin/includes/classes/verification.php");
	$veri = new Verification();
	$_POST['coupon_name'] = array(array('B','C',array('a')));
	$v_config = array(
		array('field_name' => 'coupon_name',
			  'rule' => 'required')
	);
	$veri->set_config($v_config);
	$ret = $veri->validation();
	$message= $veri->get_message();
	var_dump($ret,$message);die();
?>