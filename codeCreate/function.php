<?php
function tag($type = ''){
	if($type == 'php'){
		echo "<?php ";
	}elseif($type == '='){
		echo "<?=";
	}else{
		echo "?>";
	}
}

function format_word($str, $first_UC = false){
	$ret = '';
	$arr_str = explode('_',$str);
	if(!$arr_str) return $ret;
	foreach ($arr_str as $key => $val){
		if($first_UC) $val = ucfirst($val);
		$ret .= $val . ' ';
	}
	return $ret;
}	
?>