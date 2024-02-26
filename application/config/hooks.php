<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['post_controller_constructor'][] = array(
                                'class'    => 'Admin_auth',
                                'function' => 'index',
                                'filename' => 'Admin_auth.php',
                                'filepath' => 'modules/auth_panel/hooks'
                                );

$hook['post_controller_constructor'][] = array(
                                'class'    => 'Data_model_auth',
                                'function' => 'index',
                                'filename' => 'Data_model_auth.php',
                                'filepath' => 'modules/data_model/hooks'
                                );