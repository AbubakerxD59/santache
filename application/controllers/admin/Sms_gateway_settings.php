<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sms_gateway_settings extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper', 'sms_helper']);
        $this->load->model(['Setting_model', 'notification_model', 'category_model', 'custom_sms_model']);
        if (!has_permissions('read', 'sms_gateway_settings')) {
            $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
            redirect('admin/home', 'refresh');
        }
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (!has_permissions('read', 'sms-gateway-settings')) {
                $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
                redirect('admin/home', 'refresh');
            }
            $this->data['main_page'] = FORMS . 'sms-gateway-settings';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'SMS Gateway Settings | ' . $settings['app_name'];
            $this->data['meta_description'] = ' SMS Gateway Settings  | ' . $settings['app_name'];
            $this->data['sms_gateway_settings'] = get_settings('sms_gateway_settings', true);
            $this->data['send_notification_settings'] = get_settings('send_notification_settings', true);
            $this->data['notification_modules'] = $this->config->item('notification_modules');
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                $this->data['fetched_data'] = fetch_details('custom_sms', ['id' => $_GET['edit_id']]);
            }
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function add_sms_data()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (!has_permissions('read', 'sms-gateway-settings')) {
                $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
                redirect('admin/home', 'refresh');
            }
            if (print_msg(!has_permissions('update', 'sms-gateway-settings'), PERMISSION_ERROR_MSG, 'sms-gateway-settings')) {
                return false;
            }

            $this->load->library('form_validation');
            $this->form_validation->set_rules('base_url', 'Base URL', 'required|valid_url');
            $this->form_validation->set_rules('sms_gateway_method', 'Gateway Method', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
            } else {
                $post_data = $this->input->post();

                if (isset($post_data['header_key']) || isset($post_data['header_value'])) {
                    if (!$this->validate_paired_arrays($post_data['header_key'] ?? [], $post_data['header_value'] ?? [])) {
                        $this->response['error'] = true;
                        $this->response['message'] = 'All header keys and values must be filled. Empty pairs are not allowed.';
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        echo json_encode($this->response);
                        return;
                    }
                }

                if (isset($post_data['params_key']) || isset($post_data['params_value'])) {
                    if (!$this->validate_paired_arrays($post_data['params_key'] ?? [], $post_data['params_value'] ?? [])) {
                        $this->response['error'] = true;
                        $this->response['message'] = 'All params keys and values must be filled. Empty pairs are not allowed.';
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        echo json_encode($this->response);
                        return;
                    }
                }

                if (isset($post_data['body_key']) || isset($post_data['body_value'])) {
                    if (!$this->validate_paired_arrays($post_data['body_key'] ?? [], $post_data['body_value'] ?? [])) {
                        $this->response['error'] = true;
                        $this->response['message'] = 'All body keys and values must be filled. Empty pairs are not allowed.';
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        echo json_encode($this->response);
                        return;
                    }
                }

                $array_fields = ['header_key', 'header_value', 'params_key', 'params_value', 'body_key', 'body_value'];

                foreach ($array_fields as $field) {
                    if (isset($post_data[$field]) && is_array($post_data[$field])) {
                        $post_data[$field] = array_filter($post_data[$field], function ($item) {
                            return !empty(trim($item));
                        });
                    }
                }

                $this->Setting_model->update_smsgateway($post_data);
                $this->response['error'] = false;
                $this->response['message'] = 'custom sms setting Updated Successfully';
            }

            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($this->response);
        }
    }

    private function validate_paired_arrays($keys, $values)
    {
        if (!is_array($keys) || !is_array($values)) {
            return true;
        }

        $max_length = max(count($keys), count($values));

        if ($max_length === 0) {
            return true;
        }

        for ($i = 0; $i < $max_length; $i++) {
            $key = isset($keys[$i]) ? trim($keys[$i]) : '';
            $value = isset($values[$i]) ? trim($values[$i]) : '';

            if (isset($keys[$i]) || isset($values[$i])) {
                if (empty($key) || empty($value)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function update_notification_module()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (!has_permissions('read', 'sms-gateway-settings')) {
                $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
                redirect('admin/home', 'refresh');
            }

            $this->Setting_model->update_notification_setting($_POST);
            $this->response['error'] = false;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = (isset($edit_id)) ? ' Data Updated Successfully' : 'Data Added Successfully';

            print_r(json_encode($this->response));
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
