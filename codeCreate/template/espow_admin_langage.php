<?=tag('php')."\n"?>
/*
   <?=basename($filename) . ' ' . date('Y-m-d')?>
*/
  
define('HEADING_TITLE', '<?=$uc_classname?>');
define('HEADING_TITLE_SEARCH', 'Search:');

define('TABLE_HEADING_<?=strtoupper($prime_key)?>', HEADING_TITLE .' ID');
<?php
	foreach ($fields as $key => $val){
?>
define('TABLE_HEADING_<?=strtoupper($val)?>', '<?=format_word($val, true)?>');
<?php
	}
?>
define('TABLE_HEADING_ACTION', 'Action');

define('HEADING_PERSONAL', HEADING_TITLE .' For System');
<?php
	foreach ($fields as $key => $val){
?>
define('ENTRY_<?=strtoupper($val)?>', '<?=format_word($val, true)?>: ');
<?php
	}
?>

define('TEXT_DISPLAY_NUMBER', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> '. HEADING_TITLE .')');
define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this '. HEADING_TITLE .'?');
define('TEXT_INFO_HEADING_DELETE', 'Delete ' .  HEADING_TITLE);
define('MESSAGE_DELETE_WARNING', 'You are not allowed to delete this '. HEADING_TITLE .':');
define('MESSAGE_UPDATE_SUCCESS', HEADING_TITLE . ' Update successful!');
define('MESSAGE_INSERT_SUCCESS', HEADING_TITLE . ' Add successful!');
define('MESSAGE_DELETE_SUCCESS', HEADING_TITLE . ' Delete successful!');
define('MESSAGE_UPDATE_FAIL', HEADING_TITLE . ': Update failure!');
define('MESSAGE_INSERT_FAIL', HEADING_TITLE . ': Add failure!');
define('MESSAGE_DELETE_FAIL', HEADING_TITLE . ': Delete failure!');
define('MESSAGE_UPDATE_STATUS_SUCCESS', 'The Status update successful!');
define('MESSAGE_NOT_EXISTS_ERROR', HEADING_TITLE . ' is not exists!');

define('TEXT_SORT', 'Sort ');
?>