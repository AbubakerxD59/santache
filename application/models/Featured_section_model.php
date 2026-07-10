<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Featured_section_model extends CI_Model
{
    function add_featured_section($data)
    {
        $data = escape_array($data);

        if (isset($data['product_ids']) && !empty($data['product_ids']) && trim($data['product_type']) == 'custom_products') {
            $product_ids = implode(',', $data['product_ids']);
        } elseif (isset($data['digital_product_ids']) && !empty($data['digital_product_ids']) && trim($data['product_type']) == 'digital_product') {
            $product_ids = implode(',', $data['digital_product_ids']);
        } else {
            $product_ids = null;
        }

        // Prepare data for insertion or update
        $featured_data = [
            'title' => $data['title'],
            'short_description' => $data['short_description'],
            'product_type' => $data['product_type'],
            'categories' => (isset($data['categories']) && !empty($data['categories'])) ? implode(',', $data['categories']) : null,
            'product_ids' => $product_ids,
            'style' => $data['style']
        ];

        // Insert or update the database
        if (isset($data['edit_featured_section'])) {
            if (strtolower(trim($data['product_type'])) != 'custom_products' && strtolower(trim($data['product_type'])) != 'digital_product') {
                $featured_data['product_ids'] = null;
            }
            
            $this->db->set($featured_data)->where('id', $data['edit_featured_section'])->update('sections');
        } else {
            $this->db->insert('sections', $featured_data);
        }
    }


  public function get_section_list()
{
    $offset = 0;
    $limit = 10;
    $sort = 'u.id';
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

    if (isset($_GET['search']) && $_GET['search'] != '') {
        $search = $_GET['search'];
        $multipleWhere = [
            'id' => $search,
            'title' => $search,
            'short_description' => $search
        ];
    }

    // ✅ PRODUCT TYPE FILTER (ADDED)
    $product_type = isset($_GET['product_type']) && $_GET['product_type'] != ''
        ? $_GET['product_type']
        : null;

    /* ================= COUNT QUERY ================= */
    $count_res = $this->db->select(' COUNT(id) as `total` ');

    if (!empty($multipleWhere)) {
        $count_res->or_like($multipleWhere);
    }

    // ✅ PRODUCT TYPE FILTER (COUNT)
    if ($product_type !== null) {
        $count_res->where('product_type', $product_type);
    }

    $city_count = $count_res->get('sections')->result_array();

    foreach ($city_count as $row) {
        $total = $row['total'];
    }

    /* ================= DATA QUERY ================= */
    $search_res = $this->db->select(' * ');

    if (!empty($multipleWhere)) {
        $search_res->or_like($multipleWhere);
    }

    // ✅ PRODUCT TYPE FILTER (DATA)
    if ($product_type !== null) {
        $search_res->where('product_type', $product_type);
    }

    $city_search_res = $search_res
        ->order_by($sort, "asc")
        ->limit($limit, $offset)
        ->get('sections')
        ->result_array();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();

    foreach ($city_search_res as $row) {
        $row = output_escaping($row);

        $category_ids = explode(',', $row['categories']);
        $category_names = [];
        foreach ($category_ids as $id) {
            $category_name = fetch_details('categories', ['id' => $id], 'name');
            $category_names[] = !empty($category_name)
                ? output_escaping($category_name[0]['name'])
                : '';
        }

        $rows[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'short_description' => $row['short_description'],
            'style' => ucfirst(str_replace('_', ' ', $row['style'])),
            'product_ids' => $row['product_ids'],
            'categories' => implode(',', $category_names),
            'product_type' => ucwords(str_replace('_', ' ', $row['product_type'])),
            'date' => date('d-m-Y', strtotime($row['date_added'])),
            'operate' => '<a href="'.base_url('admin/featured-sections/?edit_id='.$row['id']).'" class="btn btn-sm btn-primary">
                            <i class="fa fa-pen"></i>
                          </a>'
        ];
    }

    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}


}
