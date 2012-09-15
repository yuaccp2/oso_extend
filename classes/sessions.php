<?php
/*
  $Id: sessions.php,v 1.1.1.1 2004/03/04 23:40:51 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (STORE_SESSIONS == 'mysql') {
    if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
      $SESS_LIFE = 1440;
    }

    function _sess_open($save_path, $session_name) {
      return true;
    }

    function _sess_close() {
      return true;
    }

    function _sess_read($key) {
      $value_query = tep_db_query("select value from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "' and expiry > '" . time() . "'");
      $value = tep_db_fetch_array($value_query);

      if (isset($value['value'])) {
        return $value['value'];
      }

      return false;
    }

    function _sess_write($key, $val) {
      global $SESS_LIFE;

      $expiry = time() + $SESS_LIFE;
      $value = $val;

      $check_query = tep_db_query("select count(*) as total from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
      $check = tep_db_fetch_array($check_query);

      if ($check['total'] > 0) {
        return tep_db_query("update " . TABLE_SESSIONS . " set expiry = '" . tep_db_input($expiry) . "', value = '" . tep_db_input($value) . "' where sesskey = '" . tep_db_input($key) . "'");
      } else {
        return tep_db_query("insert into " . TABLE_SESSIONS . " values ('" . tep_db_input($key) . "', '" . tep_db_input($expiry) . "', '" . tep_db_input($value) . "')");
      }
    }

    function _sess_destroy($key) {
      return tep_db_query("delete from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
    }

    function _sess_gc($maxlifetime) {
      tep_db_query("delete from " . TABLE_SESSIONS . " where expiry < '" . time() . "'");

      return true;
    }

    session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
  }

  function tep_session_start() {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
    
    $session_id = '';
    $session_name = tep_session_name();
    
    if (isset($_GET[$session_name])) {
      if (preg_match('/^[a-zA-Z0-9]+$/', $_GET[$session_name]) == false) {
        unset($_GET[$session_name]);
        unset($HTTP_GET_VARS[$session_name]);
      } else {
        $session_id = $_GET[$session_name];
      }
      
    } elseif (isset($_POST[$session_name])) {
      if (preg_match('/^[a-zA-Z0-9]+$/', $_POST[$session_name]) == false) {
        unset($_POST[$session_name]);
        unset($HTTP_POST_VARS[$session_name]);
      } else {
        $session_id = $_POST[$session_name];
      }
      
    } elseif (isset($_COOKIE[$session_name])) {
      if (preg_match('/^[a-zA-Z0-9]+$/', $_COOKIE[$session_name]) == false) {
        $session_data = session_get_cookie_params();
        setcookie($session_name, '', time()-42000, $session_data['path'], $session_data['domain']);
      } else {
        $session_id = $_COOKIE[$session_name];
      }
    }
    
    if (tep_not_null($session_id)) {
      tep_session_id($session_id);
    }
    
    return session_start();
  }

  function tep_session_register($variable) {
    global $session_started;

    if ($session_started == true) {
      return session_register($variable);
    } else {
      return false;
    }
  }

  function tep_session_is_registered($variable) {
    return session_is_registered($variable);
  }

  function tep_session_unregister($variable) {
    return session_unregister($variable);
  }

  function tep_session_id($sessid = '') {
    if (!empty($sessid)) {
      return session_id($sessid);
    } else {
      return session_id();
    }
  }

  function tep_session_name($name = '') {
    if (!empty($name)) {
      return session_name($name);
    } else {
      return session_name();
    }
  }

  function tep_session_close() {
    return session_write_close();
  }

  function tep_session_destroy() {
    return session_destroy();
  }

  function tep_session_save_path($path = '') {
    if (!empty($path)) {
      return session_save_path($path);
    } else {
      return session_save_path();
    }
  }

  function tep_session_recreate() {
    $session_backup = $_SESSION;

    unset($_COOKIE[tep_session_name()]);

    tep_session_destroy();

    if (STORE_SESSIONS == 'mysql') {
      session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
    }

    tep_session_start();

    $_SESSION = $session_backup;
    unset($session_backup);
  }

?>
