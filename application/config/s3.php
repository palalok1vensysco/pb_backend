<?php defined('BASEPATH') OR exit('No direct script access allowed');



$config['use_ssl'] = TRUE;

$config['verify_peer'] = TRUE;

$config['access_key'] = AMS_S3_KEY;

$config['secret_key'] = AMS_SECRET;

$config['bucket_name'] = AMS_BUCKET_NAME;

$config['s3_url'] = AMS_BUCKET_NAME;

$config['get_from_enviroment'] = FALSE;

$config['access_key_envname'] = 'S3_KEY';

$config['secret_key_envname'] = 'S3_SECRET';

if ($config['get_from_enviroment']){
	$config['access_key'] = getenv($config['access_key_envname']);
	$config['secret_key'] = getenv($config['secret_key_envname']);

}