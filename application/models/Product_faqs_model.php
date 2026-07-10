<?php


defined('BASEPATH') or exit('No direct script access allowed');


class Product_faqs_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }

    function add_product_faqs($data)
    {

        $data = escape_array($data);
        $faq_data = [
            'product_id' => $data['product_id'],
            'user_id' => isset($data['user_id']) && !empty($data['user_id']) ? $data['user_id'] : $_SESSION['user_id'],
            'question' => $data['question'],
            'answer' => isset($data['answer']) && !empty($data['answer']) ? $data['answer'] : "",
            'answered_by' => isset($data['answer']) && !empty($data['answer']) ? $_SESSION['user_id'] : 0,
        ];

        if (isset($data['edit_product_faq']) && !empty($data['edit_product_faq']) && $data['edit_product_faq'] != '') {
            update_details($faq_data, ['id' => $data['edit_product_faq']], 'product_faqs');
        } else {
            $this->db->insert('product_faqs', $faq_data);
            return $this->db->insert_id();
        }
    }

    public function get_faqs_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';

        $multipleWhere = '';

        if (isset($offset))
            $offset = $_GET['offset'];
        if (isset($limit))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($sort == 'id') {
                $sort = "id";
            } else {
                $sort = $sort;
            }

        if (isset($order) and $order != '') {
            $search = $order;
        }

        $count_res = $this->db->select(' COUNT(pf.id) as total ,p.name as product_name')->join('users u', 'u.id=pf.user_id')->join('products p', 'p.id=pf.product_id');
        if (isset($_GET['search']) && trim($_GET['search'])) {
            $search = trim($_GET['search']);
            $multipleWhere = ['pf.id' => $search, 'pf.product_id' => $search, 'p.name' => $search, 'pf.user_id' => $search, 'pf.question' => $search, 'pf.answer' => $search];
        }
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_start();
            $count_res->or_like($multipleWhere);
            $this->db->group_end();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $rating_count = $count_res->get('product_faqs pf')->result_array();
        foreach ($rating_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select('pf.*,u.username as user_name,p.name as product_name')->join('users u', 'u.id=pf.user_id')->join('products p', 'p.id=pf.product_id');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_start();
            $search_res->or_like($multipleWhere);
            $this->db->group_end();
        }

        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $rating_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('product_faqs pf')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        $i = 0;
        foreach ($rating_search_res as $row) {

            $product = fetch_details('products', ['id' => $row['product_id']], 'name');
            
            $row = output_escaping($row);
            $date = new DateTime($row['date_added']);

            $answered_by = fetch_details('users', 'id=' . $row['answered_by'], 'username');

            $operate = '<div class="dropdown">
            <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
             <a href="javascript:void(0)" class="edit_btn dropdown-item" title="View" data-id="' . $row['id'] . '" data-toggle="modal" data-target="#product_faq_value_id"data-url="admin/product_faqs/"><i class="fa fa-pen"></i> Edit</a>
              <a href="javascript:void(0)" class="delete-product-faq dropdown-item" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i> Delete</a></div>';
 
            $tempRow['id'] = $row['id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['product_id'] = $row['product_id'];
            $tempRow['product_name'] = isset($product[0]['name']) ? $product[0]['name'] : " " ;
            $tempRow['votes'] = $row['votes'];
            $tempRow['question'] = $row['question'];
            $tempRow['answer'] = $row['answer'];
            $tempRow['answered_by'] = $row['answered_by'];
            $tempRow['answered_by_name'] = (isset($answered_by[0]['username']) && !empty($answered_by)) ? $answered_by[0]['username'] : '';
            $tempRow['username'] = $row['user_name'];
            $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $i++;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function delete_faq($faq_id)
    {
        $faq_id = escape_array($faq_id);
        $this->db->delete('product_faqs', ['id' => $faq_id]);
    }
}
