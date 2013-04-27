<?=tag('php')."\n"?>
/**
* <?=basename($filename)."\n"?>
* @author espow team nathan
* @date <?=date('Y-m-d H:i:s')."\n"?>
* @package
* @licese http://www.oscommerce.com
* @version 1.1
* @copyright (c) 2003 osCommerce
*/
  require('includes/application_top.php');

  require(DIR_WS_CLASSES . '<?=$model_name?>.php');

  //实例化业务类
  $<?=$model_name?> = new <?=ucfirst($model_name)?>();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $action_update_tag = 'new,edit,newconfirm,update';
  $action_do_tag = 'newconfirm,update,deleteconfirm,setflag';

  if (tep_not_null($action) && strpos($action_do_tag, $action)!==false ) {

	$verification_class = null;
	if(strpos($action_update_tag, $action) !== false){

		include(DIR_WS_LANGUAGES . $language . '/class/verification.php');
		require(DIR_WS_CLASSES . 'verification.php');

		//实例化验证类
		$verification_class = new Verification();

		//验证配置信息 
		$v_config = array(
		<?php
			$length = count($fields_list);
			foreach ($fields_list as $key => $val){
				if($val['Extra'] != 'auto_increment'){
					$verif_rule = '';
					if($val['Null'] == 'YES') $verif_rule .= 'required|';
					if(strpos($val['Type'], 'int') !== false){
						$verif_rule .= 'patterns[integer]|';
					}elseif(strpos($val['Type'], 'char') !== false){
						preg_match('/.*char\((\d+)\)/', $val['Type'], $arr);
						if($arr && isset($arr[1])){
							$verif_rule .= 'min_length[2]|max_length['. $arr[1] .']|';
						}
					}elseif($val['Type'] == 'numeric'){
						$verif_rule .= 'patterns[number]';
					}elseif(strpos($val['Type'], 'time') !== false){
						$verif_rule .= 'patterns[date]|';
					}elseif(strpos($val['Type'], 'enum') !== false){
						preg_match('/enum\((.*)\)/', $val['Type'], $arr);
						if($arr && isset($arr[1])){
							$arr_list = explode(',',str_replace('\'','',$arr[1]));
							$arr_list = json_encode((object)$arr_list);
							$verif_rule .= 'in_enum['.$arr_list.']|';
						}
					}
		?>
			array('field_name' => '<?=$val['Field']?>',
				  'rule' => '<?=trim($verif_rule, '|')?>')<?=($key+1 == $length ? '' : ",\n")?>
		<?php
				}
			}
		?>
		);

		//设置验证配置信息
		$verification_class->set_config($v_config);
	}

    switch ($action) {

      case 'newconfirm' :
        $error = false;
		if($verification_class->validation()){//执行验证
			
			<?php
				foreach ($fields as $key => $val){
			?>$<?=$val?> = tep_db_prepare_input($_POST['<?=$val?>']);		
			<?php 
				}
			?>

			$<?=$prime_key?> = $<?=$model_name?>->do_insert(<?=$fields_str?>);

			if($<?=$prime_key?>){
				$messageStack->add_session('search', MESSAGE_INSERT_SUCCESS, 'success');
			}else{
				$error = true;
				$messageStack->add('search', MESSAGE_INSERT_ERROR, 'error');
			}
		}else{
			$error = $verification_class->get_message();
			foreach ($error as $key => $val){
				$messageStack->add('search', $val, 'error');
			}
		}
		if(!$error){
			$rows_num = $<?=$model_name?>->get_row_total();
			$page_num = ceil($rows_num / MAX_DISPLAY_SEARCH_RESULTS);
			tep_redirect(tep_href_link(<?=$filename_href?>, 'ID='.$<?=$prime_key?>.'&page='.$page_num));
		}
        break;

      case 'update':

		$error = false;
        $<?=$prime_key?> = tep_db_prepare_input($_GET['ID']);

		if($verification_class->validation() && $<?=$model_name?>->check_exists_id($<?=$prime_key?>)){

			<?php
				foreach ($fields as $key => $val){
			?>$<?=$val?> = tep_db_prepare_input($_POST['<?=$val?>']);		
			<?php 
				}
			?>

			$<?=$model_name?>->do_update(<?=$fields_str?>);
			$messageStack->add_session('search', MESSAGE_UPDATE_SUCCESS, 'success');
		}else{
			$error = $verification_class->get_message();
			foreach ($error as $key => $val){
				$messageStack->add('search', $val, 'error');
			}
		}

		if(!$error){
			tep_redirect(tep_href_link(<?=$filename_href?>, tep_get_all_get_params(array('action'))));
		}
        break;

      case 'deleteconfirm':

        $<?=$prime_key?> = tep_db_prepare_input($_GET['ID']);

		$exclude = array('ID', 'action');
		if($<?=$model_name?>->do_delete($<?=$prime_key?>)){
			$messageStack->add_session('search', MESSAGE_DELETE_SUCCESS, 'success');
		}else{
			$messageStack->add_session('search', MESSAGE_DELETE_ERROR, 'error');
			$exclude = array('action');
		}
		tep_redirect(tep_href_link(<?=$filename_href?>, tep_get_all_get_params($exclude)));
        break;
      case 'setflag':
        $<?=$prime_key?> = tep_db_prepare_input($_GET['ID']);

		if($<?=$model_name?>->set_status($_GET['flag'], $<?=$prime_key?>)){
			$messageStack->add_session('search', MESSAGE_UPDATE_STATUS_SUCCESS, 'success');
		}else{
			$messageStack->add_session('search', MESSAGE_NOT_EXISTS_ERROR, 'error');
		}
		tep_redirect(tep_href_link(<?=$filename_href?>, tep_get_all_get_params(array('ID', 'action', 'page'))));
		break;
    }
  }
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?=tag('=')?>HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=tag('=')?>CHARSET; ?>">
<title><?=tag('=')?>TITLE; ?></title>
	<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
	<script language="javascript" src="includes/menu.js"></script>
	<script language="javascript" src="includes/general.js"></script>
	<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
	<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
	<script language="JavaScript" src="includes/javascript/jquery.min.js"></script>

