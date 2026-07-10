<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Custom_sms_model extends CI_Model
{

    public function add_custom_sms($data)
    {
        $data = escape_array($data);
        $custom_sms_data = [
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type']
        ];
        if (isset($data['edit_custom_sms']) && !empty($data['edit_custom_sms'])) {
            $this->db->set($custom_sms_data)->where('id', $data['edit_custom_sms'])->update('custom_sms');
        } else {
            $this->db->insert('custom_sms', $custom_sms_data);
        }
    }

    public function get_custom_sms_data($offset = 0, $limit = 10, $sort = 'id', $order = 'ASC')
    {

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
        if (!empty($_GET['type'])) {
            $type = strtolower($_GET['type']);
            $type = str_replace(' ', '_', $type); // Place Order → place_order
            $where['type'] = $type;
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = strtolower($_GET['search']);
            $search = str_replace(' ', '_', $search);
            $multipleWhere = ['id' => $search, 'title' => $search, 'message' => $search, 'type' => $search];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }
        $city_count = $count_res->get('custom_sms')->result_array();

        foreach ($city_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $city_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('custom_sms')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($city_search_res as $row) {
            $row = output_escaping($row);
            $operate = '<div class="dropdown">
                            <a href="#" role="button" class="no-caret" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v" style="color:#6035C0;"></i>
                            </a>
                            <div class="dropdown-menu">
                                <button type="button"
                                    class="dropdown-item edit_sms_modal"
                                    data-toggle="modal"
                                    data-id="' . $row['id'] . '"
                                    data-target="#sms-gateway-modal"
                                    data-url="admin/sms-gateway-settings">
                                    <i class="fa fa-pen"></i> Edit
                                </button>
                            </div>
                        </div>';


            $tempRow['id'] = $row['id'];
            $tempRow['title'] = $row['title'];
            $tempRow['message'] = $row['message'];
            $tempRow['type'] = ucwords(str_replace('_', " ", $row['type']));
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
}
