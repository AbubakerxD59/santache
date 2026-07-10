<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Slider_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }

    function add_slider($data)
    {

        $data = escape_array($data);

        $slider_data = [
            'type' => $data['slider_type'],
            'image' => $data['image'],
        ];
        $slider_data['link'] = '';
        if (isset($data['slider_type']) && $data['slider_type'] == 'categories' && isset($data['category_id']) && !empty($data['category_id'])) {
            $slider_data['type_id'] = $data['category_id'];
        }
        if (isset($data['slider_type']) && $data['slider_type'] == 'slider_url' && isset($data['link']) && !empty($data['link'])) {
            $slider_data['link'] = $data['link'];
            $slider_data['type_id'] = 0;
        }
        if (isset($data['slider_type']) && $data['slider_type'] == 'products' && isset($data['product_id']) && !empty($data['product_id'])) {
            $slider_data['type_id'] = $data['product_id'];
        }

        if (isset($data['slider_type']) && $data['slider_type'] == 'brand' && isset($data['brand_id']) && !empty($data['brand_id'])) {
            $slider_data['type_id'] = $data['brand_id'];
        }

        if (isset($data['edit_slider'])) {
            if (empty($data['image'])) {
                unset($slider_data['image']);
            }

            $this->db->set($slider_data)->where('id', $data['edit_slider'])->update('sliders');
        } else {
            $this->db->insert('sliders', $slider_data);
        }
    }

    function get_slider_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = '';

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
            $multipleWhere = ['`id`' => $search, '`type`' => $search];
        }
        $typeWhere = '';
        if (isset($_GET['type_filter']) && $_GET['type_filter'] != '') {
            $typeWhere = ['type' => $_GET['type_filter']];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_where($multipleWhere);
        }
        if (isset($typeWhere) && !empty($typeWhere)) {
            $count_res->where($typeWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $slider_count = $count_res->get('sliders')->result_array();

        foreach ($slider_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($typeWhere) && !empty($typeWhere)) {
            $search_res->where($typeWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $slider_search_res = $search_res->order_by($sort, "asc")->limit($limit, $offset)->get('sliders')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($slider_search_res as $row) {
            $row = output_escaping($row);

            $operate = '<div class="dropdown">
            <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
              <a class="dropdown-item" href=' . base_url('admin/slider') . '?edit_id=' . $row['id'] . '><i class="fa fa-pen"></i> Edit</a>
              <a href="javascript:void(0)" class="dropdown-item" id="delete-slider" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i> Delete</a></div>';

            $tempRow['id'] = $row['id'];
            $tempRow['type'] = ucwords(str_replace('_', " ", $row['type']));
            $tempRow['link'] = $row['link'];
            $type_id = $row['type_id'];


            if ($row['type'] == 'categories') {
                $category_details = fetch_details('categories', ['id' => $type_id], 'name');
                if (!empty($category_details) && isset($category_details[0]['name'])) {
                    $tempRow['type_id'] = output_escaping($category_details[0]['name']);
                } else {
                    $tempRow['type_id'] = "N/A";
                }
            } elseif ($row['type'] == 'products') {
                $product_details = fetch_details('products', ['id' => $type_id], 'name');
                if (!empty($product_details) && isset($product_details[0]['name'])) {
                    $tempRow['type_id'] = output_escaping($product_details[0]['name']);
                } else {
                    $tempRow['type_id'] = "N/A";
                }
            } elseif ($row['type'] == 'brand') {
                $brand_details = fetch_details('brands', ['id' => $type_id], 'name');
                if (!empty($brand_details) && isset($brand_details[0]['name'])) {
                    $tempRow['type_id'] = output_escaping($brand_details[0]['name']);
                } else {
                    $tempRow['type_id'] = "N/A";
                }
            } else {
                $tempRow['type_id'] = "-"; // For other types like default or slider_url
            }

            if (empty($row['image']) || file_exists(FCPATH . $row['image']) == FALSE) {
                $row['image'] = base_url() . NO_IMAGE;
                $row['image_main'] = base_url() . NO_IMAGE;
            } else {
                $row['image_main'] = base_url($row['image']);
                $row['image'] = get_image_url($row['image'], 'thumb', 'sm');
            }
            $tempRow['image'] = "<div class='image-box-100' ><a href='" . $row['image_main'] . "' data-toggle='lightbox' data-gallery='gallery'> <img src='" . $row['image'] . "' class='h-25' ></a></div>";
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_slider($limit = '', $offset = '', $sort = 'row_order', $order = 'ASC')
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = '';

        if (isset($_POST['offset']))
            $offset = $_POST['offset'];
        if (isset($_POST['limit']))
            $limit = $_POST['limit'];

        if (isset($_POST['sort']))
            if ($_POST['sort'] == 'id') {
                $sort = "id";
            } else {
                $sort = $_POST['sort'];
            }
        if (isset($_POST['order']))
            $order = $_POST['order'];

        if (isset($_POST['search']) and $_POST['search'] != '') {
            $search = $_POST['search'];
            $multipleWhere = ['`id`' => $search, '`type`' => $search];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_where($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $slider_count = $count_res->get('sliders')->result_array();

        foreach ($slider_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $slider_search_res = $search_res->order_by($sort, "asc")->limit($limit, $offset)->get('sliders')->result_array();

        foreach ($slider_search_res as &$row) {
            $row['image'] = get_image_url($row['image'], 'thumb', 'sm', true);
            $row['link'] = $row['link'] !== "" && $row['link'] !== null ? $row['linl'] : '';
        }

        return  json_decode(json_encode($slider_search_res), 1);
    }
}
