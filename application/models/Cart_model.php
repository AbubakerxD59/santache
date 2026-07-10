<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Cart_model extends CI_Model
{
    function add_to_cart($data, $check_status = TRUE)
    {
        $data = escape_array($data);
        $product_variant_id = explode(',', $data['product_variant_id'] ?? '');
        $qty = explode(',', $data['qty'] ?? '');
        if ($check_status == TRUE) {
            $check_current_stock_status = validate_stock($product_variant_id, $qty);
            if (!empty($check_current_stock_status) && $check_current_stock_status['error'] == true) {
                $check_current_stock_status['csrfName'] = $this->security->get_csrf_token_name();
                $check_current_stock_status['csrfHash'] = $this->security->get_csrf_hash();
                $response['error'] = true;
                $response['message'] = $check_current_stock_status['message'];
                return $response;
            }
        }
        for ($i = 0; $i < count($product_variant_id); $i++) {
            $cart_data = [
                'user_id' => $data['user_id'],
                'product_variant_id' => $product_variant_id[$i],
                'qty' => $qty[$i],
                'is_saved_for_later' => (isset($data['is_saved_for_later']) && !empty($data['is_saved_for_later']) && $data['is_saved_for_later'] == '1') ? $data['is_saved_for_later'] : '0',
            ];
            if (isset($data['is_buy_now']) && $data['is_buy_now'] == '1') {
                // Update all other cart items to 'is_saved_for_later' = 1
                $this->db->set('is_saved_for_later', '1')
                    ->where('user_id', $data['user_id'])
                    ->update('cart');

                // Add the "Buy Now" product to the cart with 'is_saved_for_later' = 0
                $cart_data['is_saved_for_later'] = '0';
                if ($qty[$i] == 0) {
                    $this->remove_from_cart($cart_data);
                } else {
                    if ($this->db->select('*')->where(['user_id' => $data['user_id'], 'product_variant_id' => $product_variant_id[$i]])->get('cart')->num_rows() > 0) {
                        $this->db->set($cart_data)->where(['user_id' => $data['user_id'], 'product_variant_id' => $product_variant_id[$i]])->update('cart');
                    } else {
                        $this->db->insert('cart', $cart_data);
                    }
                }

                // Break the loop after adding the "Buy Now" product
                break;
            } else {
                if ($qty[$i] == 0) {
                    $this->remove_from_cart($cart_data);
                } else {
                    if ($this->db->select('*')->where(['user_id' => $data['user_id'], 'product_variant_id' => $product_variant_id[$i]])->get('cart')->num_rows() > 0) {
                        $this->db->set($cart_data)->where(['user_id' => $data['user_id'], 'product_variant_id' => $product_variant_id[$i]])->update('cart');
                    } else {
                        $this->db->insert('cart', $cart_data);
                    }
                }
            }
        }
        return false;
    }

    function remove_from_cart($data)
    {
        if (isset($data['user_id']) && !empty($data['user_id'])) {
            $this->db->where('user_id', $data['user_id']);
            if (isset($data['product_variant_id'][0]) && !empty($data['product_variant_id'][0])) {
                $product_variant_id = explode(',', $data['product_variant_id'] ?? '');
                $this->db->where_in('product_variant_id', $product_variant_id);
            }
            return $this->db->delete('cart');
        } else {
            return false;
        }
    }

    function get_user_cart($user_id, $is_saved_for_later = 0, $product_variant_id = '')
{
    $q = $this->db->join('product_variants pv', 'pv.id=c.product_variant_id')
        ->join('products p', 'p.id=pv.product_id')
        ->join('taxes tax', 'FIND_IN_SET(tax.id, p.tax)', 'LEFT') // Adjusted to handle multiple tax IDs
        ->where(['user_id' => $user_id, 'p.status' => '1', 'pv.status' => 1, 'qty !=' => '0', 'is_saved_for_later' => $is_saved_for_later]);

    if (!empty($product_variant_id)) {
        $q->where('c.product_variant_id', $product_variant_id);
    }

    $res =  $q->select('c.*,p.is_prices_inclusive_tax,p.name,p.id,p.image,p.short_description,p.minimum_order_quantity,p.shipping_method,p.pickup_location,p.is_on_sale,p.type,p.sale_discount,
        p.quantity_step_size,p.total_allowed_quantity,pv.price,pv.weight,pv.special_price,pv.id as product_variant_id, GROUP_CONCAT(tax.percentage) as tax_percentages')
        ->order_by('c.id', 'DESC')
        ->group_by('c.id') // Grouping by cart item to handle multiple tax percentages
        ->get('cart c')
        ->result_array();

    if (!empty($res)) {
        $res = array_map(function ($d) {
            // Check for flash sale
            $pid = $d['id'];
            $sale_dis = exists_in_flash_sale($pid);
            if (!empty($sale_dis)) {
                foreach ($sale_dis as $sale) {
                    $sale_amt = get_flash_sale_price($d['price'], $sale['discount']);
                    $d['special_price'] = $sale_amt;
                }
            }
            // End

            $d['special_price'] = $d['special_price'] != '' && $d['special_price'] != null && $d['special_price'] > 0 && $d['special_price'] < $d['price'] ? $d['special_price'] : $d['price'];

            // Calculating total tax percentage
            $tax_percentages = isset($d['tax_percentages']) ? explode(',', $d['tax_percentages']) : [];
            $total_tax_percentage = array_sum($tax_percentages);

            // Calculating tax amounts
            if ((isset($d['is_prices_inclusive_tax']) && $d['is_prices_inclusive_tax'] == 0) || (!isset($d['is_prices_inclusive_tax'])) && $total_tax_percentage > 0) {
                $price_tax_amount = $d['price'] * ($total_tax_percentage / 100);
                $special_price_tax_amount = $d['special_price'] * ($total_tax_percentage / 100);
            } else {
                $price_tax_amount = 0;
                $special_price_tax_amount = 0;
            }

            $d['price'] = $d['price'] + $price_tax_amount;
            $d['special_price'] = $d['special_price'] + $special_price_tax_amount;

            $d['net_amount'] = $d['special_price'] - $special_price_tax_amount; // Adjusted calculation
            $d['minimum_order_quantity'] = isset($d['minimum_order_quantity']) && !empty($d['minimum_order_quantity']) ? $d['minimum_order_quantity'] : 1;
            $d['tax_percentage'] = $total_tax_percentage;
            $d['tax_amount'] = $special_price_tax_amount;
            $d['quantity_step_size'] = isset($d['quantity_step_size']) && !empty($d['quantity_step_size']) ? $d['quantity_step_size'] : 1;
            $d['total_allowed_quantity'] = isset($d['total_allowed_quantity']) && !empty($d['total_allowed_quantity']) ? $d['total_allowed_quantity'] : '';
            $d['product_variants'] = get_variants_values_by_id($d['product_variant_id']);
            
            return $d;
        }, $res);
    }

    return $res;
}

    function cart_item_remainder()
    {
        $firebase_project_id = get_settings('firebase_project_id');
        $service_account_file = get_settings('service_account_file');

        $time = date('Y-m-d H:i:s');
        $currentTime = strtotime($time);
        
        $this->db->select('*');
        $this->db->from('cart');
        $this->db->where('notification_sended', '0');
        $this->db->where('is_saved_for_later', '0');
        $this->db->group_by('user_id');
        $data = $this->db->get()->result_array();

        foreach ($data as $cart_item) {
            $fcm_ids = [];

            $timeDifference = $currentTime - $cart_item['added_timestamp'];

            if ($timeDifference >= 2*3600) {
                $user_data = fetch_details('users', ['id' => $cart_item['user_id']], 'fcm_id');
                foreach ($user_data as $user_fcm) {
                    $fcm_ids[] = $user_fcm['fcm_id'];
                }
            }
            $registrationIDs_chunks[0] = $fcm_ids;
            $fcmMsg = array(
                'title' => "👋 Your Cart Misses You!",
                'body' => "Come back and complete your purchase. Great deals await! 🎉",
                'type' => "cart",
            );
            if (isset($firebase_project_id) && isset($service_account_file) && !empty($firebase_project_id) && !empty($service_account_file)) {
                $fcmFields = send_notification('', $registrationIDs_chunks, $fcmMsg);

                $this->db->set('notification_sended', 1);
                $this->db->where('user_id',  $cart_item['user_id']);
                $this->db->update('cart');
            }
        }
        return $fcmFields;
    }

}
