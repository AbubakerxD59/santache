<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manage_stock extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation', 'upload']);
        $this->load->helper(['url', 'language', 'file']);
        $this->load->model(['product_model', 'product_faqs_model', 'category_model']);
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'manage_stock';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Stock Management| ' . $settings['app_name'];
            $this->data['meta_description'] = 'Stock Management |' . $settings['app_name'];

            // $this->data['fetched_data']['product'][0]['name'] = '';


            if (isset($_GET['edit_id'])) {

                $stock = fetch_details("product_variants", ['id' => $_GET['edit_id']], ['stock', 'product_id', 'attribute_value_ids']);
                $attribute_value = fetch_details("attribute_values", ['id' => $stock[0]['attribute_value_ids']], ['value']);
                $id = $stock[0]['product_id'];
              
                $this->data['fetched_data'] = fetch_product("", "", $id);
                $this->data['fetched'] = (isset($stock[0]['stock']) && !empty($stock[0]['stock'])) ? $stock[0]['stock'] : '0';
                $this->data['attribute'] = $attribute_value;

            }
            $this->data['categories'] = $this->category_model->get_categories();

       

            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function get_stock_list()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $category_id = isset($_GET['category_id']) ? trim($_GET['category_id']) : '';
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            $order = isset($_GET['order']) ? trim($_GET['order']) : 'ASC';
            
            if (!in_array(strtoupper($order), ['ASC', 'DESC'])) {
                $order = 'ASC';
            }
            
            if ($limit > 500) {
                $limit = 500;
            }
            
            // Validate offset
            if ($offset < 0) {
                $offset = 0;
            }
            
            if (!empty($category_id)) {
                $category_id = (int)$category_id;
                if ($category_id <= 0) {
                    $category_id = '';
                }
            }
            
            return $this->product_model->get_stock_details();
        } else {
            redirect('admin/login', 'refresh');
        }
    }


    public function update_stock()
    {
        $this->form_validation->set_rules('product_name', 'Product Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('current_stock', 'Current Stock', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|numeric|greater_than[0]|xss_clean');
        $this->form_validation->set_rules('type', 'Type', 'trim|required|in_list[add,subtract]|xss_clean');
        $this->form_validation->set_rules('variant_id', 'Variant ID', 'trim|required|numeric|xss_clean');
        
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = validation_errors();
            print_r(json_encode($this->response));
            return;
        }
        
        $variant_id = (int)$this->input->post('variant_id', true);
        $current_stock = (int)$this->input->post('current_stock', true);
        $quantity = (int)$this->input->post('quantity', true);
        $type = $this->input->post('type', true);
        
        $variant = fetch_details('product_variants', ['id' => $variant_id], 'id, stock');
        if (empty($variant)) {
            $this->response['error'] = true;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = 'Invalid variant ID';
            print_r(json_encode($this->response));
            return;
        }
        
        if ($current_stock < 0 || $quantity < 0) {
            $this->response['error'] = true;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = 'Stock values cannot be negative';
            print_r(json_encode($this->response));
            return;
        }
        
        if ($type == 'subtract' && $quantity > $current_stock) {
            $this->response['error'] = true;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = "Subtracted stock cannot be greater than current stock";
            print_r(json_encode($this->response));
            return;
        }

        if ($type == 'add') {
            update_stock([$variant_id], [$quantity], 'plus');
        } else {
            update_stock([$variant_id], [$quantity]);
        }

        $this->response['error'] = false;
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
        $this->response['message'] = 'Stock Updated Successfully';
        print_r(json_encode($this->response));
    }
    


    public function get_product_data()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
           
            $status =  (isset($_GET['status']) && $_GET['status'] != "") ? $this->input->get('status', true) : NULL;
            if (isset($_GET['flag']) && !empty($_GET['flag'])) {
                return $this->product_model->get_product_details($_GET['flag'], $status);
            }
            return $this->product_model->get_product_details(null, $status);
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
