<!-- ------------------js_valid--------------------------->
		<?php 
			foreach ($languages_list as $key => $val)
			{
				echo '$("#name_'.$val['id'].'").require("'.$val['name'].' name is required.").minLength(2);';
			}
		?>
<!-- ------------------edit input--------------------------->

            <td class="main">
				<?php 
					foreach ($languages_list as $key => $val){
						echo tep_draw_input_field('name['. $val['id'] .']', $level_name[$val['id']], 'id="name_'. $val['id'] .'" maxlength="32"', true); 
						echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $val['directory'] . '/images/' . $val['image'], $val['name']);
						echo '<br/>';
					}
				?>
			</td>

<?php
//--------------------edit view---------------------------
	
	$title = $rule_model->get_title_languages($cInfo->rule_id, $languages_list);


//--------------------do_insert---------------------------
		$id = tep_db_insert_id();
		foreach ($title as $key => $val){
			$data = array(
					'rule_id' => $id,
					'language_id' => $key,
					'rule_title' => $val
				);
			tep_db_perform(TABLE_MEMBERSHIP_POINT_RULE_DESCRIPTION, $data);
		}
		return $id;

//--------------------do_update---------------------------

		foreach ($title as $key => $val){
			$data = array(
					'rule_id' => $this->_id,
					'language_id' => $key,
					'rule_title' => $val
				);
			tep_db_query('insert into '. TABLE_MEMBERSHIP_POINT_RULE_DESCRIPTION . ' (rule_id, language_id, rule_title) values ("'.join('","', $data).'") on duplicate key update rule_id = values(rule_id), language_id = values(language_id), rule_title = values(rule_title)');
		}


	/**
	* 
	* @authoer nathan 
	* @access public 
	* @param 
	* @return 
	*/
	function get_title_languages($id, $languages_list){
		$arr = array();
		foreach ($languages_list as $key => $val){
			$sql = 'select rule_title from '. TABLE_MEMBERSHIP_POINT_RULE_DESCRIPTION.' where '.$this->_key.'="'. $id .'" and language_id ="'. $val['id'] .'"';
			$_info = tep_db_fetch_array(tep_db_query($sql));
			$arr[$val['id']] = '';
			if($_info) $arr[$val['id']] = $_info['rule_title'];
		}
		return $arr;
	}
?>
