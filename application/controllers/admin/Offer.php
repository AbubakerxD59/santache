<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Offer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model(['Offer_model', 'category_model']);

        if (!has_permissions('read', 'offer')) {
            $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
            redirect('admin/home', 'refresh');
        }
    }

    public  function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'offers';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) ? 'Edit Offer Image | ' . $settings['app_name'] : 'Add Offer Images | ' . $settings['app_name'];
            $this->data['meta_description'] = ' Add Offer Images  | ' . $settings['app_name'];
            $this->data['categories'] = $this->category_model->get_categories();
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                $this->data['fetched_data'] = fetch_details('offers', ['id' => $_GET['edit_id']]);
            }
            $this->data['about_us'] = get_settings('about_us');
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public  function manage_offer()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'manage-offers';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Offer Images Management | ' . $settings['app_name'];
            $this->data['meta_description'] = ' Offer Images Management  | ' . $settings['app_name'];
            $this->data['about_us'] = get_settings('about_us');
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function add_offer()
    {
        $edit_offer = $this->input->post('edit_offer', true);
        if (isset($edit_offer)) {
            if (print_msg(!has_permissions('update', 'offer'), PERMISSION_ERROR_MSG, 'offer')) {
                return false;
            }
        } else {
            if (print_msg(!has_permissions('create', 'offer'), PERMISSION_ERROR_MSG, 'offer')) {
                return false;
            }
        }

        $this->form_validation->set_rules('offer_url', 'Offer Url', 'trim|xss_clean|valid_url');
        $this->form_validation->set_rules('offer_type', 'Offer Type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('image', 'Offer Image', 'trim|required|xss_clean', array('required' => 'Offer image is required'));
        $this->form_validation->set_rules('main_offer_type', 'Main Offer Type', 'trim|required|xss_clean', array('required' => 'Main Offer Type is required'));
        
        $main_offer_type = $this->input->post('main_offer_type', true);
        if (!empty($main_offer_type)) {
            $this->form_validation->set_rules('main_offer_type', 'Offer Type Selection', 'trim|required|xss_clean|in_list[offer_slider,popup_offer]', array('in_list' => 'Invalid offer type selected. Please select either Offer Slider or Popup Offer.'));
        }

        $offer_type = $this->input->post('offer_type', true);
        if (isset($offer_type) && $offer_type == 'categories') {
            $this->form_validation->set_rules('category_id', 'Category', 'trim|required|xss_clean', array('required' => 'Please select a category'));
            $this->form_validation->set_rules('min_discount', 'Min Discount ', 'trim|required|xss_clean|numeric|greater_than_equal_to[0]', array('numeric' => 'Min discount must be a number'));
            $this->form_validation->set_rules('max_discount', 'Max Discount', 'trim|required|xss_clean|numeric|callback_check_discounts', array('numeric' => 'Max discount must be a number'));
        } else if (isset($offer_type) && $offer_type == 'all_products') {
            $this->form_validation->set_rules('min_discount', 'Min Discount ', 'trim|required|xss_clean|numeric|greater_than_equal_to[0]', array('numeric' => 'Min discount must be a number'));
            $this->form_validation->set_rules('max_discount', 'Max Discount', 'trim|required|xss_clean|numeric|callback_check_discounts', array('numeric' => 'Max discount must be a number'));
        } else if (isset($offer_type) && $offer_type == 'brand') {
            $this->form_validation->set_rules('brand_id', 'Brand', 'trim|required|xss_clean', array('required' => 'Please select a brand'));
            $this->form_validation->set_rules('min_discount', 'Min Discount ', 'trim|required|xss_clean|numeric|greater_than_equal_to[0]', array('numeric' => 'Min discount must be a number'));
            $this->form_validation->set_rules('max_discount', 'Max Discount', 'trim|required|xss_clean|numeric|callback_check_discounts', array('numeric' => 'Max discount must be a number'));
        } else if (isset($offer_type) && $offer_type == 'products') {
            $this->form_validation->set_rules('product_id', 'Product', 'trim|required|xss_clean', array('required' => 'Please select a product'));
        } else if (isset($offer_type) && $offer_type == 'offer_url') {
            $this->form_validation->set_rules('link', 'Link', 'trim|required|xss_clean|valid_url', array('required' => 'Please enter a valid URL'));
        }
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = validation_errors();
            print_r(json_encode($this->response));
        } else {
            $main_offer_type = $this->input->post('main_offer_type', true);
            if (isset($main_offer_type) && $main_offer_type == 'popup_offer') {
                $offer_data = array(
                    'offer_type' => $this->input->post('offer_type', true),
                    'category_id' => $this->input->post('category_id', true),
                    'brand_id' => $this->input->post('brand_id', true),
                    'product_id' => $this->input->post('product_id', true),
                    'link' => $this->input->post('link', true),
                    'min_discount' => $this->input->post('min_discount', true),
                    'max_discount' => $this->input->post('max_discount', true),
                    'popup_offer_status' => $this->input->post('popup_offer_status', true),
                    'image' => $this->input->post('image', true),
                );
                $this->Offer_model->add_popup_offer($offer_data);
            } else {
                $data = array(
                    'offer_type' => $this->input->post('offer_type', true),
                    'category_id' => $this->input->post('category_id', true),
                    'brand_id' => $this->input->post('brand_id', true),
                    'product_id' => $this->input->post('product_id', true),
                    'link' => $this->input->post('link', true),
                    'min_discount' => $this->input->post('min_discount', true),
                    'max_discount' => $this->input->post('max_discount', true),
                    'image' => $this->input->post('image', true),
                );

                $edit_offer = $this->input->post('edit_offer', true);
                if (! empty($edit_offer)) {
                    $data['edit_offer'] = $this->input->post('edit_offer', true);
                }
                $this->Offer_model->add_offer($data);
            }

            $this->response['error'] = false;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $message = (null !== $this->input->post('edit_offer', true)) ? 'Offer Updated Successfully' : 'Offer Added Successfully';
            $this->response['message'] = $message;
            print_r(json_encode($this->response));
        }
    }
    public function check_discounts($max_discount)
    {
        $min_discount = $this->input->post('min_discount');

        if ($max_discount <= $min_discount) {
            $this->form_validation->set_message('check_discounts', 'The max offer discount must be greater than the min offer discount.');
            return FALSE;
        }

        return TRUE;
    }

    public function view_offers()
    {
        return $this->Offer_model->get_offer_list();
    }

    public function delete_offer()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (print_msg(!has_permissions('delete', 'offer'), PERMISSION_ERROR_MSG, 'offer', false)) {
                return false;
            }
            
            $offer_id = $_GET['id'];
            $slider_check = $this->db->query(
                "SELECT COUNT(*) as count FROM offer_sliders
                 WHERE FIND_IN_SET(?, offer_ids) > 0",
                [$offer_id]
            )->row();
            
            if ($slider_check->count > 0) {
                $this->response['error'] = true;
                $this->response['message'] = 'This offer is connected to a slider. Please remove it from slider(s) first before deleting.';
                print_r(json_encode($this->response));
                return;
            }
            
            if (delete_details(['id' => $offer_id], 'offers') == TRUE) {
                $this->response['error'] = false;
                $this->response['message'] = 'Deleted Successfully';
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Something Went Wrong';
            }
            print_r(json_encode($this->response));
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
