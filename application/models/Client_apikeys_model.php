<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Client_apikeys_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }

    public function set($data)
    {
        $this->load->helper('string');
        $secret_key = random_string('sha1', 40);

        $client_data = [
            'name' => $data['name'],
            'secret' => $secret_key
        ];
        if (isset($data['edit_client_api_keys']) && !empty($data['edit_client_api_keys'])) {
            unset($client_data['secret']);
            $this->db->set($client_data)->where('id', $data['edit_client_api_keys'])->update('client_api_keys');
        } else {
            $this->db->insert('client_api_keys', $client_data);
        }
    }


    public function get_list()
    {
        // Check read permission
        if (!has_permissions('read', 'client_api_keys')) {
            $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
            redirect('admin/home', 'refresh');
        }

        // Initialize variables with default values
        $offset = 0;
        $limit = 10;
        $sort = 'u.id';
        $order = 'ASC';
        $multipleWhere = [];

        // Validate and sanitize input parameters
        if (isset($_GET['offset']) && is_numeric($_GET['offset']) && $_GET['offset'] >= 0) {
            $offset = (int)$_GET['offset'];
        }
        if (isset($_GET['limit']) && is_numeric($_GET['limit']) && $_GET['limit'] > 0 && $_GET['limit'] <= 100) {
            $limit = (int)$_GET['limit'];
        }

        // Validate sort parameter against allowed columns
        $allowed_sort_columns = ['id', 'name', 'secret', 'status'];
        if (isset($_GET['sort']) && in_array($_GET['sort'], $allowed_sort_columns)) {
            $sort = $_GET['sort'];
        }

        // Validate order parameter
        if (isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC'])) {
            $order = strtoupper($_GET['order']);
        }

        // Sanitize search input
        if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
            $search = $this->db->escape_like_str(trim($_GET['search']));
            $multipleWhere = ['id' => $search, 'name' => $search];
        }

        // Get total count
        $count_res = $this->db->select('COUNT(id) as `total`');
        if (!empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }

        $city_count = $count_res->get('client_api_keys')->result_array();
        $total = $city_count[0]['total'] ?? 0;

        // Get search results
        $search_res = $this->db->select('*');
        if (!empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }

        $client_search_res = $search_res->order_by($sort, $order)
            ->limit($limit, $offset)
            ->get('client_api_keys')
            ->result_array();

        $bulkData = [];
        $bulkData['total'] = $total;
        $rows = [];

        foreach ($client_search_res as $row) {
            $row = output_escaping($row);

            // Check permissions for edit and delete operations
            $edit_permission = has_permissions('update', 'client_api_keys');
            $delete_permission = has_permissions('delete', 'client_api_keys');

            // Build operations dropdown based on permissions
            $operate = '';
            if($edit_permission || $delete_permission) {
                $operate .= '<div class="dropdown">
            <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
            }

            if ($edit_permission) {
                $operate .= '<a href="javascript:void(0)" class="edit_btn dropdown-item" title="Edit" data-id="' . $row['id'] . '" data-url="admin/client_api_keys/"><i class="fa fa-pen"></i> Edit</a>';
            }
            if ($delete_permission) {
                $operate .= '<a href="javascript:void(0)" class="dropdown-item" id="delete-client" data-id="' . $row['id'] . '" title="Delete"><i class="fa fa-trash"></i> Delete</a>';
            }
            $operate .= '</div></div>';

            $tempRow = [];
            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['name'];
            $tempRow['secret'] = $row['secret'];

            // Status with permission check for update
            if ($row['status'] == '1') {
                $tempRow['status'] = '<a class="badge bg-success text-white"></a>';
                if ($edit_permission) {
                    $tempRow['status'] .= '<a class="form-switch update_active_status" data-table="client_api_keys" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '"><input class="form-check-input" type="checkbox" role="switch" checked></a>';
                }
            } else {
                $tempRow['status'] = '<a class="badge bg-danger text-white"></a>';
                if ($edit_permission) {
                    $tempRow['status'] .= '<a class="form-switch update_active_status mr-1 mb-1" data-table="client_api_keys" title="Activate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '"><input class="form-check-input" type="checkbox" role="switch"></a>';
                }
            }

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;

        // Set JSON response headers
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($bulkData));
    }
}
