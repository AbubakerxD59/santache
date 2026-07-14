<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shipping_settings extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model('Setting_model');

        if (!has_permissions('read', 'shipping_settings')) {
            $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
            redirect('admin/home', 'refresh');
        }
    }


    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'shipping-settings';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Shipping Methods Management | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Shipping Methods Management  | ' . $settings['app_name'];
            $this->data['settings'] = get_settings('shipping_method', true);
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function update_shipping_settings()
    {

        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (print_msg(!has_permissions('update', 'shipping_settings'), PERMISSION_ERROR_MSG, 'shipping_settings')) {
                return false;
            }

            $_POST['temp'] = '1';
            $this->form_validation->set_rules('temp', '', 'trim|required|xss_clean');

            $shiprocket_shipping_method = $this->input->post('shiprocket_shipping_method', true);
            $local_shipping_method = $this->input->post('local_shipping_method', true);
            $usps_shipping_method = $this->input->post('usps_shipping_method', true);
            if (!isset($shiprocket_shipping_method) && !isset($local_shipping_method) && !isset($usps_shipping_method)) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = 'Please select atleast one shipping method';
                print_r(json_encode($this->response));
                return false;
            }

            $shipping_method = $this->input->post('shiprocket_shipping_method', true);
            if (isset($shipping_method) && $shipping_method == "on") {
                $this->form_validation->set_rules('email', ' Email ', 'trim|required|xss_clean');
                $this->form_validation->set_rules('password', ' Password ', 'trim|required|xss_clean');
                $this->form_validation->set_rules('webhook_token', ' Token ', 'trim|xss_clean');
            }

            if (isset($usps_shipping_method) && $usps_shipping_method == "on") {
                $this->form_validation->set_rules('usps_consumer_key', 'USPS Consumer Key', 'trim|required|xss_clean');
                $this->form_validation->set_rules('usps_consumer_secret', 'USPS Consumer Secret', 'trim|required|xss_clean');
                $this->form_validation->set_rules('usps_origin_zip', 'USPS Origin ZIP', 'trim|required|xss_clean');
                $this->form_validation->set_rules('usps_environment', 'USPS Environment', 'trim|xss_clean');
            }

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {

                $data = array(
                    'local_shipping_method' => $this->input->post('local_shipping_method', true),
                    'shiprocket_shipping_method' => $this->input->post('shiprocket_shipping_method', true),
                    'email' => $this->input->post('email', true),
                    'password' => $this->input->post('password', true),
                    'webhook_token' => $this->input->post('webhook_token', true),
                    'usps_shipping_method' => $this->input->post('usps_shipping_method', true),
                    'usps_consumer_key' => $this->input->post('usps_consumer_key', true),
                    'usps_consumer_secret' => $this->input->post('usps_consumer_secret', true),
                    'usps_origin_zip' => $this->input->post('usps_origin_zip', true),
                    'usps_environment' => $this->input->post('usps_environment', true),
                    'usps_crid' => $this->input->post('usps_crid', true),
                    'usps_mid' => $this->input->post('usps_mid', true),
                    'usps_manifest_mid' => $this->input->post('usps_manifest_mid', true),
                    'usps_account_number' => $this->input->post('usps_account_number', true),
                    'usps_from_first_name' => $this->input->post('usps_from_first_name', true),
                    'usps_from_last_name' => $this->input->post('usps_from_last_name', true),
                    'usps_from_street' => $this->input->post('usps_from_street', true),
                    'usps_from_city' => $this->input->post('usps_from_city', true),
                    'usps_from_state' => $this->input->post('usps_from_state', true),
                    'usps_from_phone' => $this->input->post('usps_from_phone', true),
                    'temp' => $this->input->post('temp', true),
                );

                $this->Setting_model->update_shipping_method($data);
                $this->response['error'] = false;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = 'Shipping Setting Updated Successfully';
                print_r(json_encode($this->response));
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
