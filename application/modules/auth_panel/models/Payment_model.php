<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment_model extends CI_Model {

    protected $RZP_KEY_ID;
    protected $RZP_SECRET_KEY;
    protected $RZP_MODE;

    function __construct() {
        parent::__construct();
        $this->load->helper("aes");
        $this->retrieve_rzp();
    }

    private function retrieve_rzp() {
        $rzp = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "RZP_DETAIL"), ""), true);
        if ($rzp) {
            $this->RZP_KEY_ID = $rzp['key'];
            $this->RZP_SECRET_KEY = $rzp['secret'];
            $this->RZP_MODE = $rzp['mode'];
        }
    }

    function retrieve_gateway_name($pay_via) {
        $return = "UNDEFINED";
        switch ($pay_via) {
            case 1:
                $return = "PAY_U_MONEY";
                break;
            case 2:
                $return = "PAY_U_BIZ";
                break;
            case 3:
                $return = "RAZOR_PAY";
                break;
            case 4:
                $return = "CASH_FREE";
                break;
        }
        return $return;
    }

    public function track_rzp_order_id($txn_id) {
        $response = array();

        if (strpos($txn_id, "pay_") != "") 
        {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.razorpay.com/v1/orders/" . $txn_id . "/payments",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic " . base64_encode($this->RZP_KEY_ID . ':' . $this->RZP_SECRET_KEY),
                ),
            ));

            $response = curl_exec($curl);
            $response = json_decode($response, true);
            curl_close($curl);
        } 
        else 
        {
            $response = $this->track_rzp_txn_id($txn_id);

            if (isset($response['status'])) 
            {
                if ($this->input->post("type") == "Primary") 
                {
                    $this->db->where("post_transaction_id", $txn_id);
                    $this->db->order_by("transaction_status","asc");
                    $report = $this->db->get("course_transaction_record")->row_array();
                } 
                else 
                {
                    $this->db->where("comp_txn_id", $txn_id);
                    $this->db->order_by("txn_status","asc");
                    $report = $this->db->get("course_transaction_record_extender")->row_array();
                }

                $message = "";
                
                switch ($response['status']) 
                {
                    case "captured":

                        if ($this->input->post("type") == "Primary") 
                        {
                            if ($report['transaction_status'] != 1) 
                            {
                                $invoice_no = 0;
                                if (!$report['invoice_no']) 
                                {
                                    $this->load->model("Payment_model");
                                    $invoice_no = $this->Payment_model->get_invoice_no();
                                }
                                
                                $this->db->where("id", $report['id']);
                                $this->db->set("transaction_status", 1);
                                $this->db->set("delivery_json", json_encode(array("added_by" => "track")));
                                if ($invoice_no)
                                    $this->db->set("invoice_no", $invoice_no);
                                $this->db->update("course_transaction_record");

                                $message = "Your Transaction Has Been Completed with Transaction ID:" . $response['id'] . ". You can access content from My Library.";
                            }
                        } 
                        else 
                        {
                            if ($report['txn_status'] != 1) 
                            {
                                $invoice_no = 0;
                                if (!$report['e_invoice_no']) 
                                {
                                    $this->load->model("Payment_model");
                                    $invoice_no = $this->Payment_model->get_invoice_no();
                                }

                                $this->db->where("id", $report['id']);
                                $this->db->set("txn_status", 1);
                                $this->db->set("additional_meta", json_encode(array("added_by" => "track")));
                                if ($invoice_no)
                                    $this->db->set("e_invoice_no", $invoice_no);
                                $this->db->update("course_transaction_record_extender");

                                $this->db->where("id", $report['txn_id']);
                                $this->db->set("extend_seconds", "extend_seconds+" . $report['extended_sec'], false);
                                $this->db->update("course_transaction_record");

                                $message = "Your Transaction Has Been Completed with Transaction ID:" . $response['id'] . " And validity extended. You can access content from My Library.";
                            }
                        }
                        break;
                    case "refunded":
                        if ($this->input->post("type") == "Primary") {
                            if ($report['transaction_status'] == 1) {
                                $json = $report['delivery_json'] ? $report['delivery_json'] : array();
                                $json['refunded_by'] = "hook";

                                $this->db->where("id", $report['id']);
                                $this->db->set("transaction_status", 4);
                                $this->db->set("refund_id", $response['id']);
                                $this->db->set("delivery_json", json_encode($json));
                                $this->db->update("course_transaction_record");

                                $message = "Your Transaction Has Been Refunded with Transaction ID:" . $response['id'] . ". If any payment deducted from your account refunded will be in 5 to 7 bussiness days.";
                            }
                        } else {
                            if ($report['txn_status'] != 1) {
                                $json = $report['additional_meta'] ? $report['additional_meta'] : array();
                                $json['refunded_by'] = "hook";
                                
                                $this->db->where("id", $report['id']);
                                $this->db->set("txn_status", 4);
                                $this->db->set("additional_meta", json_encode($json));
                                $this->db->update("course_transaction_record_extender");

                                $this->db->where("id", $report['txn_id']);
                                $this->db->set("extend_seconds", "extend_seconds-" . $report['extended_sec'], false);
                                $this->db->update("course_transaction_record");

                                $message = "Your Transaction Has Been Completed with Transaction ID:" . $response['id'] . " And validity extended. You can access content from My Library.";
                            }
                        }
                        break;
                    default:
                        break;
                }

                if ($message) {
                    $this->redis_magic = new Redis_magic("data");
                    $this->redis_magic->DEL("my_courses:" . $report["user_id"]);
                    update_api_version($this->db, 12, 0, $report['user_id']);

                    /*
                     * Generate Push
                     */
                    $user = $this->db->query("select id,device_token,name,email,device_type,mobile from users where id =" . $report['user_id'])->row_array();

                    $push_data = json_encode(
                            array(
                                'notification_code' => 90001,
                                'message' => $message,
                                'data' => array("message_target" => '')
                            )
                    );
                    if ($user['device_token'] && $user['device_type'] < 3) {
                        $this->load->helper("push");
                        generatePush($user['device_type'], $user['device_token'], $push_data);
                    }
                }
            }
        }

        return $response;
    }

    public function track_rzp_txn_id($txn_id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.razorpay.com/v1/payments/" . $txn_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic " . base64_encode($this->RZP_KEY_ID . ':' . $this->RZP_SECRET_KEY),
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        curl_close($curl);
        return $response;
    }

    function rzp_capture_payment($txn_dtl) {
        $post_params = array(
            "amount" => round($txn_dtl['total_price'] * 100),
            "currency" => "INR",
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.razorpay.com/v1/payments/" . $txn_dtl["post_transaction_id"] . "/capture");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_MAXREDIRS, "10");
        curl_setopt($ch, CURLOPT_TIMEOUT, "10");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic " . base64_encode($this->RZP_KEY_ID . ':' . $this->RZP_SECRET_KEY),
            "Content-Type: application/json"
        ));
        $response = curl_exec($ch);
        $response = json_decode($response, true);

        curl_close($ch);
    }

    function fetch_rxp_payments($from, $to, $status, $count = 25, $skip = 0) {
        /*
         * $from: start from timestamp
         * $to: end to timestamp
         * $satus: authorised/paid,captured etc..
         */
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.razorpay.com/v1/payments?skip=$skip&count=$count&from=$from&to=$to");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_USERPWD, $this->RZP_KEY_ID . ':' . $this->RZP_SECRET_KEY);

        $response = curl_exec($ch);
        $response = json_decode($response, true);
        curl_close($ch);
        return $response;
    }

}
