<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include('application/config/config.ini.php');
/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
define('TIME_INTERVAL',5);
//local server
//leave it blank for live server
define('FOR_INTERNAL', "");


define("AMS_BUCKET_NAME", $config_aws_buckect_name);
define("AMS_S3_KEY", $config_aws_s3_key);
define("AMS_SECRET", $config_aws_secret_key);
define("AMS_REGION", $config_aws_s3_region);
define("AMS_BUCKET_BASE", 'https://' . AMS_BUCKET_NAME . '.s3.' . AMS_REGION . '.amazonaws.com/');

if (defined("PROJECT_MODE") && PROJECT_MODE == "prod")
    define("S3_CLOUDFRONT_DOMAIN", "vcott.s3.ap-south-1.amazonaws.com");
else
    define("S3_CLOUDFRONT_DOMAIN", "vcott.s3.ap-south-1.amazonaws.com");

define("CONFIG_REDIS_HOST", $config_redis_host);
define("CONFIG_REDIS_PASSWORD", $config_redis_password);
define("CONFIG_REDIS_PORT", $config_redis_port);

if (defined("PROJECT_MODE") && PROJECT_MODE == "prod")
    define("ELASTIC_SERVER_HOST", "");
else
    define("ELASTIC_SERVER_HOST", "");

/* android gsm key */
define("GSM_KEY", "AAAAeZpNDsY:APA91bHmT1e-ObeCeQdf5jahxD2cwwGuKaj-t41A05MauMwkvGBUOjdPmtIDYhiynKevd-j5fX8mMXuajrczXiKK6a8xTynrIJs0lu7F_f1-yFKE2Fi0Zm7d_3UiUBjMMIlIKkfz7BQr");
define("CONFIG_PROJECT_FULL_NAME", $config_project_full_name);
//define('CONFIG_PROJECT_NICK_NAME', $config_project_nick_name);

define("CONFIG_PROJECT_GLOBAL_NAME",$config_project_full_name);
define("CONFIG_PROJECT_GLOBAL_NICK_NAME",$config_project_nick_name);
define("CONFIG_PROJECT_SUBDOMAIN_NAME",'');
define("CONFIG_PROJECT_DIR_NAME",'mahua_tv');
/* support email */
define("SUPPORT_EMAIL", $config_support_email);


/* web socket ip */
define("WEB_SOCKET_IP", $config_web_socket_ip);

define("ADMIN_VERSION", "admin_v1");

//Pallycon credential (videocrypt)
define("PALLYCON_SITE_ID", "XIIU");
define("PALLYCON_ACCESS_KEY", "0Dya2LDBy2Kh8YqjD9pFEaZEZFD3Rygq");
define("PALLYCON_SITE_KEY", "KiMQ6hM1d3WHBREMn0xwoWnldj5JaWY7");
      


//Session  manager before framework load
// if (isset($_SERVER['HTTP_HOST']) && !strpos($_SERVER['HTTP_HOST'], "admin.videocrypt.in")) {
    $ds = DIRECTORY_SEPARATOR;
    define('LIBBATH', BASEPATH . "libraries{$ds}Session{$ds}");

    require_once LIBBATH . 'Session_driver.php';
    require_once LIBBATH . "drivers{$ds}Session_files_driver.php";
    require_once BASEPATH . "core{$ds}Common.php";
    $config = get_config();

    if (empty($config['sess_save_path'])) {
        $config['sess_save_path'] = rtrim(ini_get('session.save_path'), '/\\');
    }

    $config = array(
        'cookie_lifetime' => $config['sess_expiration'],
        'cookie_name' => $config['sess_cookie_name'],
        'cookie_path' => $config['cookie_path'],
        'cookie_domain' => $config['cookie_domain'],
        'cookie_secure' => $config['cookie_secure'],
        'expiration' => $config['sess_expiration'],
        'match_ip' => $config['sess_match_ip'],
        'save_path' => $config['sess_save_path'],
        '_sid_regexp' => '[0-9a-v]{32}',
    );

    $class = new CI_Session_files_driver($config);

    if (is_php('5.4')) {
        session_set_save_handler($class, TRUE);
    } else {
        session_set_save_handler(
                array($class, 'open'), array($class, 'close'), array($class, 'read'), array($class, 'write'), array($class, 'destroy'), array($class, 'gc')
        );
        register_shutdown_function('session_write_close');
    }
    session_name($config['cookie_name']);
    session_start(); 
    //echo "<pre>"; print_r($_SESSION);
    if (isset($_SESSION['temp_app_id']) && $_SESSION['temp_app_id']!='') {
        defined("APP_ID") OR define("APP_ID", $_SESSION['temp_app_id']);
    } else if (isset($_SESSION['active_user_data']))
        defined('APP_ID') OR define('APP_ID', $_SESSION['active_user_data']->app_id ?? "0");
      //  print_r( $_SESSION['active_user_data']->app_id); die;

    session_abort();
 //}