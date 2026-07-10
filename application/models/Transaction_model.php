<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Transaction_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }

    function add_transaction($data)
    {
        $this->load->model('Order_model');
        $data = escape_array($data);
        /* transaction_type : transaction - for payment transactions | wallet - for wallet transactions  */
        $transaction_type = (!isset($data['transaction_type']) || empty($data['transaction_type'])) ? 'transaction' : $data['transaction_type'];
        $trans_data = [
            'transaction_type' => $transaction_type,
            'user_id' => $data['user_id'],
            'order_id' => $data['order_id'],
            'order_item_id' => $data['order_item_id'],
            'type' => strtolower($data['type']),
            'txn_id' => $data['txn_id'],
            'transaction_date' => (isset($data['transaction_date']) && !empty($data['transaction_date'])) ? date("Y-m-d H:i:s", strtotime($data['transaction_date'])) : date("Y-m-d H:i:s"),
            'amount' => $data['amount'],
            'status' => $data['status'],
            'message' => $data['message'],
        ];
        $this->db->insert('transactions', $trans_data);
    }

    function update_transaction($data, $txn_id)
    {
        $this->load->model('Order_model');
        $data = escape_array($data);
        /* transaction_type : transaction - for payment transactions | wallet - for wallet transactions  */
        $trans_data = [
            'status' => $data['status'],
            'message' => $data['message'],
        ];
        $this->db->where('txn_id', $txn_id);
        $this->db->update('transactions', $trans_data);
    }

