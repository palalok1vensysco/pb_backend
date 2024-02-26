<?php

function get_db_meta_key($db, $key) {
    if ($key == "") {
        return "";
    } else {        
        $query = "SELECT meta_value
                    FROM meta_information 
                    WHERE meta_name = '$key'";                        
        if ($r = $db->query($query)->row()) {
            return $r->meta_value;
        } else {
            return "";
        }
    }
}



if(!defined("get_language")){
    function get_language(){
        $APP_ID = "lang_id = " . (defined("LANG_ID") ?  LANG_ID : 1);
        return $APP_ID;
    }
}
function set_db_meta_key($db, $key, $value) {
    if ($key == "") {
        return false;
    } else {

        $query = "SELECT meta_value
        FROM meta_information 
        WHERE meta_name = '$key'";
             
        if ($db->query($query)->row()) {
            $data = array("meta_value" => $value);
            $db->where(array('meta_name' => $key));
            $db->update('meta_information', $data);
        } else {
            $data = array("meta_value" => $value, "meta_name" => $key);
            $db->insert('meta_information', $data);
        }
    }
}
