<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Ticket_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }

    function add_ticket($data)
    {
        $data = escape_array($data);
        if (isset($data['edit_ticket_status'])) {
            $ticket_data = [
                'status' => $data['status'],
            ];
        } else {
            $ticket_data = [
                'ticket_type_id' => $data['ticket_type_id'],
                'user_id' => $data['user_id'],
                'subject' => $data['subject'],
                'email' => $data['email'],
                'description' => $data['description'],
                'status' => $data['status'],
            ];
        }
        if (isset($data['edit_ticket'])) {
            $this->db->set($ticket_data)->where('id', $data['edit_ticket'])->update('tickets');
        } else if (isset($data['edit_ticket_status'])) {
            $this->db->set($ticket_data)->where('id', $data['edit_ticket_status'])->update('tickets');
        } else {
            $this->db->insert('tickets', $ticket_data);
            $insert_id = $this->db->insert_id();
            if (!empty($insert_id)) {
                $user = fetch_users($data['user_id']);
                // Send notification to admin users
                $app_settings = get_settings('system_settings', true);
                $fcm_ids = array();

                // Get admin users from user_permissions table
                $user_roles = fetch_details("user_permissions", "", '*', '', '', '', '');
                if (!empty($user_roles)) {
                    foreach ($user_roles as $admin_user) {
                        $user_res = fetch_details('users', ['id' => $admin_user['user_id']], 'fcm_id');
                        if (!empty($user_res) && isset($user_res[0]['fcm_id']) && !empty($user_res[0]['fcm_id'])) {
                            $fcm_ids[][] = $user_res[0]['fcm_id'];
                        }
                    }
                }

               
                // Send FCM notification if admin FCM IDs found
                if (!empty($fcm_ids)) {


                    $fcm_subject = "New Ticket Raised";
                    $fcm_body = "Ticket #" . $insert_id . ": " . $data['subject'] . " (by " . $user[0]['username'] . ")";

                    $fcmMsg = array(
                        'title' => $fcm_subject,
                        'body' => $fcm_body,
                        'type' => "ticket",
                        'content_available' => 'true',
                        'priority' => 'high',
                        'ticket_id' => strval($insert_id),
                        'subject' => $data['subject'],
                    );

                    send_notification($fcmMsg, $fcm_ids, $fcmMsg);
                }
                return $insert_id;
            } else {
                return false;
            }
        }
    }
    function add_ticket_type($data)
    {
        $data = escape_array($data);

        $ticket_data = [
            'title' => $data['title'],
        ];
        if (isset($data['edit_ticket_type'])) {
            $this->db->set($ticket_data)->where('id', $data['edit_ticket_type'])->update('ticket_types');
        } else {
            $this->db->insert('ticket_types', $ticket_data);
            $insert_id = $this->db->insert_id();
            if (!empty($insert_id)) {

                return $insert_id;
            } else {
                return false;
            }
        }
    }

    function add_ticket_message($data)
    {
        $data = escape_array($data);

        $ticket_msg_data = [
            'user_type' => $data['user_type'],
            'user_id' => $data['user_id'],
            'ticket_id' => $data['ticket_id'],
            'message' => $data['message']
        ];
        if (isset($data['attachments']) && !empty($data['attachments'])) {
            $ticket_msg_data['attachments'] = json_encode($data['attachments']);
        }

        $this->db->insert('ticket_messages', $ticket_msg_data);
        $insert_id = $this->db->insert_id();
        if (!empty($insert_id)) {
            return $insert_id;
        } else {
            return false;
        }
    }

    function get_ticket_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 't.id';
        $order = 'ASC';
        $multipleWhere = '';

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "t.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                'u.id' => $search,
                'u.username' => $search,
                'u.email' => $search,
                'u.mobile' => $search,
                't.subject' => $search,
                't.email' => $search,
                't.description' => $search,
                'tty.title' => $search
            ];
        }

        $count_res = $this->db->select('COUNT(DISTINCT t.id) as `total`')->from('tickets t')->join('ticket_types tty', 'tty.id=t.ticket_type_id', 'left')->join('users u', 'u.id=t.user_id', 'left');

        if (isset($_GET['status']) && $_GET['status'] != null && $_GET['status'] != '') {
            $count_res->where("t.status", $_GET['status']);
        }

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_start();
            $first = true;
            foreach ($multipleWhere as $key => $value) {
                if ($first) {
                    $count_res->like($key, $value);
                    $first = false;
                } else {
                    $count_res->or_like($key, $value);
                }
            }
            $count_res->group_end();
        }

        $cat_count = $count_res->get()->result_array();
        $total = 0;
        if (!empty($cat_count)) {
            foreach ($cat_count as $row) {
                $total = $row['total'];
            }
        }

        $search_res = $this->db->select('t.*,tty.title,u.username')->from('tickets t')->join('ticket_types tty', 'tty.id=t.ticket_type_id', 'left')->join('users u', 'u.id=t.user_id', 'left');

        if (isset($_GET['status']) && $_GET['status'] != null && $_GET['status'] != '') {
            $search_res->where("t.status", $_GET['status']);
        }

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $first = true;
            foreach ($multipleWhere as $key => $value) {
                if ($first) {
                    $search_res->like($key, $value);
                    $first = false;
                } else {
                    $search_res->or_like($key, $value);
                }
            }
            $search_res->group_end();
        }

        $cat_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get()->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $status = "";
        $tempRow = array();
        foreach ($cat_search_res as $row) {
            $row = output_escaping($row);

            $operate = '<div class="dropdown">
            <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a href="javascript:void(0)" class="view_ticket dropdown-item" data-id=' . $row['id'] . ' data-username=' . $row['username'] . ' data-date_created=' . $row['date_created'] . ' data-subject=' . $row['subject'] . ' data-status=' . $row['status'] . ' data-ticket_type="' . $row['title'] . '" title="View" data-target="#ticket_modal" data-toggle="modal" ><i class="fa fa-eye"></i> View</a>
              <a href="javascript:void(0)" class="delete-brand dropdown-item" id="delete-ticket" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i> Delete</a></div>';

            $tempRow['id'] = $row['id'];
            $tempRow['ticket_type_id'] = ucwords(str_replace('_', ' ', $row['ticket_type_id']));
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['subject'] = $row['subject'];
            $tempRow['email'] = $row['email'];
            $tempRow['description'] = $row['description'];
            if ($row['status'] == "1") {
                $status = '<label class="badge bg-secondary">PENDING</label>';
            } else if ($row['status'] == "2") {
                $status = '<label class="badge bg-info">OPENED</label>';
            } else if ($row['status'] == "3") {
                $status = '<label class="badge bg-success">RESOLVED</label>';
            } else if ($row['status'] == "4") {
                $status = '<label class="badge bg-danger">CLOSED</label>';
            } else if ($row['status'] == "5") {
                $status = '<label class="badge bg-warning">REOPENED</label>';
            }
            $tempRow['status'] = $status;
            $tempRow['last_updated'] = ucwords(str_replace('_', ' ', $row['last_updated']));
            $tempRow['date_created'] = ucwords(str_replace('_', ' ', $row['date_created']));
            $tempRow['username'] = $row['username'];
            $tempRow['ticket_type'] = $row['title'];
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            // echo "<pre>";
            // print_r($rows);
            // die;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    function get_message_list($ticket_id = "", $user_id = "", $search = "", $offset = 0, $limit = 50, $sort = "tm.id", $order = "DESC", $data = array(), $msg_id = "")
    {

        $multipleWhere = '';

        if (isset($_GET['offset']) && $_GET['offset'] != 'undefined' && $_GET['offset'] != "")
            $offset = $_GET['offset'];
        if (isset($_GET['limit']) && $_GET['limit'] != '' && $_GET['limit'] != 'undefined')
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "tm.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                '`u.id`' => $search,
                '`u.username`' => $search,
                '`t.subject`' => $search,
                '`tm.message`' => $search
            ];
        }

        if (!empty($ticket_id)) {
            $where['tm.ticket_id'] = $ticket_id;
        }

        if (!empty($user_id)) {
            $where['tm.user_id'] = $user_id;
        }
        if (!empty($msg_id)) {
            $where['tm.id'] = $msg_id;
        }

        $count_res = $this->db->select(' COUNT(tm.id) as `total`')->join('tickets t', 't.id=tm.ticket_id', 'left')->join('users u', 'u.id=tm.user_id', 'left');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_where($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $cat_count = $count_res->get('ticket_messages tm')->result_array();
        foreach ($cat_count as $row) {
            $total = $row['total'];
        }
        $search_res = $this->db->select('tm.*,t.subject,u.username')->join('tickets t', 't.id=tm.ticket_id', 'left')->join('users u', 'u.id=tm.user_id', 'left');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $cat_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('ticket_messages tm')->result_array();

        $rows = $tempRow = $bulkData = array();
        $bulkData['total'] = $total;
        $bulkData['error'] = (empty($cat_search_res)) ? true : false;
        $bulkData['message'] = (empty($cat_search_res)) ? 'Ticket Message(s) does not exist' : 'Message retrieved successfully';
        $bulkData['total'] = (empty($cat_search_res)) ? 0 : $total;
        if (!empty($cat_search_res)) {
            $data = $this->config->item('type');
            foreach ($cat_search_res as $row) {
                $row = output_escaping($row);
                $tempRow['id'] = $row['id'];
                $tempRow['user_type'] = $row['user_type'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['ticket_id'] = $row['ticket_id'];
                $tempRow['message'] = (!empty($row['message'])) ? $row['message'] : "";
                $tempRow['name'] = $row['username'];
                if (!empty($row['attachments'])) {
                    $attachments = json_decode($row['attachments'], 1);
                    $counter = 0;
                    foreach ($attachments as $row1) {
                        $tmpRow['media'] = get_image_url($row1);
                        $file = new SplFileInfo($row1);
                        $ext = $file->getExtension();
                        if (in_array($ext, $data['image']['types'])) {
                            $tmpRow['type'] = "image";
                        } else if (in_array($ext, $data['video']['types'])) {
                            $tmpRow['type'] = "video";
                        } else if (in_array($ext, $data['document']['types'])) {
                            $tmpRow['type'] = "document";
                        } else if (in_array($ext, $data['archive']['types'])) {
                            $tmpRow['type'] = "archive";
                        }
                        $attachments[$counter] = $tmpRow;
                        $counter++;
                    }
                } else {
                    $attachments = array();
                }
                $tempRow['attachments'] = $attachments;
                $tempRow['subject'] = $row['subject'];
                $tempRow['last_updated'] = $row['last_updated'];
                $tempRow['date_created'] = $row['date_created'];
                $rows[] = $tempRow;
            }
            $bulkData['data'] = $rows;
        } else {
            $bulkData['data'] = [];
        }

        print_r(json_encode($bulkData));
    }

    function get_tickets($ticket_id = "", $ticket_type_id = "", $user_id = "", $status = "", $search = "", $offset = "", $limit = "1", $sort = "", $order = "")
    {

        $multipleWhere = '';
        $where = array();
        if (!empty($search)) {
            $multipleWhere = [
                '`u.id`' => $search,
                '`u.username`' => $search,
                '`u.email`' => $search,
                '`u.mobile`' => $search,
                '`t.subject`' => $search,
                '`t.email`' => $search,
                '`t.description`' => $search,
                '`tty.title`' => $search
            ];
        }
        if (!empty($ticket_id)) {
            $where['t.id'] = $ticket_id;
        }
        if (!empty($ticket_type_id)) {
            $where['t.ticket_type_id'] = $ticket_type_id;
        }
        if (!empty($user_id)) {
            $where['t.user_id'] = $user_id;
        }
        if (!empty($status)) {
            $where['t.status'] = $status;
        }
        $count_res = $this->db->select(' COUNT(u.id) as `total`')->join('ticket_types tty', 'tty.id=t.ticket_type_id', 'left')->join('users u', 'u.id=t.user_id', 'left');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_start();
            $count_res->or_like($multipleWhere);
            $count_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $cat_count = $count_res->get('tickets t')->result_array();
        foreach ($cat_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select('t.*,tty.title,u.username')->join('ticket_types tty', 'tty.id=t.ticket_type_id', 'left')->join('users u', 'u.id=t.user_id', 'left');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $cat_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('tickets t')->result_array();
        $rows = $tempRow = $bulkData = array();
        $bulkData['error'] = (empty($cat_search_res)) ? true : false;
        $bulkData['message'] = (empty($cat_search_res)) ? 'Ticket(s) does not exist' : 'Tickets retrieved successfully';
        $bulkData['total'] = (empty($cat_search_res)) ? 0 : $total;
        if (!empty($cat_search_res)) {
            foreach ($cat_search_res as $row) {
                $row = output_escaping($row);
                $tempRow['id'] = $row['id'];
                $tempRow['ticket_type_id'] = $row['ticket_type_id'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['subject'] = $row['subject'];
                $tempRow['email'] = $row['email'];
                $tempRow['description'] = $row['description'];
                $tempRow['status'] = $row['status'];
                $tempRow['last_updated'] = $row['last_updated'];
                $tempRow['date_created'] = $row['date_created'];
                $tempRow['name'] = $row['username'];
                $tempRow['ticket_type'] = $row['title'];
                $rows[] = $tempRow;
            }
            $bulkData['data'] = $rows;
        } else {
            $bulkData['data'] = [];
        }
        return $bulkData;
    }

    function get_messages($ticket_id = "", $user_id = "", $search = "", $offset = "", $limit = "", $sort = "", $order = "", $data = array(), $msg_id = "")
    {

        $multipleWhere = '';
        $where = array();
        if (!empty($search)) {
            $multipleWhere = [
                '`u.id`' => $search,
                '`u.username`' => $search,
                '`t.subject`' => $search,
                '`tm.message`' => $search
            ];
        }
        if (!empty($ticket_id)) {
            $where['tm.ticket_id'] = $ticket_id;
        }

        if (!empty($user_id)) {
            $where['tm.user_id'] = $user_id;
        }
        if (!empty($msg_id)) {
            $where['tm.id'] = $msg_id;
        }

        $count_res = $this->db->select(' COUNT(tm.id) as `total`')->join('tickets t', 't.id=tm.ticket_id', 'left')->join('users u', 'u.id=tm.user_id', 'left');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_start();
            $count_res->or_like($multipleWhere);
            $count_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $cat_count = $count_res->get('ticket_messages tm')->result_array();
        foreach ($cat_count as $row) {
            $total = $row['total'];
        }
        $search_res = $this->db->select('tm.*,t.subject,u.username')->join('tickets t', 't.id=tm.ticket_id', 'left')->join('users u', 'u.id=tm.user_id', 'left');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $cat_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('ticket_messages tm')->result_array();

        $rows = $tempRow = $bulkData = $tmpRow = array();
        $bulkData['error'] = (empty($cat_search_res)) ? true : false;
        $bulkData['message'] = (empty($cat_search_res)) ? 'Ticket Message(s) does not exist' : 'Message retrieved successfully';
        $bulkData['total'] = (empty($cat_search_res)) ? 0 : $total;
        if (!empty($cat_search_res)) {
            foreach ($cat_search_res as $row) {
                $row = output_escaping($row);
                $tempRow['id'] = $row['id'];
                $tempRow['user_type'] = $row['user_type'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['ticket_id'] = $row['ticket_id'];
                $tempRow['message'] = (!empty($row['message'])) ? $row['message'] : "";
                $tempRow['name'] = $row['username'];
                if (!empty($row['attachments'])) {
                    $attachments = json_decode($row['attachments'], 1);
                    $counter = 0;
                    foreach ($attachments as $row1) {
                        $tmpRow['media'] = get_image_url($row1);
                        $file = new SplFileInfo($row1);
                        $ext = $file->getExtension();
                        if (in_array($ext, $data['image']['types'])) {
                            $tmpRow['type'] = "image";
                        } else if (in_array($ext, $data['video']['types'])) {
                            $tmpRow['type'] = "video";
                        } else if (in_array($ext, $data['document']['types'])) {
                            $tmpRow['type'] = "document";
                        } else if (in_array($ext, $data['archive']['types'])) {
                            $tmpRow['type'] = "archive";
                        }
                        $attachments[$counter] = $tmpRow;
                        $counter++;
                    }
                } else {
                    $attachments = array();
                }
                $tempRow['attachments'] = $attachments;
                $tempRow['subject'] = $row['subject'];
                $tempRow['last_updated'] = $row['last_updated'];
                $tempRow['date_created'] = $row['date_created'];
                $rows[] = $tempRow;
            }
            $bulkData['data'] = $rows;
        } else {
            $bulkData['data'] = [];
        }
        return $bulkData;
    }

    function delete_ticket($ticket_id)
    {
        if (delete_details(['id' => $ticket_id], 'tickets') == TRUE) {
            if (delete_details(['ticket_id' => $ticket_id], 'ticket_messages') == TRUE) {
                return true;
            }
        } else {
            return false;
        }
    }

    function get_ticket_type_list()
    {
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

        $multipleWhere = [];

        if (!empty($_GET['search'])) {
            $search = $_GET['search'];
            $multipleWhere = [
                'id' => $search,
                'title' => $search
            ];
        }

        // Count Query
        $count_res = $this->db->select('COUNT(id) as total')->from('ticket_types');
        if (!empty($multipleWhere)) {
            $count_res->group_start();
            foreach ($multipleWhere as $key => $value) {
                $count_res->or_like($key, $value);
            }
            $count_res->group_end();
        }
        $cat_count = $count_res->get()->row_array();
        $total = isset($cat_count['total']) ? $cat_count['total'] : 0;

        // Data Query
        $this->db->select('*')->from('ticket_types');
        if (!empty($multipleWhere)) {
            $this->db->group_start();
            foreach ($multipleWhere as $key => $value) {
                $this->db->or_like($key, $value);
            }
            $this->db->group_end();
        }
        $this->db->order_by($sort, $order)->limit($limit, $offset);
        $cat_search_res = $this->db->get()->result_array();

        $rows = [];
        foreach ($cat_search_res as $row) {
            $row = output_escaping($row);

            $operate = '<div class="dropdown">
            <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
              <a href="javascript:void(0)" class="edit_btn dropdown-item" title="Edit" data-id="' . $row['id'] . '" data-url="admin/tickets/manage_ticket_types/"><i class="fa fa-pen"></i> Edit</a>
              <a href="javascript:void(0)" class="delete-ticket-type dropdown-item" data-id="' . $row['id'] . '" title="Delete"><i class="fa fa-trash"></i> Delete</a>
            </div>
        </div>';

            $rows[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'date_created' => $row['date_created'],
                'operate' => $operate,
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['total' => $total, 'rows' => $rows]));
    }
}
