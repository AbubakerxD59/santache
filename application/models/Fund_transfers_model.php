<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Fund_transfers_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }

    function set_fund_transfer($delivery_boy_id, $amount, $opening_bal, $status = 'success', $message = "")
    {
        $t = &get_instance();
        $res = $t->db->select('balance')->where('id', $delivery_boy_id)->get('users')->result_array();

        $data = [
            'delivery_boy_id' => $delivery_boy_id,
            'opening_balance' => $opening_bal,
            'closing_balance' => $opening_bal - $amount,
            'amount' => $amount,
            'status' => $status,
            'message' => $message
        ];
        $data = escape_array($data);
        $t->db->insert('fund_transfers', $data);
    }

    function get_fund_transfers_list($user_id = '')
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = [];
        $where = [];

        // Get parameters
        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }
        if (isset($_GET['sort'])) {
            $sort = ($_GET['sort'] == 'id') ? "id" : $_GET['sort'];
        }
        if (isset($_GET['order'])) {
            $order = $_GET['order'];
        }

        // Prepare search filters
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                'fund_transfers.id' => $search,
                'users.username' => $search,
                'users.mobile' => $search,
                'fund_transfers.message' => $search,
                'fund_transfers.opening_balance' => $search,
                'fund_transfers.closing_balance' => $search,
                'fund_transfers.status' => $search,
                'fund_transfers.amount' => $search
            ];
        }

        // Delivery boy condition
        if ($user_id != '' && is_numeric($user_id)) {
            $where = ['fund_transfers.delivery_boy_id' => trim($user_id)];
        }

        // Count query
        $count_res = $this->db->select('COUNT(fund_transfers.id) as total')
            ->join('users', 'fund_transfers.delivery_boy_id = users.id', 'left');

        if (!empty($multipleWhere)) {
            $count_res->group_start();
            $count_res->or_like($multipleWhere);
            $count_res->group_end();
        }

        if (!empty($where)) {
            $count_res->where($where);
        }

        $transfers_count_result = $count_res->get('fund_transfers')->result_array();
        $total = (!empty($transfers_count_result)) ? $transfers_count_result[0]['total'] : 0;

        // Data query
        $search_res = $this->db->select('fund_transfers.*, users.username as name, users.mobile as mobile')
            ->join('users', 'fund_transfers.delivery_boy_id = users.id', 'left');

        if (!empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }

        if (!empty($where)) {
            $search_res->where($where);
        }

        $transfers_res = $search_res->order_by($sort, $order)
            ->limit($limit, $offset)
            ->get('fund_transfers')
            ->result_array();

        // Build response
        $bulkData = [];
        $bulkData['total'] = $total;
        $rows = [];

        foreach ($transfers_res as $row) {
            $row = output_escaping($row);
            $tempRow = [];

            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['name'];
            $tempRow['mobile'] = (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0)
                ? str_repeat("X", max(strlen($row['mobile']) - 3, 0)) . substr($row['mobile'], -3)
                : $row['mobile'];
            $tempRow['opening_balance'] = empty($row['opening_balance']) ? "0" : number_format($row['opening_balance'], 2);
            $tempRow['closing_balance'] = empty($row['closing_balance']) ? "0" : number_format($row['closing_balance'], 2);
            $tempRow['amount'] = empty($row['amount']) ? "0" : number_format($row['amount'], 2);
            $statusText = ucfirst(str_replace('_', ' ', strtolower($row['status'])));

            switch (strtolower($row['status'])) {
                case 'success':
                    $tempRow['status'] = '<span class="badge bg-success">' . $statusText . '</span>';
                    break;

                case 'pending':
                    $tempRow['status'] = '<span class="badge bg-warning">' . $statusText . '</span>';
                    break;

                case 'failed':
                    $tempRow['status'] = '<span class="badge bg-danger">' . $statusText . '</span>';
                    break;

                case 'processing':
                    $tempRow['status'] = '<span class="badge bg-info">' . $statusText . '</span>';
                    break;

                default:
                    $tempRow['status'] = '<span class="badge bg-secondary">' . $statusText . '</span>';
                    break;
            }

            $tempRow['message'] = $row['message'];
            $tempRow['date'] = date('d-m-Y', strtotime($row['date_created']));

            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;

        // Output JSON
        echo json_encode($bulkData);
    }

}