function get_transactions_list($user_id = '')
{
    $offset = 0;
    $limit = 10;
    $sort = 'id';
    $order = 'ASC';
    $multipleWhere = '';
    $where = [];

    /* ================= BASIC FILTERS ================= */

    if (isset($_GET['transaction_type']) && $_GET['transaction_type'] != '') {
        $where['transactions.transaction_type'] = $_GET['transaction_type'];
    }

    if (isset($_GET['status']) && $_GET['status'] != '') {
        $where['transactions.status'] = $_GET['status'];
    }

    if (isset($_GET['type']) && $_GET['type'] != '') {
        $where['transactions.type'] = $_GET['type'];
    }

    /* ================= PAGINATION ================= */

    if (isset($_GET['offset']))
        $offset = $_GET['offset'];

    if (isset($_GET['limit']))
        $limit = $_GET['limit'];

    /* ================= SORTING ================= */

    if (isset($_GET['sort'])) {
        if ($_GET['sort'] == 'id') {
            $sort = "id";
        } else {
            $sort = $this->db->escape($_GET['sort']);
        }
    }

    if (isset($_GET['order']))
        $order = $_GET['order'];

    /* ================= SEARCH ================= */

    if (isset($_GET['search']) && $_GET['search'] != '') {
        $search = $_GET['search'];
        $multipleWhere = [
            '`transactions.id`' => $search,
            '`transactions.amount`' => $search,
            '`transactions.date_created`' => $search,
            '`transactions.type`' => $search,
            '`transactions.order_id`' => $search,
            '`transactions.txn_id`' => $search,
            'users.username' => $search,
            'users.mobile' => $search,
            'users.email' => $search,
            'transactions.status' => $search
        ];
    }

    /* ================= USER FILTER ================= */

    if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
        $where['users.id'] = $_GET['user_id'];
    }

    if (!empty($user_id)) {
        $user_where = ['users.id' => $user_id];
    }

    /* ================= COUNT QUERY ================= */

    $count_res = $this->db
        ->select(' COUNT(transactions.id) as `total` ')
        ->join('users', 'transactions.user_id = users.id', 'left');

    if (!empty($multipleWhere)) {
        $this->db->group_start();
        $count_res->or_like($multipleWhere);
        $this->db->group_end();
    }

    if (!empty($where)) {
        $count_res->where($where);
    }

    if (!empty($user_where)) {
        $count_res->where($user_where);
    }

    $txn_count = $count_res->get('transactions')->result_array();

    foreach ($txn_count as $row) {
        $total = $row['total'];
    }

    /* ================= DATA QUERY ================= */

    $search_res = $this->db->select('transactions.*, users.username as name');

    if (!empty($multipleWhere)) {
        $this->db->group_start();
        $search_res->or_like($multipleWhere);
        $this->db->group_end();
    }

    if (!empty($where)) {
        $search_res->where($where);
    }

    if (!empty($user_where)) {
        $search_res->where($user_where);
    }

    $search_res->join('users', 'transactions.user_id = users.id', 'left');

    $txn_search_res = $search_res
        ->order_by($sort, $order)
        ->limit($limit, $offset)
        ->get('transactions')
        ->result_array();

    /* ================= RESPONSE ================= */

    $bulkData = [];
    $bulkData['total'] = $total;
    $rows = [];

    foreach ($txn_search_res as $row) {

        $row = output_escaping($row);

        $operate = ($row['type'] == 'bank_transfer')
            ? '<div class="dropdown">
                <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></a>
                <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="edit_transaction dropdown-item"
                       data-id="'.$row['id'].'"
                       data-txn_id="'.$row['txn_id'].'"
                       data-status="'.$row['status'].'"
                       data-message="'.$row['message'].'"
                       data-toggle="modal"
                       data-target="#transaction_modal">
                       <i class="fa fa-pen"></i> Edit
                    </a>
                </div>
              </div>'
            : '';

        switch (strtolower($row['status'])) {
            case 'success':
                $statusBadge = '<span class="badge bg-success text-white">Success</span>';
                break;
            case 'pending':
                $statusBadge = '<span class="badge bg-warning text-white">Pending</span>';
                break;
            case 'failed':
                $statusBadge = '<span class="badge bg-danger text-white">Failed</span>';
                break;
            case 'processing':
                $statusBadge = '<span class="badge bg-info text-white">Processing</span>';
                break;
            default:
                $statusBadge = '<span class="badge bg-secondary text-white">'.ucfirst($row['status']).'</span>';
        }

        $rows[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'order_id' => $row['order_id'],
            'type' => $row['type'],
            'txn_id' => $row['txn_id'],
            'payu_txn_id' => $row['payu_txn_id'],
            'amount' => $row['amount'],
            'message' => $row['message'],
            'txn_date' => date('d-m-Y', strtotime($row['transaction_date'])),
            'date' => $row['date_created'],
            'status' => $statusBadge,
            'operate' => $operate,
        ];
    }

    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}


    function get_withdrawal_transactions_list($user_id = '')
    {
        $multipleWhere = '';
        $sort = 'id';
        $order = 'DESC';
        $offset = 0;
        $limit = 10;

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];
        if (isset($_GET['sort']))
            $sort = $_GET['sort'];
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (!empty($user_id)) {
            $user_where = ['user_id' => $user_id];
        }
        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['`payment_requests.id`' => $search, '`payment_requests.payment_address`' => $search, '`payment_requests.amount_requested`' => $search, '`payment_requests.status`' => $search];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');
        if (isset($user_where) && !empty($user_where)) {
            $count_res->where($user_where);
        }
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_Start();
            $count_res->or_like($multipleWhere);
            $this->db->group_End();
        }

        $txn_count = $count_res->get('payment_requests')->result_array();
        foreach ($txn_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($user_where) && !empty($user_where)) {
            $search_res->where($user_where);
        }
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_Start();
            $search_res->or_like($multipleWhere);
            $this->db->group_End();
        }

        $txn_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('payment_requests')->result_array();


        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();


        $username = fetch_details('users', ['id' => $user_id], 'username');
        foreach ($txn_search_res as $row) {
            $row = output_escaping($row);

            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $username[0]['username'];
            $tempRow['payment_address'] = $row['payment_address'];
            $tempRow['amount_requested'] = $row['amount_requested'];
            $status = [
                '0' => '<span class="badge bg-secondary">Pending</span>',
                '1' => '<span class="badge bg-success">Approved</span>',
                '2' => '<span class="badge bg-danger">Rejected</span>',
            ];
            $tempRow['status'] = $status[$row['status']];
            $tempRow['date_created'] = $row['date_created'];
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }


    function get_transactions($id = '', $user_id = '', $transaction_type = '', $type = '', $search = '', $offset = '0', $limit = '25', $sort = 'id', $order = 'DESC')
    {
        $where = $multiple_where = [];

        $where['status'] = 'success';

        if (!empty($user_id)) {
            $where['user_id'] = $user_id;
        }

        if ($transaction_type != '') {
            $where['transaction_type'] = $transaction_type;
        }

        if ($type != '') {
            $where['type'] = $type;
        }

        if ($id !== '') {
            $where['id'] = $id;
        }

        if ($search !== '') {
            $multiple_where = [
                'id' => $search,
                'transaction_type' => $search,
                'type' => $search,
                'order_id' => $search,
                'txn_id' => $search,
                'amount' => $search,
                'message' => $search,
                'transaction_date' => $search,
                'date_created' => $search,
            ];
        }

        $count_sql = $this->db->select('COUNT(id) as total, SUM(amount) as amount_total')->from('transactions');
        if (!empty($where)) {
            $count_sql->where($where);
        }
        if (!empty($multiple_where)) {
            $count_sql->group_start();
            $count_sql->or_like($multiple_where);
            $count_sql->group_end();
        }
        $count = $count_sql->get()->row_array();
        $total = isset($count['total']) ? $count['total'] : 0;
        $amount_total = isset($count['amount_total']) ? (float)$count['amount_total'] : 0.00;

        $transactions_sql = $this->db->select('*')->from('transactions');
        if (!empty($where)) {
            $transactions_sql->where($where);
        }
        if (!empty($multiple_where)) {
            $transactions_sql->group_start();
            $transactions_sql->or_like($multiple_where);
            $transactions_sql->group_end();
        }
        if ($limit != '' && $offset !== '') {
            $transactions_sql->limit($limit, $offset);
        }
        $transactions_sql->order_by($sort, $order);
        $q = $transactions_sql->get();

        $transactions['data'] = $q->result_array();

        if (!empty($transactions['data'])) {
            foreach ($transactions['data'] as &$txn) {
                $txn['payu_txn_id'] = $txn['payu_txn_id'] ?? "";
                $txn['currency_code'] = $txn['currency_code'] ?? "";
                $txn['payer_email'] = $txn['payer_email'] ?? "";
            }
        }

        $transactions['total'] = $total;

        return $transactions;
    }


    function edit_transactions($data)
    {
        $data = escape_array($data);

        $t_data = [
            'id' => $data['id'],
            'status' => $data['status'],
            'txn_id' => $data['txn_id'],
            'message' => $data['message'],
        ];

        if ($this->db->set($t_data)->where('id', $data['id'])->update('transactions')) {
            return false;
        } else {
            return true;
        }
    }
}