<?=tag('php')?>
  if($action && strpos($action_update_tag, $action)!==false){
?>
	<link type="text/css" rel="Stylesheet" href="includes/javascript/jQuery.validity/jquery.validity.css" />
	<script type="text/javascript" src="includes/javascript/jQuery.validity/jquery.validity.js"></script>
	<SCRIPT type="text/javascript">
	<!--
	$(function() { 
		$("form").validity(function() {
			<?php
				foreach ($fields_list as $key => $val){
					if($val['Extra'] != 'auto_increment'){
						if($val['Null'] == 'NO'){
							echo '$("#'.$val['Field'].'").require();'."\n";
						}
					}
				}

			?>
		});
	});
	//-->
	</SCRIPT>
<?=tag('php')?>
  }
?>

</head>
<body onLoad="SetFocus();">
<!-- header //-->
<?=tag('php')?> require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?=tag('=')?>BOX_WIDTH; ?>" valign="top"><table border="0" width="<?=tag('=')?>BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?=tag('php')?> require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?=tag('php')?>
	
 if($action == 'new' || $action == 'newconfirm') {
	//ADD VIEW
?>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?=tag('=')?>HEADING_TITLE; ?></td>
              <td class="pageHeading" align="right">&nbsp;</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td><?=tag('=')?>tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?=tag('=')?>HEADING_PERSONAL; ?></td>
      </tr>
      <tr>
        <td>
		 <?=tag('=')?>tep_draw_form('<?=$classname?>', <?=$filename_href?>, tep_get_all_get_params(array('action')) . 'action=newconfirm', 'post'); ?>
		  <table width="100%">
		   <tr>
		    <td  class="formArea">
			  <table border="0" cellspacing="2" cellpadding="2">
				<?php
					foreach ($fields_list as $key => $val){
						if($val['Extra'] == 'auto_increment') continue;
						$require = 'false';
						$option_str = '';
						if($val['Null'] == 'NO') $require = 'true';
						if(strpos($val['Type'], 'enum') !== false){
							preg_match('/enum\((.*)\)/', $val['Type'], $arr);
							if($arr && isset($arr[1])){
								$arr_list = explode(',',str_replace('\'','',$arr[1]));
								$option_str = 'array(';
								foreach ($arr_list as $_key => $_val){
									$option_str .='array("id"=>"'.$_val.'","text"=>"'.$_val.'"),';
								}
								$option_str = trim($option_str, ',');
								$option_str .= ')';
							}
						}
				?><tr>
					<td class="main"><?=tag('=')?>ENTRY_<?=strtoupper($val['Field'])?>; ?></td>
					<?php
						if($option_str){
					?><td class="main"><?=tag('=')?>tep_draw_pull_down_menu('<?=$val['Field']?>', <?=$option_str?>,'','id="<?=$val['Field']?>"',<?=$require?>); ?></td>
					<?php
						}elseif($val['Type'] == 'text'){
					?><td class="main"><?=tag('=')?>tep_draw_textarea_field('<?=$val['Field']?>', '', 60, 8)?></td>
					<?php
						}elseif(strpos($val['Field'], '_status') !== false && strpos($val['Type'], 'enum') === false){
					?>
					<?=tag('=')."\n"?>
							echo '<input type="radio" name="<?=$val['Field']?>" value="1"> '. IMAGE_ICON_STATUS_GREEN .
							'<input type="radio" name="<?=$val['Field']?>" value="0">'. IMAGE_ICON_STATUS_RED;
					?>	
					<?php
						}else{
					?><td class="main"><?=tag('=')?>tep_draw_input_field('<?=$val['Field']?>', $edit_info['<?=$val['Field']?>'],'id="<?=$val['Field']?>"',<?=$require?>); ?></td>
				<?php
						}
				?></tr>
				<?php
					}
					echo "\n";
				?>
			 </table>
		   </td>
		  </tr>
		  <tr><td><?=tag('=')?>tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
		  <tr>
			<td align="right" class="main">
				<?=tag('=')?>tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(<?=$filename_href?>, tep_get_all_get_params(array('action','ID'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>
			</td>
		  </tr>
		 </table>
        </form>
       </td>
      </tr>
<?=tag('php')?>
  } elseif($action == 'edit' or $action == 'update') {
	//edit view
	$<?=$prime_key?> = isset($_GET['ID']) ? (int)$_GET['ID'] : 0;
	$edit_info = $<?=$model_name?>->get_info($<?=$prime_key?>);

?>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?=tag('=')?>HEADING_TITLE; ?></td>
              <td class="pageHeading" align="right">&nbsp;</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td><?=tag('=')?>tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?=tag('=')?>HEADING_PERSONAL; ?></td>
      </tr>
      <tr>
        <td>
		 <?=tag('=')?>tep_draw_form('<?=$classname?>', <?=$filename_href?>, tep_get_all_get_params(array('action')) . 'action=update', 'post'); ?>
		  <table width="100%">
		   <tr>
		    <td  class="formArea">
			  <table border="0" cellspacing="2" cellpadding="2">
				<?php
					foreach ($fields_list as $key => $val){
						if($val['Extra'] == 'auto_increment') continue;
						$require = 'false';
						$option_str = '';
						if($val['Null'] == 'NO') $require = 'true';
						if(strpos($val['Type'], 'enum') !== false){
							preg_match('/enum\((.*)\)/', $val['Type'], $arr);
							if($arr && isset($arr[1])){
								$arr_list = explode(',',str_replace('\'','',$arr[1]));
								$option_str = 'array(';
								foreach ($arr_list as $_key => $_val){
									$option_str .='array("id"=>"'.$_val.'","text"=>"'.$_val.'"),';
								}
								$option_str = trim($option_str, ',');
								$option_str .= ')';
							}
						}?><tr>
					<td class="main"><?=tag('=')?>ENTRY_<?=strtoupper($val['Field'])?>; ?></td>
					<?php
						if($option_str){?><td class="main"><?=tag('=')?>tep_draw_pull_down_menu('<?=$val['Field']?>', <?=$option_str?>,$edit_info['<?=$val['Field']?>'],'id="<?=$val['Field']?>"',<?=$require?>); ?></td>
					<?php
						}elseif($val['Type'] == 'text'){
					?><td class="main"><?=tag('=')?>tep_draw_textarea_field('<?=$val['Field']?>', '', 60, 8, $edit_info['<?=$val['Field']?>'])?></td>
					<?php
						}elseif(strpos($val['Field'], '_status') !== false && strpos($val['Type'], 'enum') === false){
					?>
					<?=tag('=')."\n"?>if($edit_info['<?=$val['Field']?>'] == 1){
								$active = 'checked';
							}else{
								$inactive = 'checked';
							}
							echo '<input type="radio" name="<?=$val['Field']?>" value="1" '.$active.'> '. IMAGE_ICON_STATUS_GREEN .
							'<input type="radio" name="<?=$val['Field']?>" value="0" '.$inactive.'>'. IMAGE_ICON_STATUS_RED;
					?>	
					<?php
						}else{?><td class="main"><?=tag('=')?>tep_draw_input_field('<?=$val['Field']?>', $edit_info['<?=$val['Field']?>'],'id="<?=$val['Field']?>"',<?=$require?>); ?></td>
				<?php
						}
				?></tr>
				<?php
					}
					echo "\n";
				?>
			 </table>
		   </td>
		  </tr>
		  <tr><td><?=tag('=')?>tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
		  <tr>
			<td align="right" class="main">
				<?=tag('=')?>tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(<?=$filename_href?>, tep_get_all_get_params(array('action','ID'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>
			</td>
		  </tr>
		 </table>
        </form>
       </td>
      </tr>
<?=tag('php')?>
  } else {
  //LIST TABLE VIEW
?>
      <tr>
        <td><?=tag('=')?>tep_draw_form('search', <?=$filename_href?>, '', 'get'); ?>
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?=tag('=')?>HEADING_TITLE; ?></td>
              <td class="pageHeading" align="right">&nbsp;</td>
              <td class="smallText" align="right">
				<?php
					foreach ($fields_list as $key => $val){
						if(strpos($val['Field'], '_status') !== false && strpos($val['Type'] ,'enum') === false){
				?>
							<?=tag('=')?>ENTRY_<?=strtoupper($val['Field'])?> . ' ' . tep_draw_pull_down_menu('<?=$val['Field']?>',array(array('id'=>'','text'=>SEARCH_SELECT_OPTION), array('id'=>'0','text'=>IMAGE_ICON_STATUS_RED), array('id'=>'1','text'=>IMAGE_ICON_STATUS_GREEN))); ?>&nbsp;&nbsp;
				<?php
						}elseif(strpos($val['Type'], 'enum') !== false){
							preg_match('/enum\((.*)\)/', $val['Type'], $arr);
							if($arr && isset($arr[1])){
								$arr_list = explode(',',str_replace('\'','',$arr[1]));
								$option_str = 'array(array("id"=>" ","text"=>SEARCH_SELECT_OPTION),';
								foreach ($arr_list as $_key => $_val){
									$option_str .='array("id"=>"'.$_val.'","text"=>"'.$_val.'"),';
								}
								$option_str = trim($option_str, ',');
								$option_str .= ')';
							}
				?>
							<?=tag('=')?>ENTRY_<?=strtoupper($val['Field'])?> . ' ' . tep_draw_pull_down_menu('<?=$val['Field']?>',<?=$option_str?>); ?>&nbsp;&nbsp;
				<?php
					
						}
					}
				?>
				<?=tag('=')?>HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?>
			  </td>
              <td class="smallText" align="right"><?=tag('=')?>tep_image_submit('button_search.gif', IMAGE_UPDATE); ?></td>
            </tr>
          </table>
        </form></td>
      </tr>
      <tr>

          <?=tag('php')."\n"?>
          $listing = (isset($_GET['listing']) ? $_GET['listing'] : '');
          switch ($listing) {
		  <?php
			$i = 0;
			foreach ($fields as $key => $val){
				if($i >= $list_show_num) break;
		  ?>	
              case '<?=$val?>':
				  $order = "<?=$val?>";
				  break;
              case '<?=$val?>-desc':
				  $order = "<?=$val?> DESC";
				  break;			 
		  <?php
				$i++;
			}
		  ?>
              default:
				  $order = "<?=$prime_key?> DESC";
          }
		  $listing_param = trim(tep_get_all_get_params(array('action','listing')),'&');

          ?>
        <td>
		 <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
			 <!-- list box -->
			  <table border="0" width="100%" cellspacing="0" cellpadding="2">
               <tr class="dataTableHeadingRow">
				<?php
				  $i = 0;
					foreach ($fields_list as $key => $val){
						$i++;
						if($i > $list_show_num) break;
						$_field = $val['Field'];
						$_heading_field = 'TABLE_HEADING_' . strtoupper($_field);
				?><td class="dataTableHeadingContent">
				  <a href="<?=tag('=')?>"$PHP_SELF?$listing_param&listing=<?=$_field?>"; ?>"><?=tag('=')?>tep_image_button('ic_up.gif', TEXT_SORT . <?=$_heading_field?> . TEXT_ABC); ?></a>&nbsp;
				  <a href="<?=tag('=')?>"$PHP_SELF?$listing_param&listing=<?=$_field?>-desc"; ?>"><?=tag('=')?>tep_image_button('ic_down.gif', TEXT_SORT . <?=$_heading_field?> . TEXT_ZYX); ?></a>
				  <br><?=tag('=')?><?=$_heading_field?>; ?>
				</td>
				<?php
					}
				?><td class="dataTableHeadingContent" align="right" valign="bottom"><?=tag('=')?>TABLE_HEADING_ACTION; ?>&nbsp;</td>
               </tr>

<?=tag('php')?>

	$<?=$prime_key?> = isset($_GET['ID']) ? $_GET['ID'] : '';
    //$where = 'where language_id = "' . $languages_id . '" and ';

    if ( isset($_GET['search']) && (tep_not_null($_GET['search'])) ) {
      $keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
      $where .= ' where (<?=$prime_key?> = "' . $keywords . '")';
    }
<?php
	foreach ($fields_list as $key => $val){
		if((strpos($val['Field'], '_status') !== false && strpos($val['Type'] ,'enum') === false) || strpos($val['Type'], 'enum') !== false){
?>
	if ( isset($_GET['<?=$val['Field']?>']) && (tep_not_null($_GET['<?=$val['Field']?>'])) ) {
	  $<?=$val['Field']?> = tep_db_input(tep_db_prepare_input($_GET['<?=$val['Field']?>']));
	  if(!isset($where)) $where = ' where 1 ';
	  $where .= ' and <?=$val['Field']?> = "' . $<?=$val['Field']?> . '"';
	}
<?php
		}
	}
?>

    $query_raw = "select * from " . TABLE_<?=strtoupper($classname)?> . $where . " order by " . $order;

	$page_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $query_raw, $query_numrows);
    $query = tep_db_query($query_raw);
    while ($info_arr = tep_db_fetch_array($query)) {

      if ( !isset($cInfo) && (!$<?=$prime_key?> || ($<?=$prime_key?> == $info_arr['<?=$prime_key?>']))) {
        $cInfo = new objectInfo($info_arr);
      }

      if (isset($cInfo) && (is_object($cInfo)) && ($info_arr['<?=$prime_key?>'] == $cInfo-><?=$prime_key?>) ) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(<?=$filename_href?>, tep_get_all_get_params(array('ID', 'action')) . 'ID=' . $cInfo-><?=$prime_key?> . '&amp;action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(<?=$filename_href?>, tep_get_all_get_params(array('ID')) . 'ID=' . $info_arr['<?=$prime_key?>']) . '\'">' . "\n";
      }
?>
				<?php
					$i = 0;
					foreach ($fields_list as $key => $val){
						if($i >= $list_show_num) break;
						if(strpos($val['Field'], '_status') !== false && strpos($val['Type'], 'enum') === false){
				?><td class="dataTableContent">
					<?=tag('php') . "\n"?>
						$setflag_url = '<a href="'.tep_href_link(<?=$filename_href?>, 'ID=' .$info_arr['<?=$prime_key?>'] .'&action=setflag&flag=%d') .'">%s</a>';
						$ico_action_yes = tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10); 
						$ico_action_no = tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10);
						$ico_yes = tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10);
						$ico_no = tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
						if($info_arr['<?=$val['Field']?>'] == 1){
							echo  $ico_yes.'&nbsp;&nbsp;'. sprintf($setflag_url, 0, $ico_action_no);
						}else{
							echo sprintf($setflag_url, 1, $ico_action_yes) .'&nbsp;&nbsp;'. $ico_no;
						}
					?>
				</td>
				<?php
						}else{

				?><td class="dataTableContent"><?=tag('=')?>$info_arr['<?=$val['Field']?>']; ?></td>
				<?php
						}
						$i++;
					}
				?>

				<td class="dataTableContent" align="right">
					<?=tag('php')?> 
						if ( isset($cInfo) && is_object($cInfo) && ($info_arr['<?=$prime_key?>'] == $cInfo-><?=$prime_key?>) ) {
							echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); 
						} else {
							echo '<a href="' . tep_href_link(<?=$filename_href?>, tep_get_all_get_params(array('ID')) . 'ID=' . $info_arr['<?=$prime_key?>']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
						} ?>&nbsp;
				</td>
              </tr>
<?=tag('php')?>
    }
?>
              <tr>
                <td colspan="5">
				<!-- page area-->
				 <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?=tag('=')?>$page_split->display_count($query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER); ?></td>
                    <td class="smallText" align="right"><?=tag('=')?>$page_split->display_links($query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'ID'))); ?></td>
                  </tr>
<?=tag('php')?>
    if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
?>
                  <tr>
                    <td align="right" colspan="2"><?=tag('=')?>'<a href="' . tep_href_link(<?=$filename_href?>) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                  </tr>
<?=tag('php')?>
    } else {
?>
                  <tr>
                    <td align="right" colspan="2" class="smallText"><?=tag('=')?>'<a href="' . tep_href_link(<?=$filename_href?>, 'page=' . $_GET['page'] . '&amp;action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
                  </tr>
<?=tag('php')?>
    }
?>
                 </table>
				<!-- page area end-->
				</td>
              </tr>
             </table>
			<!-- list box end-->
			</td>
<?=tag('php')?>
  //Operating area content
  $heading = array();
  $contents = array();
   switch ($action) {
    case 'confirm':
		//confirm delete operate
        if ($_GET['ID'] != '0') {
            $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'&nbsp;<br><b>' . TEXT_INFO_HEADING_DELETE . '</b>');
            $contents = array('form' => tep_draw_form('<?=$classname?>', <?=$filename_href?>, tep_get_all_get_params(array('ID', 'action')) . 'ID=' . $cInfo-><?=$prime_key?> . '&amp;action=deleteconfirm'));
            $contents[] = array('text' => '<br/>' . TEXT_DELETE_INTRO);
            $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(<?=$filename_href?>, tep_get_all_get_params(array('ID', 'action')) . 'ID=' . $cInfo-><?=$prime_key?>) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        } else {
            $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'&nbsp;<br><b>' . TEXT_INFO_HEADING_DELETE_GROUP . '</b>');
            $contents[] = array('text' => MESSAGE_DELETE_WARNING . '<br><br><b>' . $cInfo-><?=$prime_key?> . ' </b>');
        }
      break;
     default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'&nbsp;<br><b>' . $cInfo-><?=$prime_key?> . '</b>');
		$contents[] = array('align' => 'center', 'text' => '<br/>');
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(<?=$filename_href?>, tep_get_all_get_params(array('ID', 'action')) . 'ID=' . $cInfo-><?=$prime_key?> . '&amp;action=confirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>	<a href="' . tep_href_link(<?=$filename_href?>, tep_get_all_get_params(array('ID', 'action')) . 'ID=' . $cInfo-><?=$prime_key?> . '&amp;action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');

		<?php
			$start = $list_show_num - 1;
			$length = count($fields_list);
			for ($i=$start; $i<$length; $i++){
				$_field = $fields_list[$i]['Field'];
		?>$contents[] = array('align' => 'left', 'text' => ENTRY_<?=strtoupper($_field)?> . $cInfo-><?=$_field?>);
		<?php
			}
		?>
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?=tag('php')?>
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?=tag('php')?> require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?=tag('php')?> require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>