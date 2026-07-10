<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Brand_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }


    public function add_brand($data)
    {
        $data = escape_array($data);

        $brands_data = [
            'name' => $data['brand_input_name'],
            'slug' => create_unique_slug($data['brand_input_name'], 'brands'),
            'status' => '1',
        ];

        if (isset($data['edit_brand']) && !empty($data['edit_brand'])) {
            unset($brands_data['status']);
            if (isset($data['brand_input_image'])) {
                $brands_data['image'] = $data['brand_input_image'];
            }
            $this->db->set($brands_data)->where('id', $data['edit_brand'])->update('brands');
        } else {
            if (isset($data['brand_input_image']) && !empty($data['brand_input_image'])) {
                $brands_data['image'] = $data['brand_input_image'];
            }
            $this->db->insert('brands', $brands_data);
        }
    }


    public function get_brands($id = NULL, $limit = '', $offset = '', $sort = 'row_order', $order = 'ASC', $has_child_or_item = 'true', $slug = '', $ignore_status = '', $seller_id = '', $status = '1')
    {
        $this->db->select('b.id as brand_id , b.name as brand_name, b.slug as brand_slug, b.image as brand_img, b.status as brand_status');

        $this->db->join('products p', ' p.brand = b.name', 'left');
        $this->db->group_start();
        $this->db->where(['b.status ' => $status]);
        $this->db->or_where(['b.name ' => ' p.brand '], NULL, FALSE);
        $this->db->group_End();
        $this->db->group_by('b.id');

        if (!empty($limit) || !empty($offset)) {
            $this->db->offset($offset);
            $this->db->limit($limit);
        }

        $this->db->order_by($sort, $order);

        $parent = $this->db->get('brands b');
        $brands = $parent->result();
        $count_res = $this->db->count_all_results('brands b');
        $i = 0;

        return  json_decode(json_encode($brands), 1);
    }


    public function delete_brand($id)
    {
        // Escape the ID to prevent SQL injection
        $id = escape_array($id);
    
        // Get the brand name from the brands table
        $brand_query = $this->db->where('id', $id)->get('brands');
        if ($brand_query->num_rows() == 0) {
            // Brand not found
            return FALSE;
        }
        $brand_name = $brand_query->row()->name;
    
        // Check if the brand is assigned to any products
        $product_query = $this->db->where('brand', $brand_name)->get('products');
        if ($product_query->num_rows() > 0) {
            // If there are products associated with this brand, return FALSE
            return FALSE;
        }
    
        // If no products are associated, proceed with the deletion
        $this->db->trans_start();
        $this->db->where('id', $id)->delete('brands');
        $this->db->trans_complete();
    
        // Log the result for debugging
    
        return $this->db->trans_status();
    }
    


    public function get_brand_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = '';
        $where = ['status !=' => NULL];

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['`id`' => $search, '`name`' => $search];
        }
        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        $brand_count = $count_res->get('brands')->result_array();
        foreach ($brand_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $brand_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('brands')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($brand_search_res as $row) {
            $operate = '<div class="dropdown">
        <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v"></i>
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href=' . base_url('admin/brand/create_brand') . '?edit_id=' . $row['id'] . '><i class="fa fa-pen"></i> Edit</a>
            <a href="javascript:void(0)" class="delete-brand dropdown-item" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i> Delete</a>
        </div>';



            if ($row['status'] == '1') {
                $tempRow['status'] = '<a class="badge bg-success text-white" ></a>';
                $tempRow['status'] .= '<a class="form-switch update_active_status " data-table="brands" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><input class="form-check-input " type="checkbox" role="switch" checked></a>';
            } else {
                $tempRow['status'] = '<a class="badge bg-danger text-white" ></a>';
                $tempRow['status'] .= '<a class="form-switch update_active_status mr-1 mb-1" data-table="brands" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><input class="form-check-input " type="checkbox" role="switch" ></a>';
            }

            $tempRow['id'] = $row['id'];
            $tempRow['name'] = output_escaping($row['name']); // Assuming output_escaping() handles escaping
            $tempRow['name'] = str_replace("'", "`", $tempRow['name']) . '</a>';

            if (empty($row['image']) || file_exists(FCPATH  . $row['image']) == FALSE) {
                $row['image'] = base_url() . NO_IMAGE;
                $row['image_main'] = base_url() . NO_IMAGE;
            } else {
                $row['image_main'] = base_url($row['image']);
                $row['image'] = get_image_url($row['image'], 'thumb', 'sm');
            }
            $tempRow['image'] = "<div class='image-box-100' ><a href='" . $row['image_main'] . "' data-toggle='lightbox' data-gallery='gallery' class='image-box-100'> <img src='" . $row['image'] . "' class='h-10' ></a></div>";

            $tempRow['operate'] = $operate;

            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
}
