<?php
function my_error_handler($severity, $message, $filepath, $line){
	$levels = array(
						E_ERROR				=>	'Error',
						E_WARNING			=>	'Warning',
						E_PARSE				=>	'Parsing Error',
						E_NOTICE			=>	'Notice',
						E_CORE_ERROR		=>	'Core Error',
						E_CORE_WARNING		=>	'Core Warning',
						E_COMPILE_ERROR		=>	'Compile Error',
						E_COMPILE_WARNING	=>	'Compile Warning',
						E_USER_ERROR		=>	'User Error',
						E_USER_WARNING		=>	'User Warning',
						E_USER_NOTICE		=>	'User Notice',
						E_STRICT			=>	'Runtime Notice'
					);

	if ($severity == E_STRICT || $severity == E_NOTICE)
	{
		return;
	}

	$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$content .= sprintf('%s %s %s %s %s', date('Y-m-d H:i:s'), $levels[$severity], $message, $filepath, $line."\n");
	$content .= $url."\n";
	if($_POST){
		$content .= jsoncode($_POST)."\n";
	}
	file_put_contents(DIR_FS_DEBUG.'error.txt', $content, FILE_APPEND);
}
set_error_handler("my_error_handler");
?>