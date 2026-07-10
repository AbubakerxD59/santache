<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * {{CONTROLLER_NAME}} Controller
 * 
 * @package    CodeIgniter
 * @subpackage Controllers
 * @category   Controller
 * @author     {{AUTHOR}}
 * @created    {{DATE}}
 */
class {{CONTROLLER_NAME}} extends CI_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        // Load required models
        // $this->load->model('{{MODEL_NAME}}_model');
        
        // Load required libraries
        $this->load->library(['session', 'form_validation']);
        
        // Load required helpers
        $this->load->helper(['url', 'form', 'html']);
        
        // Set default data
        $this->data = array();
        $this->data['page_title'] = '{{CONTROLLER_NAME}}';
        $this->data['controller'] = '{{CONTROLLER_NAME_LOWER}}';
    }
    
    /**
     * Index page
     * 
     * @return void
     */
    public function index()
    {
        $this->data['page_title'] = '{{CONTROLLER_NAME}} - Index';
        $this->data['breadcrumb'] = array(
            'Home' => base_url(),
            '{{CONTROLLER_NAME}}' => ''
        );
        
        // Get data from model
        // $this->data['items'] = $this->{{MODEL_NAME}}_model->get_all();
        
        // Load view
        $this->load->view('{{VIEW_PATH}}/index', $this->data);
    }
    
    /**
     * Show single item
     * 
     * @param int $id
     * @return void
     */
    public function show($id = null)
    {
        if (!$id) {
            show_404();
        }
        
        // Get item from model
        // $item = $this->{{MODEL_NAME}}_model->get_by_id($id);
        // if (!$item) {
        //     show_404();
        // }
        
        $this->data['page_title'] = '{{CONTROLLER_NAME}} - View';
        $this->data['breadcrumb'] = array(
            'Home' => base_url(),
            '{{CONTROLLER_NAME}}' => base_url('{{CONTROLLER_NAME_LOWER}}'),
            'View' => ''
        );
        
        // $this->data['item'] = $item;
        
        // Load view
        $this->load->view('{{VIEW_PATH}}/show', $this->data);
    }
    
    /**
     * Create new item
     * 
     * @return void
     */
    public function create()
    {
        $this->data['page_title'] = '{{CONTROLLER_NAME}} - Create';
        $this->data['breadcrumb'] = array(
            'Home' => base_url(),
            '{{CONTROLLER_NAME}}' => base_url('{{CONTROLLER_NAME_LOWER}}'),
            'Create' => ''
        );
        
        // Handle form submission
        if ($this->input->post()) {
            $this->_handle_create();
        }
        
        // Load view
        $this->load->view('{{VIEW_PATH}}/create', $this->data);
    }
    
    /**
     * Edit existing item
     * 
     * @param int $id
     * @return void
     */
    public function edit($id = null)
    {
        if (!$id) {
            show_404();
        }
        
        // Get item from model
        // $item = $this->{{MODEL_NAME}}_model->get_by_id($id);
        // if (!$item) {
        //     show_404();
        // }
        
        $this->data['page_title'] = '{{CONTROLLER_NAME}} - Edit';
        $this->data['breadcrumb'] = array(
            'Home' => base_url(),
            '{{CONTROLLER_NAME}}' => base_url('{{CONTROLLER_NAME_LOWER}}'),
            'Edit' => ''
        );
        
        // $this->data['item'] = $item;
        
        // Handle form submission
        if ($this->input->post()) {
            $this->_handle_update($id);
        }
        
        // Load view
        $this->load->view('{{VIEW_PATH}}/edit', $this->data);
    }
    
    /**
     * Delete item
     * 
     * @param int $id
     * @return void
     */
    public function delete($id = null)
    {
        if (!$id) {
            show_404();
        }
        
        // Get item from model
        // $item = $this->{{MODEL_NAME}}_model->get_by_id($id);
        // if (!$item) {
        //     show_404();
        // }
        
        // Delete item
        // if ($this->{{MODEL_NAME}}_model->delete($id)) {
        //     $this->session->set_flashdata('success', 'Item deleted successfully.');
        // } else {
        //     $this->session->set_flashdata('error', 'Failed to delete item.');
        // }
        
        redirect('{{CONTROLLER_NAME_LOWER}}');
    }
    
    /**
     * Handle create form submission
     * 
     * @return void
     */
    private function _handle_create()
    {
        // Set validation rules
        $this->form_validation->set_rules('name', 'Name', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('description', 'Description', 'trim');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
        
        if ($this->form_validation->run()) {
            $data = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'status' => $this->input->post('status'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            
            // Insert data
            // if ($this->{{MODEL_NAME}}_model->insert($data)) {
            //     $this->session->set_flashdata('success', 'Item created successfully.');
            //     redirect('{{CONTROLLER_NAME_LOWER}}');
            // } else {
            //     $this->session->set_flashdata('error', 'Failed to create item.');
            // }
        }
    }
    
    /**
     * Handle update form submission
     * 
     * @param int $id
     * @return void
     */
    private function _handle_update($id)
    {
        // Set validation rules
        $this->form_validation->set_rules('name', 'Name', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('description', 'Description', 'trim');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
        
        if ($this->form_validation->run()) {
            $data = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            
            // Update data
            // if ($this->{{MODEL_NAME}}_model->update($id, $data)) {
            //     $this->session->set_flashdata('success', 'Item updated successfully.');
            //     redirect('{{CONTROLLER_NAME_LOWER}}');
            // } else {
            //     $this->session->set_flashdata('error', 'Failed to update item.');
            // }
        }
    }
    
    /**
     * AJAX method example
     * 
     * @return void
     */
    public function ajax_get_data()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $response = array(
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => array()
        );
        
        // Get data from model
        // $response['data'] = $this->{{MODEL_NAME}}_model->get_all();
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}