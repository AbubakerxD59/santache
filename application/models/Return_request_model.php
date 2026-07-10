<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Return_request_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }

    function get_return_request_list()
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
            $multipleWhere = ['rr.`id`' => $search, 'oi.`order_id`' => $search, 'u.`username`' => $search, 'u.`email`' => $search, 'u.`mobile`' => $search, 'p.`name`' => $search, 'oi.`price`' => $search, 'rr.`return_reason`' => $search,];
        }

        $count_res = $this->db->select(' COUNT(rr.id) as `total` ')->join('users u', 'u.id=rr.user_id')->join('products p', 'p.id=rr.product_id')->join('order_items oi', 'oi.id=rr.order_item_id');

        if (isset($_GET['status']) && $_GET['status'] != '' && $_GET['status'] !== null) {
            $count_res->where("rr.status", $_GET['status']);
        }

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_start();
            $count_res->or_like($multipleWhere);
            $count_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $request_count = $count_res->get('return_requests rr')->result_array();

        $total = 0;
        foreach ($request_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' rr.id,rr.remarks,rr.return_reason, rr.return_item_image, oi.order_id, u.id as user_id,u.username as username ,p.name as product_name,oi.price,oi.discounted_price,oi.id as order_item_id,oi.quantity,oi.sub_total,rr.status')->join('users u', 'u.id=rr.user_id')->join('products p', 'p.id=rr.product_id')->join('order_items oi', 'oi.id=rr.order_item_id');
        
        if (isset($_GET['status']) && $_GET['status'] != '' && $_GET['status'] !== null) {
            $search_res->where("rr.status", $_GET['status']);
        }

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $offer_search_res = $search_res->order_by($sort, "desc")->limit($limit, $offset)->get('return_requests rr')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($offer_search_res as $row) {
            $row = output_escaping($row);
            $status = $row['status']; // Assuming $row['status'] contains the status value
            isset($row['return_item_image']) && !empty($row['return_item_image']) && $row['return_item_image'] != 'null' ? $return_image = "<img src=" . base_url() . $row['return_item_image'] . " data-toggle='lightbox' data-gallery='gallery' class='img-responsive image-box-100' />" : $return_image = "<img src='" . base_url() . NO_IMAGE . "' class='img-responsive image-box-100' />";

            $operate = '';

            if ($status != 1 && $status != 2) {
                $operate = '<div class="dropdown">
                <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a href="javascript:void(0)" class="edit_request dropdown-item" title="Edit" data-target="#request_rating_modal" data-toggle="modal">
                        <i class="fa fa-pen"></i> Edit
                    </a>
                </div>
            </div>';
            }

            $tempRow['operate'] = $operate;

            $tempRow['id'] = $row['id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['user_name'] = $row['username'];
            $tempRow['order_id'] = $row['order_id'];
            $tempRow['order_item_id'] = $row['order_item_id'];
            $tempRow['product_name'] = $row['product_name'];
            $tempRow['return_reason'] = (isset($row['return_reason']) && !empty($row['return_reason'])) ? $row['return_reason'] : "-";
            $tempRow['return_item_image'] = $return_image;
            $tempRow['price'] = $row['price'];
            $tempRow['discounted_price'] = $row['discounted_price'];
            $tempRow['quantity'] = $row['quantity'];
            $tempRow['sub_total'] = $row['sub_total'];
            $tempRow['status_digit'] = $row['status'];
            $status = [
                '0' => '<span class="badge bg-warning">Pending</span>',
                '1' => '<span class="badge bg-primary">Approved</span>',
                '2' => '<span class="badge bg-danger">Rejected</span>',
            ];

            $tempRow['status'] = $status[$row['status']];
            $tempRow['remarks'] = $row['remarks'];
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }


    function update_return_request($data)
    {

        $data = escape_array($data);
        $request = array(
            'status' => $data['status'],
            'remarks' => (isset($data['update_remarks']) && !empty($data['update_remarks'])) ? $data['update_remarks'] : null,
        );
        $item_id  = $data['order_item_id'];

        $this->db->where('id', $data['return_request_id'])->update('return_requests', $request);

        if ($data['status'] == '1') {
            $this->load->model('order_model');
            process_refund($data['order_item_id'], 'returned');
            $data = fetch_details('order_items', ['id' => $data['order_item_id']], 'product_variant_id,quantity,user_id');
            update_stock($data[0]['product_variant_id'], $data[0]['quantity'], 'plus');
            update_details(['deliver_by' => $_POST['deliver_by']], ['id' => $item_id], 'order_items');
            $this->order_model->update_order_item($item_id, 'return_request_approved', 1);

            //for delivery boy notification
            $order_item_res = fetch_details('order_items', ['id' => $item_id], 'order_id');
            $user_id = $_POST['deliver_by'];
            $customer_id = $data[0]['user_id'];
            $settings = get_settings('system_settings', true);
            $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : '';
            $user_res = fetch_details('users', ['id' => $user_id], 'username,fcm_id');
            $customer_res = fetch_details('users', ['id' => $customer_id], 'username,fcm_id,mobile,email');
            $fcm_ids = array();
            //custom message



            $custom_notification =  fetch_details('custom_notifications', ['type' => "customer_order_returned_request_approved"], '');
            $hashtag_customer_name = '< customer_name >';
            $hashtag_order_id = '< order_item_id >';
            $hashtag_application_name = '< application_name >';
            $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
            $hashtag = html_entity_decode($string);
            $data1 = str_replace(array($hashtag_customer_name, $hashtag_order_id, $hashtag_application_name), array($customer_res[0]['username'], $order_item_res[0]['order_id'], $app_name), $hashtag);
            $message = output_escaping(trim($data1, '"'));
            $delivery_boy_msg = 'Hello Dear ' . $user_res[0]['username'] . ' ' . 'you have new order to be pickup order ID #' . $order_item_res[0]['order_id'] . ' please take note of it! Thank you. Regards ' . $app_name . '';
            $customer_msg = (!empty($custom_notification)) ? $message :  'Hello Dear ' . $customer_res[0]['username'] . ',your return request of order item id' . $item_id  . ' is approved';

            (notify_event(
                "customer_order_returned_request_approved",
                ["customer" => [$customer_res[0]['email']], "delivery_boy" => [$user_res[0]['email']]],
                ["customer" => [$customer_res[0]['mobile']], "delivery_boy" => [$user_res[0]['mobile']]],
                ["users.mobile" => $customer_res[0]['mobile']]
            ));

            if (!empty($user_res[0]['fcm_id'])) {
                $fcmMsg = array(
                    'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "You have new order to deliver",
                    'body' => $delivery_boy_msg,
                    'type' => "order",
                );

                $fcm_ids[0][] = $user_res[0]['fcm_id'];
                send_notification($fcmMsg, $fcm_ids, $fcmMsg);
            }
            if (!empty($customer_res[0]['fcm_id'])) {
                $fcmMsg = array(
                    'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Order status updated",
                    'body' => $customer_msg,
                    'type' => "order",
                );

                $fcm_ids[0][] = $customer_res[0]['fcm_id'];
                send_notification($fcmMsg, $fcm_ids, $fcmMsg);
            }
        } elseif ($data['status'] == '2') {

            $this->load->model('order_model');
            $this->order_model->update_order_item($item_id, 'return_request_decline', 1);
            //for delivery boy notification
            $data = fetch_details('order_items', ['id' => $data['order_item_id']], 'product_variant_id,quantity,user_id');
            $order_item_res = fetch_details('order_items', ['id' => $item_id], 'order_id');

            $customer_id = $data[0]['user_id'];
            $settings = get_settings('system_settings', true);
            $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : '';
            $customer_res = fetch_details('users', ['id' => $customer_id], 'username,fcm_id,mobile,email');
            $fcm_ids = array();
            //custom message



            $custom_notification =  fetch_details('custom_notifications', ['type' => "customer_order_returned_request_decline"], '');
            $hashtag_customer_name = '< customer_name >';
            $hashtag_order_id = '< order_item_id >';
            $hashtag_application_name = '< application_name >';
            $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
            $hashtag = html_entity_decode($string);
            $data1 = str_replace(array($hashtag_customer_name, $hashtag_order_id, $hashtag_application_name), array($customer_res[0]['username'], $order_item_res[0]['order_id'], $app_name), $hashtag);
            $message = output_escaping(trim($data1, '"'));
            $customer_msg = (!empty($custom_notification)) ? $message :  'Hello Dear ' . $customer_res[0]['username'] . ',your return request of order item id' . $item_id  . ' has been declined';

            (notify_event(
                "customer_order_returned_request_decline",
                ["customer" => [$customer_res[0]['email']]],
                ["customer" => [$customer_res[0]['mobile']]],
                ["users.mobile" => $customer_res[0]['mobile']]
            ));

            if (!empty($customer_res[0]['fcm_id'])) {
                $fcmMsg = array(
                    'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Order status updated",
                    'body' => $customer_msg,
                    'type' => "order",
                );

                $fcm_ids[0][] = $customer_res[0]['fcm_id'];
                send_notification($fcmMsg, $fcm_ids, $fcmMsg);
            }
        }
    }

    function get_return_requests($limit = "", $offset = '', $sort = 'id', $order = 'DESC', $search = NULL, $where = NULL)
    {
        $multipleWhere = '';

        if (isset($search) and $search != '') {
            $multipleWhere = ['rr.`id`' => $search, 'oi.`order_id`' => $search, 'u.`username`' => $search, 'u.`email`' => $search, 'u.`mobile`' => $search, 'p.`name`' => $search, 'oi.`price`' => $search,];
        }

        $count_res = $this->db->select(' COUNT(rr.id) as `total` ')->join('users u', 'u.id=rr.user_id')->join('products p', 'p.id=rr.product_id')->join('order_items oi', 'oi.id=rr.order_item_id');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_where($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $request_count = $count_res->get('return_requests rr')->result_array();

        foreach ($request_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' rr.id,rr.remarks, oi.order_id, u.id as user_id,u.username as username ,p.name as product_name,oi.price,oi.discounted_price,oi.id as order_item_id,oi.quantity,oi.sub_total,rr.status')->join('users u', 'u.id=rr.user_id')->join('products p', 'p.id=rr.product_id')->join('order_items oi', 'oi.id=rr.order_item_id');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $offer_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('return_requests rr')->result_array();

        $bulkData = array();
        $bulkData['error'] = (empty($offer_search_res)) ? true : false;
        $bulkData['message'] = (empty($offer_search_res)) ? 'Return Request details does not exist' : 'Return Request details are retrieve successfully';
        $bulkData['total'] = (empty($offer_search_res)) ? 0 : $total;

        $rows = array();
        $tempRow = array();

        foreach ($offer_search_res as $row) {
            $row = output_escaping($row);

            $tempRow['id'] = $row['id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['user_name'] = $row['username'];
            $tempRow['order_id'] = $row['order_id'];
            $tempRow['order_item_id'] = $row['order_item_id'];
            $tempRow['product_name'] = $row['product_name'];
            $tempRow['price'] = $row['price'];
            $tempRow['discounted_price'] = $row['discounted_price'];
            $tempRow['quantity'] = $row['quantity'];
            $tempRow['sub_total'] = $row['sub_total'];
            $tempRow['status'] = $row['status'];
            $tempRow['remarks'] = $row['remarks'];
            $rows[] = $tempRow;
        }
        $bulkData['data'] = $rows;
        print_r(json_encode($bulkData));
    }

    function sync_existing_return_requests()
    {
        // Find all order_items with return_request_pending status that don't have return_requests records
        $pending_items = $this->db->select('oi.id, oi.user_id, oi.product_variant_id, oi.order_id, pv.product_id')
            ->from('order_items oi')
            ->join('product_variants pv', 'pv.id = oi.product_variant_id', 'left')
            ->where('oi.active_status', 'return_request_pending')
            ->where('oi.id NOT IN (SELECT order_item_id FROM return_requests WHERE order_item_id IS NOT NULL)', NULL, FALSE)
            ->get()
            ->result_array();

        $inserted = 0;
        foreach ($pending_items as $item) {
            $request_data = [
                'user_id' => $item['user_id'],
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['product_variant_id'],
                'order_id' => $item['order_id'],
                'order_item_id' => $item['id'],
                'status' => 0  // 0 = pending
            ];
            
            $this->db->insert('return_requests', $request_data);
            $inserted++;
        }

        return [
            'error' => false,
            'message' => "Synced {$inserted} existing return request(s)",
            'synced_count' => $inserted
        ];
    }
}
