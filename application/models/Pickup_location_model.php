<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Pickup_location_model extends CI_Model
{

    function add_pickup_location($data)
    {
        $data = escape_array($data);
        $pickup_location_data = [
            'pickup_location' => $data['pickup_location'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'address_2' => $data['address2'],
            'city' => $data['city'],
            'state' => $data['state'],
            'country' => $data['country'],
            'pin_code' => $data['pincode'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ];
        if (isset($data['edit_pickup_location'])) {
            $this->db->set($pickup_location_data)->where('id', $data['edit_pickup_location'])->update('pickup_locations');
        } else {
            $this->db->insert('pickup_locations', $pickup_location_data);

            //    send add_pickup_location request in shiprocket

            $this->load->library(['shiprocket']);
            $this->shiprocket->add_pickup_location($pickup_location_data);
        }
    }

    public function get_list($table, $from_app = false)
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
            if ($table == 'pickup_locations') {
                $multipleWhere = ['pickup_locations.id' => $search, 'pickup_locations.pickup_location' => $search, 'pickup_locations.name' => $search, 'pickup_locations.email' => $search, 'pickup_locations.phone' => $search];
            }
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');



        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $city_count = $count_res->get($table)->result_array();

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

        $city_search_res = $search_res->order_by($sort, "asc")->limit($limit, $offset)->get($table)->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $url = 'manage_' . $table;
        foreach ($city_search_res as $row) {
            $row = output_escaping($row);

            if (!$from_app) {
                $operate = '<div class="dropdown">
    <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-ellipsis-v"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
                if (has_permissions('update', 'pickup_location')) {
                    $operate .= '<a href="javascript:void(0)" class="edit_btn dropdown-item" title="Edit" 
        data-id="' . $row['id'] . '" 
        data-url="admin/Pickup_location/' . $url . '">
        <i class="fa fa-pen"></i> Edit</a>';
                }

                if (has_permissions('delete', 'pickup_location')) {
                    $operate .= '<a href="javascript:void(0)" class="dropdown-item" id="delete-location" 
        data-id="' . $row['id'] . '" 
        data-table="' . $table . '" 
        title="Delete">
        <i class="fa fa-trash"></i> Delete</a>';
                }

                $operate .= '</div></div>';

                if ($row['status'] == '1') {
                    $verify = '<a class="badge bg-success text-white"></a>';
                    $verify .= '<a class="form-switch update_active_status" data-table="pickup_locations" title="Deactivate" href="javascript:void(0)" 
            data-id="' . $row['id'] . '" 
            data-status="' . $row['status'] . '">
            <input class="form-check-input" type="checkbox" role="switch" checked>
        </a>';
                } else {
                    $verify = '<a class="badge bg-danger text-white"></a>';
                    $verify .= '<a class="form-switch update_active_status" data-table="pickup_locations" title="Activate" href="javascript:void(0)" 
            data-id="' . $row['id'] . '" 
            data-status="' . $row['status'] . '">
            <input class="form-check-input" type="checkbox" role="switch">
        </a>';
                }
            }

            $tempRow['id'] = $row['id'];
            $tempRow['pickup_location'] = $row['pickup_location'];
            $tempRow['name'] = $row['name'];
            $tempRow['email'] = $row['email'];
            $tempRow['phone'] = $row['phone'];
            $tempRow['address'] = $row['address'];
            $tempRow['address2'] = $row['address_2'];
            $tempRow['city'] = $row['city'];
            $tempRow['state'] = $row['state'];
            $tempRow['country'] = $row['country'];
            $tempRow['pin_code'] = $row['pin_code'];

            if (!$from_app) {
                $tempRow['verified'] = $verify;
                $tempRow['operate'] = $operate;
            } else {
                $tempRow['status'] = $row['status'];
            }

            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        if ($from_app == true) {
            return $bulkData;
        } else {
            print_r(json_encode($bulkData));
        }
    }
}
