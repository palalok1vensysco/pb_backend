<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Inputs_model extends CI_Model {

    function __construct() {
        parent::__construct();
        define("SECURITY_GROUP_ID", '6773836');
    }

    private function create_input_aws($input) {
        $client = new Aws\MediaLive\MediaLiveClient($this->Credentials_model->get_credentials());
        return (array) $client->createInput([
                    'Destinations' => [
                        [
                            'StreamName' => $input['destination_a_name'] . '/' . $input['destination_a_key'],
                        ],
                        [
                            'StreamName' => $input['destination_b_name'] . '/' . $input['destination_b_key'],
                        ]
                    ],
                    'InputSecurityGroups' => [$input['security_group_id']],
                    'MediaConnectFlows' => [],
                    'Name' => $input['name'],
                    'Type' => $input['type'],
        ]);
    }

    private function update_input_aws($input, $input_id) {
        $client = new Aws\MediaLive\MediaLiveClient($this->Credentials_model->get_credentials());
        return (array) $client->updateInput([
                    'InputId' => $input_id,
                    'Destinations' => [
                        [
                            'StreamName' => $input['destination_a_name'] . '/' . $input['destination_a_key'],
                        ],
                        [
                            'StreamName' => $input['destination_b_name'] . '/' . $input['destination_b_key'],
                        ]
                    ]
        ]);
    }

    public function index($input) {
        $data = $this->Credentials_model->refine_array($this->create_input_aws($input));
        $aws_input = $data["Aws\Resultdata"]["Input"];

        $insert = array(
            'security_group_id' => $aws_input['SecurityGroups'][0],
            'input_id' => $aws_input['Id'],
            'name' => $aws_input['Name'],
            'name' => $aws_input['Name'],
            'state' => $aws_input['State'],
            'type' => $aws_input['Type'],
            'destination_a_name' => $input['destination_a_name'],
            'destination_a_key' => $input['destination_a_key'],
            'destination_b_name' => $input['destination_b_name'],
            'destination_b_key' => $input['destination_b_key'],
            'ip_a' => $aws_input['Destinations'][0]['Ip'],
            'ip_b' => $aws_input['Destinations'][1]['Ip'],
            'port_a' => $aws_input['Destinations'][0]['Port'],
            'port_b' => $aws_input['Destinations'][1]['Port'],
            'arn' => $aws_input['Arn'],
            'remark' => $input['remark'],
            'json' => json_encode($data),
            'created_by' => $this->session->userdata('active_backend_user_id'),
            'created' => time(),
            'app_id' => (defined("APP_ID") ? "" . APP_ID . "" : "0")
        );

        $this->db->insert('aws_channel_input', $insert);
    }

    function get_inputs() {
        if (defined("APP_ID"))  
        $this->db->where("app_id", APP_ID);
        return $this->db->get('aws_channel_input')->result_array();
    }

    function delete_input($id, $input_id) {
        $client = new Aws\MediaLive\MediaLiveClient($this->Credentials_model->get_credentials());
        $result = $client->deleteInput([
            'InputId' => $input_id, // REQUIRED
        ]);
        $this->db->where('id', $id);
        $this->db->delete('aws_channel_input');
        redirect($_SERVER['HTTP_REFERER']);
    }

    function update_input() {
        $input = $this->input->post();
        $update = array(
            "destination_a_name" => $input['destination_a_name'],
            "destination_a_key" => $input['destination_a_key'],
            "destination_b_name" => $input['destination_b_name'],
            "destination_b_key" => $input['destination_b_key']
        );
        $this->update_input_aws($update, $input['input_id']);

        $this->db->where('id', $input['id']);
        $this->db->update('aws_channel_input', $update);
    }

}
