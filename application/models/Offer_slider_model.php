<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Offer_slider_model extends CI_Model
{
    function add_offer_slider($data)
    {
        $data = escape_array($data);
        $offer_data = [
            'offer_ids' => (isset($data['offer_ids']) && !empty($data['offer_ids'])) ? implode(',', $data['offer_ids']) : '',
            'style' => $data['style'],
        ];

        if (isset($data['edit_offer_slider'])) {
            $this->db->set($offer_data)->where('id', $data['edit_offer_slider'])->update('offer_sliders');
        } else {
            $this->db->insert('offer_sliders', $offer_data);
        }
    }
    public function get_offer_slider_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'desc';
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
            $multipleWhere = ['id' => $search, 'style' => $search, 'offer_ids' => $search];
        }
        $type_filter = '';
        if (isset($_GET['type']) && !empty($_GET['type'])) {
            $type_filter = $_GET['type'];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $city_count = $count_res->get('offer_sliders')->result_array();

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

        $city_search_res = $search_res->order_by($sort, "asc")->limit($limit, $offset)->get('offer_sliders')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($city_search_res as $row) {
            $row = output_escaping($row);

            $offer_ids = explode(',', $row['offer_ids']);
            $offer_details_html = '';
            
            if (!empty($row['offer_ids'])) {
                $this->db->select('offers.*, categories.name as category_name, products.name as product_name, brands.name as brand_name');
                $this->db->from('offers');
                $this->db->join('categories', 'categories.id = offers.type_id AND offers.type = "categories"', 'left');
                $this->db->join('products', 'products.id = offers.type_id AND offers.type = "products"', 'left');
                $this->db->join('brands', 'brands.id = offers.type_id AND offers.type = "brand"', 'left');
                $this->db->where_in('offers.id', $offer_ids);
                
                if (!empty($type_filter)) {
                    $this->db->where('offers.type', $type_filter);
                }
                
                $offers = $this->db->get()->result_array();
                
                if (!empty($offers)) {
                    foreach ($offers as $offer) {
                        $image = base_url($offer['image']);
                        $type = ucfirst($offer['type']);
                        $name = '';
                        
                        if ($offer['type'] == 'categories' && !empty($offer['category_name'])) {
                            $name = $offer['category_name'];
                        } elseif ($offer['type'] == 'products' && !empty($offer['product_name'])) {
                            $name = $offer['product_name'];
                        } elseif ($offer['type'] == 'brand' && !empty($offer['brand_name'])) {
                            $name = $offer['brand_name'];
                        } elseif ($offer['type'] == 'offer_url') {
                            $name = 'Offer URL';
                        } elseif ($offer['type'] == 'all_products') {
                            $name = 'All Products';
                        }
                        
                        $discount = '';
                        if (!empty($offer['min_discount']) || !empty($offer['max_discount'])) {
                            $min = !empty($offer['min_discount']) ? $offer['min_discount'] : 0;
                            $max = !empty($offer['max_discount']) ? $offer['max_discount'] : 0;
                            $discount = '<br><small>Discount: ' . $min . '% - ' . $max . '%</small>';
                        }
                        
                        $offer_details_html .= '<div class="mb-2 p-2 border rounded">
                            <img src="' . $image . '" style="width: 50px; height: 50px; object-fit: cover;" class="me-2">
                            <strong>' . $type . ':</strong> ' . $name . $discount . '
                        </div>';
                    }
                } else {
                    $offer_details_html = '<span class="text-muted">No matching offers</span>';
                }
            }
            
            if (!empty($type_filter) && empty($offers)) {
                continue;
            }

            $operate = '<div class="dropdown">
            <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a href="javascript:void(0)" class="edit_btn dropdown-item" title="Edit" data-id="' . $row['id'] . '" data-url="admin/offer_slider/"><i class="fa fa-pen"></i> Edit</a>
              <a href="javascript:void(0)" class=" dropdown-item" id="delete-offer-slider" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i> Delete</a></div>';
            
            $tempRow['id'] = $row['id'];
            $tempRow['style'] = ucfirst(str_replace('_', ' ', $row['style']));
            $tempRow['offer_details'] = $offer_details_html;
            $tempRow['date'] = date('d-m-Y', strtotime($row['date_added']));
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    function get_offer_data($search_term = "")
    {
        $where = " (id like '%" . $search_term . "%') or type like '%" . $search_term . "%' ";
        // Fetch offers
        $this->db->select('*');
        $this->db->where($where);
        $fetched_records = $this->db->get('offers');
        $offers = $fetched_records->result_array();

        // 
        $this->db->select('count(`id`) as total');
        $this->db->where($where);
        $fetched_records = $this->db->get('offers');
        $offers_count = $fetched_records->result_array();

        // Initialize Array with fetched data
        $data = array();
        $data['total'] = $offers_count[0]['total'];

        foreach ($offers as $offer) {
            $image = base_url($offer['image']);
            $min_discount = isset($offer['min_discount']) && !empty($offer['min_discount']) ? $offer['min_discount'] : "0";
            $max_discount = isset($offer['max_discount']) && !empty($offer['max_discount']) ? $offer['max_discount'] : "0";
            $html =  '<div class="mx-auto product-image"><img src="' . $image . '" class="img-fluid rounded"></div>';
            $data['data'][] = array(
                "id" => $offer['id'],
                "type" => $offer['type'],
                "min_discount" => $min_discount,
                "max_discount" => $max_discount,
                "image" => $image,
                "text" => $html
            );
        }
        return $data;
    }
}
