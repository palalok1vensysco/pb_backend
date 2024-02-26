<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  if( !function_exists('say_hello') ) {

    function user_auth() {
      $auth_array = array();
      $auth_array['SUPER_USER'] = array('');
      $auth_array['HELP_DESK'] = array('');
      $auth_array['CONTENT_MANAGER'] = array('');
      $auth_array['CLAIM_HANDLER'] = array('');
      $auth_array['CUSTOMER_MANAGER'] = array('');
      $auth_array['PAYMENT_OPERATOR'] = array('');

    }

  }

?>
