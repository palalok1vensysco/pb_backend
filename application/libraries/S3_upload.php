<?php

/**
 * Amazon S3 Upload PHP class
 *
 * @version 0.1
 */
class S3_upload {

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->library('s3');

        $this->CI->config->load('s3', TRUE);
        $s3_config = $this->CI->config->item('s3');
        $this->bucket_name = $s3_config['bucket_name'];
        $this->s3_url = $s3_config['s3_url'];
    }

    function upload_file($file_path, $folder) {
        $s3_config = $this->CI->config->item('s3');
        $this->folder_name = $folder;
        // generate unique filename
        $file = pathinfo($file_path);
        $s3_file = time() . '.' . $file['extension'];
        $mime_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file_path);

        $saved = $this->CI->s3->putObjectFile(
                $file_path,
                $this->bucket_name,
                $this->folder_name . $s3_file,
                S3::ACL_PUBLIC_READ,
                array(),
                $mime_type
        );
        if ($saved) {
            return $s3_file;
        }
    }

    function upload_s3_file($file_path, $folder) {
        $s3_config = $this->CI->config->item('s3');
        $this->folder_name = $folder;
        //generate unique filename
        $file = pathinfo($file_path);
        $s3_file = time() . '.' . $file['extension'];
        $mime_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file_path);
        //pre($this); die;
        $saved = $this->CI->s3->putObjectFile(
                $file_path,
                $this->bucket_name,
                $this->folder_name . $s3_file,
                S3::ACL_PUBLIC_READ,
                array(),
                $mime_type
        );
        if ($saved) {
            return S3_BUCKET_URL_NEW . $this->folder_name . $s3_file;
        }
    }

    function upload_s3($file_path, $folder, $dynamic_name = false) {
        $s3_config = $this->CI->config->item('s3');
        $this->folder_name = $folder;
        //generate unique filename
        $file = explode("/", $file_path);

        $s3_file = end($file);
        if ($dynamic_name)
            $s3_file = $dynamic_name . "_" . $s3_file;
        $mime_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file_path);
        //pre($this); die;
        $saved = $this->CI->s3->putObjectFile(
                $file_path,
                $this->bucket_name,
                $this->folder_name . $s3_file,
                S3::ACL_PUBLIC_READ,
                array(),
                $mime_type
        );
        if ($saved) {
            return AMS_BUCKET_BASE . $this->folder_name . $s3_file;
        }
    }

}
