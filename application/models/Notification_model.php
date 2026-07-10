<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Notification_model extends CI_Model
{

    public function add_notification($data)
    {
        $data = escape_array($data);
        $notification_data = array(
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'],
            'send_to' => $data['send_to'],
            'users_id' => (isset($data['users_id']) && !empty($data['users_id'])) ? $data['users_id'] : 0,
        );

        if (isset($data['type']) && $data['type'] == 'categories') {
            $notification_data['type_id'] = $data['category_id'];
        }
        if (isset($data['type']) && $data['type'] == 'products') {
            $notification_data['type_id'] = $data['product_id'];
        }
        if (isset($data['type']) && $data['type'] == 'notification_url') {
            $notification_data['link'] = $data['link'];
        }
        if (isset($data['send_to']) && $data['send_to'] == 'specific_user') {
            $notification_data['users_id'] = stripslashes($data['select_user_id']);
        }

        if (isset($data['image']) && !empty($data['image'])) {
            $notification_data['image'] = $data['image'];
        }
        return $this->db->insert('notifications', $notification_data);
    }

    function get_notifications($offset, $limit, $sort, $order, $user_id = null, $user_created_at = null)
    {
        $notification_data = [];

        // Get user's registration date if user_id is provided
        if ($user_id !== null && $user_created_at === null) {
            $user_data = $this->db->select('created_at')->where('id', $user_id)->get('users')->result_array();
            if (!empty($user_data)) {
                $user_created_at = $user_data[0]['created_at'];
            }
        }

        // Build the base query for count
        $this->db->select('COUNT(id) as `total`');
        if ($user_id !== null) {
            $this->db->group_start()
                ->where("JSON_CONTAINS(users_id, '\"$user_id\"')")
                ->or_where("users_id", '0')
                ->group_end();
        } else {
            $this->db->where("users_id", '0');
        }
        
        // Filter notifications sent after user registration date
        if ($user_created_at !== null) {
            $this->db->where('date_sent >=', $user_created_at);
        }
        
        $count_res = $this->db->get('notifications')->result_array();

        // Build the search query
        $this->db->select('*')->order_by($sort, $order)->limit($limit, $offset);
        if ($user_id !== null) {
            $this->db->group_start()
                ->where("JSON_CONTAINS(users_id, '\"$user_id\"')")
                ->or_where("users_id", '0')
                ->group_end();
        } else {
            $this->db->where("users_id", '0');
        }
        
        // Filter notifications sent after user registration date
        if ($user_created_at !== null) {
            $this->db->where('date_sent >=', $user_created_at);
        }
        
        $search_res = $this->db->get('notifications')->result_array();

        for ($i = 0; $i < count($search_res); $i++) {
            $search_res[$i]['title'] = output_escaping($search_res[$i]['title']);
            $search_res[$i]['message'] = output_escaping($search_res[$i]['message']);
            $search_res[$i]['send_to'] = output_escaping($search_res[$i]['send_to']);
            $search_res[$i]['users_id'] = output_escaping($search_res[$i]['users_id']);

            if (empty($search_res[$i]['image'])) {
                $search_res[$i]['image'] = '';
            } else {
                if (file_exists(FCPATH . $search_res[$i]['image']) == FALSE) {
                    $search_res[$i]['image'] = base_url() . NO_IMAGE;
                } else {
                    $search_res[$i]['image'] = base_url() . $search_res[$i]['image'];
                }
            }
            if (empty($search_res[$i]['link'])) {
                $search_res[$i]['link'] = '';
            }
        }
        $notification_data['total'] = $count_res[0]['total'];
        $notification_data['data'] = $search_res;

        return $notification_data;
    }


    public function get_notifications_data($offset = 0, $limit = 10, $sort = 'read_by', $order = 'ASC')
    {

        $multipleWhere = '';
        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'read_by') {
                $sort = "read_by";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['id' => $search, 'title' => $search, 'message' => $search];
        }

        if (isset($_GET['message_type']) && ($_GET['message_type'] != '')) {
            $where = ('read_by =' . $_GET['message_type']);
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }
        $city_count = $count_res->get('system_notification')->result_array();

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

        $city_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('system_notification')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($city_search_res as $row) {
            $row = output_escaping($row);

            $operate = '<div class="dropdown">
            <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';

            // Check if type is not 'flash sale' before adding the edit button
            if ($row['type'] !== 'flash_sale') {
                $operate .= '<a class="dropdown-item" href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['type_id'] . '><i class="fa fa-eye"></i> view</a>';
            }

            $operate .= '<a href="javascript:void(0)" class="delete_system_noti dropdown-item" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i> Delete</a></div>';

            $tempRow['id'] = $row['id'];
            $tempRow['title'] = $row['title'];
            $tempRow['message'] = $row['message'];
            $tempRow['type'] = str_replace('_', ' ', $row['type']);
            $tempRow['type_id'] = $row['type_id'];
            $tempRow['read_by'] = ($row['read_by'] == 1) ? '<label class="badge bg-primary">Read</label>' : '<label class="badge bg-secondary">Un-Read</label>';
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

 public function get_notification_list()
{
    $offset = $this->input->get('offset', true) ?? 0;
    $limit  = $this->input->get('limit', true) ?? 10;
    $sort   = $this->input->get('sort', true) ?? 'id';
    $order  = $this->input->get('order', true) ?? 'DESC';
    $search = $this->input->get('search', true);
    $type   = $this->input->get('type', true);

    /* ================= COUNT QUERY ================= */
    $this->db->from('notifications');

    if (!empty($type)) {
        $this->db->where('type', $type);
    }

    if (!empty($search)) {
        $this->db->group_start()
            ->like('id', $search)
            ->or_like('title', $search)
            ->or_like('message', $search)
            ->or_like('send_to', $search)
            ->or_like('type', $search)
        ->group_end();
    }

    $total = $this->db->count_all_results();

    /* ================= DATA QUERY ================= */
    $this->db->from('notifications');

    if (!empty($type)) {
        $this->db->where('type', $type);
    }

    if (!empty($search)) {
        $this->db->group_start()
            ->like('id', $search)
            ->or_like('title', $search)
            ->or_like('message', $search)
            ->or_like('send_to', $search)
            ->or_like('type', $search)
        ->group_end();
    }

    $this->db->order_by($sort, $order);
    $this->db->limit($limit, $offset);

    $result = $this->db->get()->result_array();

    $rows = [];

    foreach ($result as $row) {
        $row = output_escaping($row);

        /* Image handling */
        if (empty($row['image']) || !file_exists(FCPATH . $row['image'])) {
            $row['image'] = base_url(NO_IMAGE);
        } else {
            $row['image'] = base_url($row['image']);
        }

        $rows[] = [
            'id'       => $row['id'],
            'title'    => $row['title'],
            'type'     => ucwords(str_replace('_', ' ', $row['type'])),
            'message'  => $row['message'],
            'send_to'  => ucwords(str_replace('_', ' ', $row['send_to'])),
            'image'    => "<img src='{$row['image']}' class='img-fluid rounded' width='60'>",
            'operate'  => '<a href="javascript:void(0)" class="delete_notifications" data-id="'.$row['id'].'"><i class="fa fa-trash"></i></a>'
        ];
    }

    echo json_encode([
        'total' => $total,
        'rows'  => $rows
    ]);
}


    public function mark_all_as_read()
    {
        if (update_details(['read_by' => '1'], ['read_by' => 0], 'system_notification')) {
            $response_data['error'] =  false;
            $response_data['message'] =  'All notifications marked as read successfully.';
        } else {
            $response_data['error'] =  true;
            $response_data['message'] =  'Opps! Something went wrong.';
        }
        print_r(json_encode($response_data));
    }
}
