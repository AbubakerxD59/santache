<?php

use function PHPSTORM_META\type;

error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['sms_helper']);
    }

    public function update_order($set, $where, $isjson = false, $table = 'orders')
    {
        $set = escape_array($set);


        if ($isjson == true) {
            $field = array_keys($set); // active_status
            $current_status = $set[$field[0]]; //processed

            $res = fetch_details($table, $where, '*');

            $settings = get_settings('system_settings', true);
            $local_pickup = isset($settings['local_pickup']) && ($settings['local_pickup'] != '') ? $settings['local_pickup'] : '0';

            if ($table == 'orders') {
                $pickup_status = ($local_pickup == 1 && $res[0]['is_local_pickup'] == 1) ? 'ready_to_pickup' : 'shipped';
            } elseif ($table == 'order_items') {
                $res = fetch_details('order_items', $where, '*');
                $order_res = fetch_details('orders', 'id=' . $res[0]['order_id'], '*');
                $pickup_status = ($local_pickup == 1 && $order_res[0]['is_local_pickup'] == 1) ? 'ready_to_pickup' : 'shipped';
            } else {
                $pickup_status = 'shipped';
            }

            if ($set['status'] != 'return_request_decline') {
                $priority_status = [
                    'awaiting' => 0,
                    'received' => 1,
                    'processed' => 2,
                    $pickup_status => 3,
                    'delivered' => 4,
                    'return_request_pending' => 5,
                    'return_request_approved' => 6,
                    'cancelled' => 7,
                    'returned' => 8,
                ];
            } else {
                $priority_status = [
                    'awaiting' => 0,
                    'received' => 1,
                    'processed' => 2,
                    $pickup_status => 3,
                    'delivered' => 4,
                    'return_request_pending' => 5,
                    'return_request_decline' => 6,
                    'cancelled' => 7,
                    'returned' => 8,
                ];
            }
            if (count($res) >= 1) {
                $i = 0;
                foreach ($res as $row) {

                    $set = array();
                    $temp = array();
                    $active_status = array();

                    $active_status[$i] = json_decode($row['status'], 1) ?? [];
                    $current_selected_status = end($active_status[$i]);
                    $temp = $active_status[$i];
                    // $temp = (isset($active_status[$i][0][0]) && $active_status[$i][0][0] != 'awaiting') ? $active_status[$i] : [];
                    $cnt = count($temp);
                    $currTime = date('Y-m-d H:i:s');

                    $min_value = (!empty($temp)) ? $priority_status[$current_selected_status[0]] : -1;
                    $max_value = $priority_status[$current_status];

                    if ($current_status == 'returned' || $current_status == 'cancelled') {
                        $temp[$cnt] = [$current_status, $currTime];
                    } else {

                        foreach ($priority_status as $key => $value) {

                            if ($value > $min_value && $value <= $max_value) {

                                $temp[$cnt] = [$key, $currTime];
                            }
                            ++$cnt;
                        }
                    }


                    $set = [$field[0] => json_encode(array_values($temp))];


                    $this->db->trans_start();
                    $this->db->set($set)->where(['id' => $row['id']])->update($table);
                    $this->db->trans_complete();

                    $response = FALSE;
                    if ($this->db->trans_status() === TRUE) {
                        $response = TRUE;
                    }

                    /* give commission to the delivery boy if the order is delivered */
                    if ($current_status == 'delivered' && $table == 'orders') {
                        $order = fetch_details('orders', $where, 'delivery_boy_id,final_total,payment_method,total_payable');
                        if (!empty($order)) {
                            $delivery_boy_id = $order[0]['delivery_boy_id'];
                            if ($delivery_boy_id > 0) {
                                $commission = 0;
                                $delivery_boy = fetch_details('users', "id=$delivery_boy_id", 'bonus,bonus_type');
                                $final_total = $order[0]['final_total'];
                                $total_payable = $order[0]['total_payable'];
                                $settings = get_settings('system_settings', true);

                                // get bonus_type
                                if ($delivery_boy[0]['bonus_type'] == "fixed_amount_per_order") {
                                    $commission = (isset($delivery_boy[0]['bonus']) && $delivery_boy[0]['bonus'] > 0) ? $delivery_boy[0]['bonus'] : $settings['delivery_boy_bonus_percentage'];
                                }
                                if ($delivery_boy[0]['bonus_type'] == "percentage_per_order") {
                                    $commission = (isset($delivery_boy[0]['bonus']) && $delivery_boy[0]['bonus'] > 0) ? $delivery_boy[0]['bonus'] : $settings['delivery_boy_bonus_percentage'];
                                    $commission = $final_total * ($commission / 100);

                                    if ($commission > $final_total) {
                                        $commission = $final_total;
                                    }
                                }
                                /* commission must be greater then zero to be credited into the account */
                                if ($commission > 0) {
                                    $this->load->model("transaction_model");
                                    $transaction_data = [
                                        'transaction_type' => "wallet",
                                        'user_id' => $delivery_boy_id,
                                        'order_id' => $row['id'],
                                        'type' => "credit",
                                        'txn_id' => "",
                                        'amount' => $commission,
                                        'status' => "success",
                                        'message' => "Order delivery bonus for order ID: #" . $row['id'],
                                    ];
                                    $this->transaction_model->add_transaction($transaction_data);
                                    $this->load->model('customer_model');
                                    $this->customer_model->update_balance($commission, $delivery_boy_id, 'add');

                                    if (strtolower($order[0]['payment_method']) == "cod") {
                                        $transaction_data = [
                                            'transaction_type' => "transaction",
                                            'user_id' => $delivery_boy_id,
                                            'order_id' => $row['id'],
                                            'type' => "delivery_boy_cash",
                                            'txn_id' => "",
                                            'amount' => $total_payable,
                                            'status' => "1",
                                            'message' => "Delivery boy collected COD",
                                        ];
                                        $this->transaction_model->add_transaction($transaction_data);
                                        $this->load->model('customer_model');
                                        update_cash_received($total_payable, $delivery_boy_id, "add");
                                    }
                                }
                            }
                        }
                    }
                    ++$i;
                }
                return $response;
            }
        } else {
            $this->db->trans_start();
            $this->db->set($set)->where($where)->update($table);
            $this->db->trans_complete();
            $response = FALSE;
            if ($this->db->trans_status() === TRUE) {
                $response = TRUE;
            }
            return $response;
        }
    }

    public function delete_draft_orders()
    {
        $status = "draft";
        $products = fetch_details('orders', ['active_status' => $status], 'id');
        foreach ($products as $order_id) {
            $order = fetch_orders($order_id['id'], false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, 0);
            $added_date = $order['order_data'][0]['order_items'][0]['date_added'];

            $added_date_time = new DateTime($added_date);
            $current_time = new DateTime();
            $time_diff = $current_time->diff($added_date_time);

            if ($time_diff->h >= 1 || $time_diff->days > 0) {
                $user_id = $order['order_data'][0]['user_id'];
                $returnable_amount = $order['order_data'][0]['wallet_balance'];
                update_stock($order['order_data'][0]['order_items'][0]['product_variant_id'], $order['order_data'][0]['order_items'][0]['quantity'], 'plus');
                delete_details(['id' => $order['order_data'][0]['id']], 'orders');
                delete_details(['order_id' => $order['order_data'][0]['id']], 'order_items');
                delete_details(['order_id' => $order['order_data'][0]['id']], 'transactions');

                $response['error'] = false;
                $response['message'] = 'Order deleted successfully';
                $response['data'] = array();
            }
        }
        print_r(json_encode($response));
    }

    public function update_order_item($id, $status, $return_request = 0, $fromapp = false, $return_data = [])
    {
        if ($return_request == 0) {
            $res = validate_order_status($id, $status, fromuser: true);
            if ($res['error']) {
                $response['error'] = (isset($res['return_request_flag'])) ? false : true;
                $response['message'] = $res['message'];
                $response['data'] = $res['data'];
                return $response;
            }
        }
        if ($fromapp == true) {
            if ($status == 'returned') {
                $status = 'return_request_pending';
            }
        }

        $order_item_details = fetch_details('order_items', ['id' => $id], 'order_id');
        $order_details = fetch_orders($order_item_details[0]['order_id']);
        if (!empty($order_details) && !empty($order_item_details)) {

            $order_details = $order_details['order_data'];
            $order_items_details = $order_details[0]['order_items'];
            $key = array_search($id, array_column($order_items_details ?? [], 'id'));
            $order_id = $order_details[0]['id'];
            $user_id = $order_details[0]['user_id'];
            $order_counter = $order_items_details[$key]['order_counter'];
            $order_cancel_counter = $order_items_details[$key]['order_cancel_counter'];
            $order_return_counter = $order_items_details[$key]['order_return_counter'];
            $user_res = fetch_details('users', ['id' => $user_id], 'fcm_id');
            $fcm_ids = array();
            if (!empty($user_res[0]['fcm_id'])) {
                $fcm_ids[0][] = $user_res[0]['fcm_id'];
            }


            if ($this->update_order(['status' => $status], ['id' => $id], true, 'order_items')) {

                $this->order_model->update_order(['active_status' => $status], ['id' => $id], false, 'order_items');

                if ($status == 'return_request_pending') {
                    $order_item_data = fetch_details('order_items', ['id' => $id], 'user_id,product_variant_id,order_id');
                    if (!empty($order_item_data)) {
                        $variant_data = fetch_details('product_variants', ['id' => $order_item_data[0]['product_variant_id']], 'product_id');

                        if (!is_exist(['order_item_id' => $id], 'return_requests')) {
                            $request_data = [
                                'user_id' => $order_item_data[0]['user_id'],
                                'product_id' => $variant_data[0]['product_id'],
                                'product_variant_id' => $order_item_data[0]['product_variant_id'],
                                'order_id' => $order_item_data[0]['order_id'],
                                'order_item_id' => $id,
                                'status' => 0
                            ];
                            $this->db->insert('return_requests', $request_data);
                        }
                    }
                }

                // following code is for return image in return item
                if (isset($return_data) && !empty($return_data) && $return_data != []) {
                    unset($return_data['order_item_id']);
                    unset($return_data['order_id']);

                    unset($return_data['ci_csrf_token']);
                    unset($return_data['other_reason']);
                    unset($return_data['status']);


                    update_details($return_data, ['id' => $id], 'order_items');
                    update_details($return_data, ['order_item_id' => $id], 'return_requests');
                }

                // Update full order status only when ALL items are cancelled or returned
                if (
                    ($order_counter == intval($order_cancel_counter) + 1 && $status == 'cancelled') ||
                    ($order_counter == intval($order_return_counter) + 1 && $status == 'returned')
                ) {
                    if ($this->update_order(['status' => $status], ['id' => $order_id], true)) {
                        $this->update_order(['active_status' => $status], ['id' => $order_id]);
                    }
                }
            }

            $response['error'] = false;
            $response['message'] = 'Status Updated Successfully';
            $response['data'] = array();
            return $response;
        }
    }

    public function place_order($data)
    {
        // print_R($data);
        // die();
        $data = escape_array($data);
        $CI = &get_instance();
        $CI->load->model('Address_model');
        $files = (isset($_POST['attachments']) && !empty($_POST['attachments'])) ? json_encode($_POST['attachments']) : "";
        $response = array();
        $user = fetch_details('users', ['id' => $data['user_id']]);
        $user_email = $user[0]['email'];
        $product_variant_id = explode(',', $data['product_variant_id'] ?? '');
        $quantity = explode(',', $data['quantity'] ?? '');
        $otp = mt_rand(100000, 999999);

        $check_current_stock_status = validate_stock($product_variant_id, $quantity);


        if (isset($check_current_stock_status['error']) && $check_current_stock_status['error'] == true) {
            return ($check_current_stock_status);
        }

        /* Calculating Final Total */

        $total = 0;
        $product_variant = $this->db->select('pv.*, p.tax as tax_ids, p.name as product_name, p.is_prices_inclusive_tax, p.is_attachment_required, p.download_link,p.image, p.is_cancelable, p.is_returnable')
            ->join('products p ', 'pv.product_id=p.id', 'left')
            ->join('categories c', 'p.category_id = c.id', 'left')
            ->where_in('pv.id', $product_variant_id)
            ->order_by('FIELD(pv.id,' . $data['product_variant_id'] . ')')
            ->get('product_variants pv')
            ->result_array();

        if (!empty($product_variant)) {

            $system_settings = get_settings('system_settings', true);
            $shipping_setting = get_settings(('shipping_method'), true);
            $pickup = (isset($system_settings['local_pickup'])) ? $system_settings['local_pickup'] : 0;

            if ($shipping_setting['shiprocket_shipping_method'] == 0 && $shipping_setting['local_shipping_method'] == 0) {
                $response['error'] = true;
                $response['message'] = 'All Shipping Method is Close Right Now Please Wait For Sometime or Contact To Admin';
                return $response;
            }

            if ($pickup == 1 && $data['local_pickup'] == 1) {
                $delivery_charge = 0;
            } else {
                $delivery_charge = isset($data['delivery_charge']) && !empty($data['delivery_charge']) ? $data['delivery_charge'] : 0;
            }

            $gross_total = 0;
            $cart_data = [];

            for ($i = 0; $i < count($product_variant); $i++) {
                $in_flash_sale = exists_in_flash_sale($product_variant[$i]['product_id']);

                if (isset($in_flash_sale[0]['discount']) && !empty($in_flash_sale[0]['discount'])) {
                    $flash_sale_price = get_flash_sale_price($product_variant[$i]['price'], $in_flash_sale[0]['discount']);
                    $pv_price[$i] = $flash_sale_price;
                } else {
                    $pv_price[$i] = ($product_variant[$i]['special_price'] > 0 && $product_variant[$i]['special_price'] != null) ? $product_variant[$i]['special_price'] : $product_variant[$i]['price'];
                }

                $tax_ids = explode(',', $product_variant[$i]['tax_ids']); // Get tax IDs as an array
                $tax_percentage[$i] = 0;
                $tax_amount[$i] = 0;

                foreach ($tax_ids as $tax_id) {
                    $tax_data = $this->db->select('percentage, title')
                        ->where('id', $tax_id)
                        ->get('taxes')
                        ->row_array();

                    if (!empty($tax_data)) {
                        $tax_percentage[$i] += $tax_data['percentage'];

                        if ((isset($product_variant[$i]['is_prices_inclusive_tax']) && $product_variant[$i]['is_prices_inclusive_tax'] == 0) || (!isset($product_variant[$i]['is_prices_inclusive_tax'])) && $tax_data['percentage'] > 0) {
                            $tax_amount[$i] += $pv_price[$i] * ($tax_data['percentage'] / 100);
                        }
                    }
                }

                if (!isset($product_variant[$i]['is_prices_inclusive_tax']) || $product_variant[$i]['is_prices_inclusive_tax'] == 0) {
                    $pv_price[$i] += $tax_amount[$i]; // Add tax to price if not inclusive
                }

                $subtotal[$i] = ($pv_price[$i]) * $quantity[$i];
                $pro_name[$i] = $product_variant[$i]['product_name'];
                $variant_info = get_variants_values_by_id($product_variant[$i]['id']);
                $product_variant[$i]['variant_name'] = (isset($variant_info[0]['variant_values']) && !empty($variant_info[0]['variant_values'])) ? $variant_info[0]['variant_values'] : "";

                $gross_total += $subtotal[$i];
                $total += $subtotal[$i];
                $total = round($total, 2);
                $gross_total = round($gross_total, 2);

                array_push($cart_data, array(
                    'name' => $pro_name[$i],
                    'tax_amount' => $tax_amount[$i],
                    'qty' => $quantity[$i],
                    'sub_total' => $subtotal[$i],
                ));
            }

            // Continue with the rest of the code

            /* Calculating Bulk Discount */
            $bulk_discount = 0;

            // Check if bulk discount is provided from frontend (checkout page)
            if (isset($data['bulk_discount']) && !empty($data['bulk_discount'])) {
                $bulk_discount = floatval($data['bulk_discount']);
            } else {
                // Calculate bulk discount if not provided
                // Get bulk discount info for all products in the order with their quantities
                $bulk_discount_products = $this->db->select('p.id as product_id, p.bulk_discount_min_qty, p.bulk_discount_amount, pv.id as variant_id')
                    ->join('product_variants pv', 'p.id = pv.product_id')
                    ->where_in('pv.id', $product_variant_id)
                    ->where('p.bulk_discount_min_qty > 0')
                    ->where('p.bulk_discount_amount > 0')
                    ->get('products p')
                    ->result_array();

                if (!empty($bulk_discount_products)) {
                    // Create a mapping of variant_id to quantity
                    $variant_qty_map = array_combine($product_variant_id, $quantity);

                    $product_quantities = [];
                    $product_discount_info = [];

                    foreach ($bulk_discount_products as $row) {
                        $pid = $row['product_id'];
                        $vid = $row['variant_id'];
                        if (!isset($product_quantities[$pid])) {
                            $product_quantities[$pid] = 0;
                            $product_discount_info[$pid] = [
                                'min_qty' => $row['bulk_discount_min_qty'],
                                'amount' => $row['bulk_discount_amount']
                            ];
                        }
                        $product_quantities[$pid] += isset($variant_qty_map[$vid]) ? intval($variant_qty_map[$vid]) : 0;
                    }

                    foreach ($product_quantities as $pid => $total_qty) {
                        if ($total_qty >= $product_discount_info[$pid]['min_qty']) {
                            $bulk_discount += $product_discount_info[$pid]['amount'];
                        }
                    }
                }

            }

            // Apply bulk discount to total if applicable
            if ($bulk_discount > 0) {
                $total = max(0, $total - $bulk_discount);
                $gross_total = max(0, $gross_total - $bulk_discount);
            }

            $system_settings = get_settings('system_settings', true);

            /* Calculating Promo Discount */
            if (isset($data['promo_code']) && !empty($data['promo_code'])) {

                $promo_code = validate_promo_code($data['promo_code'], $data['user_id'], $gross_total);

                if ($promo_code['error'] == false) {

                    if ($promo_code['data'][0]['discount_type'] == 'percentage') {
                        $promo_code_discount = (isset($promo_code['data'][0]['is_cashback']) && $promo_code['data'][0]['is_cashback'] == 0) ? floatval($total * $promo_code['data'][0]['discount'] / 100) : 0;
                    } else {
                        $promo_code_discount = (isset($promo_code['data'][0]['is_cashback']) && $promo_code['data'][0]['is_cashback'] == 0) ? $promo_code['data'][0]['discount'] : 0;
                    }
                    if ($promo_code_discount <= $promo_code['data'][0]['max_discount_amount']) {
                        $total = (isset($promo_code['data'][0]['is_cashback']) && $promo_code['data'][0]['is_cashback'] == 0) ? floatval($total) - $promo_code_discount : floatval($total);
                    } else {
                        $total = (isset($promo_code['data'][0]['is_cashback']) && $promo_code['data'][0]['is_cashback'] == 0) ? floatval($total) - $promo_code['data'][0]['max_discount_amount'] : floatval($total);
                        $promo_code_discount = $promo_code['data'][0]['max_discount_amount'];
                    }
                } else {
                    return $promo_code;
                }
            }

            $platform_fees = isset($data['platform_fees']) ? floatval($data['platform_fees']) : 0;
            $custom_charges_total = isset($data['custom_charges_total']) ? floatval($data['custom_charges_total']) : 0;
            $final_total = $total + $delivery_charge + $platform_fees + $custom_charges_total;

            $final_total = round($final_total, 2);

            /* Calculating Wallet Balance */
            $total_payable = $final_total;
            $actual_wallet_used = 0;

            if ($data['is_wallet_used'] == '1') {
                // Use the minimum of wallet balance used or final total
                $wallet_amount_to_use = min($data['wallet_balance_used'], $final_total);

                $wallet_balance = update_wallet_balance('debit', $data['user_id'], $wallet_amount_to_use, "Used against Order Placement");
                if ($wallet_balance['error'] == false) {
                    $total_payable -= $wallet_amount_to_use;
                    $Wallet_used = true;
                    $actual_wallet_used = $wallet_amount_to_use;
                } else {
                    $response['error'] = true;
                    $response['message'] = $wallet_balance['message'];
                    return $response;
                }
            }

            $status = (isset($data['active_status'])) ? $data['active_status'] : 'received';

            $custom_charges_payload = null;

            if (!empty($data['custom_charges_json'])) {
                $json = $data['custom_charges_json'];
                $json = stripslashes($json);
                $decoded = json_decode($json, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $custom_charges_payload = $decoded;
                }
            } elseif (isset($data['custom_charges'])) {
                if (is_array($data['custom_charges'])) {
                    $custom_charges_payload = $data['custom_charges'];
                } elseif (is_string($data['custom_charges'])) {
                    // Try to decode to see if it's already JSON
                    $decoded = json_decode($data['custom_charges'], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $custom_charges_payload = $decoded;
                    }
                }
            }

            if (!empty($custom_charges_payload) && is_array($custom_charges_payload)) {

                $filtered_charges = [];

                $is_pos_order = !empty($data['is_pos_order']);
                $is_digital_order = isset($data['product_type']) && $data['product_type'] === 'digital_product';
                $is_pickup_order = !empty($data['local_pickup']); // store pickup
                $is_doorstep = !$is_pickup_order && !$is_digital_order;

                // Check if charges are already filtered (no apply_* flags present)
                $first_charge = reset($custom_charges_payload);
                $already_filtered = !isset($first_charge['apply_pos']) &&
                    !isset($first_charge['apply_digital']) &&
                    !isset($first_charge['apply_pickup']) &&
                    !isset($first_charge['apply_doorstep']);
                !isset($first_charge['is_refundable']);

                if ($already_filtered) {
                    // Charges are already filtered, use them as-is
                    $filtered_charges = $custom_charges_payload;
                } else {
                    // Filter charges based on order type
                    foreach ($custom_charges_payload as $charge) {

                        $apply = false;

                        if ($is_pos_order && !empty($charge['apply_pos'])) {
                            $apply = true;
                        } elseif ($is_digital_order && !empty($charge['apply_digital'])) {
                            $apply = true;
                        } elseif ($is_pickup_order && !empty($charge['apply_pickup'])) {
                            $apply = true;
                        } elseif ($is_doorstep && !empty($charge['apply_doorstep'])) {
                            $apply = true;
                        } else if ($is_refundable && !empty($charge['is_refundable'])) {
                            $apply = true;
                        }

                        if ($apply) {
                            $filtered_charges[] = [
                                'name' => $charge['name'],
                                'amount' => (float) $charge['amount']
                            ];
                        }
                    }
                }

                $custom_charges_payload = !empty($filtered_charges)
                    ? json_encode($filtered_charges)
                    : null;
            } else {
                // Safety check: if it's still an array at this point, encode it or set to null
                if (is_array($custom_charges_payload)) {
                    $custom_charges_payload = !empty($custom_charges_payload)
                        ? json_encode($custom_charges_payload)
                        : null;
                }
            }




            // die();
            $order_data = [
                'user_id' => $data['user_id'],
                'mobile' => (isset($data['mobile']) && !empty($data['mobile'])) ? $data['mobile'] : '',
                'total' => $gross_total,
                'attachments' => '',
                'promo_discount' => (isset($promo_code_discount) && $promo_code_discount != NULL) ? $promo_code_discount : '0',
                'bulk_discount' => $bulk_discount,
                'total_payable' => $total_payable,
                'delivery_charge' => $delivery_charge,
                'is_delivery_charge_returnable' => $data['is_delivery_charge_returnable'],
                'wallet_balance' => (isset($Wallet_used) && $Wallet_used == true) ? $actual_wallet_used : '0',
                'final_total' => $final_total,
                'discount' => '0',
                'payment_method' => $data['payment_method'],
                'status' => json_encode(array(array($status, date("d-m-Y h:i:sa")))),
                'active_status' => $status,
                'is_local_pickup' => $data['local_pickup'],
                'promo_code' => (isset($data['promo_code'])) ? $data['promo_code'] : '',
                'email' => isset($data['email']) ? $data['email'] : ' ',
                'custom_charges' => $custom_charges_payload,
                // 'platform_fees' => $platform_fees,
            ];
            // print_R($custom_charges_payload);
            // die();
            if (isset($data['address_id']) && !empty($data['address_id'])) {
                $order_data['address_id'] = $data['address_id'];
            }

            if (isset($data['delivery_date']) && !empty($data['delivery_date']) && !empty($data['delivery_time']) && isset($data['delivery_time'])) {
                $order_data['delivery_date'] = date('Y-m-d', strtotime($data['delivery_date']));
                $order_data['delivery_time'] = $data['delivery_time'];
            }
            if (isset($data['address_id']) && !empty($data['address_id'])) {
                $address_data = $CI->address_model->get_address('', $data['address_id'], true);
                if (!empty($address_data)) {
                    $order_data['latitude'] = $address_data[0]['latitude'];
                    $order_data['longitude'] = $address_data[0]['longitude'];
                    $order_data['address'] = (!empty($address_data[0]['address'])) ? $address_data[0]['address'] . ', ' : '';
                    $order_data['address'] .= (!empty($address_data[0]['landmark'])) ? $address_data[0]['landmark'] . ', ' : '';
                    $order_data['address'] .= (!empty($address_data[0]['area'])) ? $address_data[0]['area'] . ', ' : '';
                    $order_data['address'] .= (!empty($address_data[0]['city'])) ? $address_data[0]['city'] . ', ' : '';
                    $order_data['address'] .= (!empty($address_data[0]['state'])) ? $address_data[0]['state'] . ', ' : '';
                    $order_data['address'] .= (!empty($address_data[0]['country'])) ? $address_data[0]['country'] . ', ' : '';
                    $order_data['address'] .= (!empty($address_data[0]['pincode'])) ? $address_data[0]['pincode'] : '';
                    $order_data['mobile'] = (!empty($address_data[0]['mobile'])) ? $address_data[0]['mobile'] : $data['mobile'];
                }
            } else {
                $order_data['address'] = "";
            }


            if (!empty($_POST['latitude']) && !empty($_POST['longitude'])) {
                $order_data['latitude'] = $_POST['latitude'];
                $order_data['longitude'] = $_POST['longitude'];
            }
            if ($system_settings['is_delivery_boy_otp_setting_on'] == '1') {
                $order_data['otp'] = $otp;
            } else {
                $order_data['otp'] = 0;
            }
            $order_data['notes'] = $data['order_note'];
            $this->db->insert('orders', $order_data);
            $last_order_id = $this->db->insert_id();

            $attachments = (isset($data['attachments']) && !empty($data['attachments'])) ? $data['attachments'] : '';

            for ($i = 0; $i < count($product_variant); $i++) {
                $variant_id = $product_variant[$i]['id'];
                $individual_tax_amount = is_array($tax_amount[$i]) ? array_sum($tax_amount[$i]) : $tax_amount[$i];
                $individual_tax_percentage = is_array($tax_percentage[$i]) ? array_sum($tax_percentage[$i]) : $tax_percentage[$i];
                $tax_ids = $product_variant[$i]['tax_ids'];
                $product_variant_data[$i] = [
                    'user_id' => $data['user_id'],
                    'order_id' => $last_order_id,
                    'product_name' => $product_variant[$i]['product_name'],
                    'product_type' => $product_variant[$i]['product_type'],
                    'product_image' => $product_variant[$i]['image'],
                    'deliveryboy_otp_setting_on' => $system_settings['is_delivery_boy_otp_setting_on'],
                    'product_is_cancelable' => $product_variant[$i]['is_cancelable'],
                    'product_is_returnable' => $product_variant[$i]['is_returnable'],
                    'variant_name' => $product_variant[$i]['variant_name'],
                    'product_variant_id' => $product_variant[$i]['id'],
                    'quantity' => $quantity[$i],
                    'price' => $pv_price[$i],
                    'tax_percent' => $individual_tax_percentage,
                    'tax_amount' => $individual_tax_amount,
                    'tax_id' => $tax_ids,
                    'sub_total' => $subtotal[$i],
                    'status' => json_encode(array(array($status, date("d-m-Y h:i:sa")))),
                    'active_status' => $status,
                    'attachment' => isset($attachments[$variant_id]) ? $attachments[$variant_id] : '',
                ];
                $this->db->insert('order_items', $product_variant_data[$i]);
                $order_item_id = $this->db->insert_id();
                if (isset($product_variant[$i]['download_link']) && !empty($product_variant[$i]['download_link'])) {
                    $hash_link = $product_variant[$i]['download_link'] . '-' . $order_item_id;
                    $hash_link_data['hash_link'] = $hash_link;
                    $this->db->where('id', $order_item_id)->update('order_items', $hash_link_data);
                }
            }
            $product_variant_ids = explode(',', $data['product_variant_id']);
            $product_variant_data[0]['attachments'] = json_decode($files, true);

            if (isset($product_variant_data[0]['attachments']) && !empty($product_variant_data[0]['attachments']))
                for ($i = 0; $i < count($product_variant_data[0]['attachments']); $i++) {
                    $product_variant_data[0]['attachments'][$i] = base_url($product_variant_data[0]['attachments'][$i]);
                }
            $qtns = explode(',', $data['quantity'] ?? '');
            update_stock($product_variant_ids, $qtns);

            $overall_total = array(
                'total_amount' => array_sum($subtotal),
                'delivery_charge' => $delivery_charge,
                'tax_amount' => array_sum($tax_amount),
                'tax_percentage' => array_sum($tax_percentage),
                'discount' => $order_data['promo_discount'],
                'wallet' => $order_data['wallet_balance'],
                'final_total' => $order_data['final_total'],
                'total_payable' => $order_data['total_payable'],
                'otp' => $otp,
                'address' => (isset($order_data['address'])) ? $order_data['address'] : '',
                'payment_method' => $data['payment_method']
            );
            if (trim(strtolower($data['payment_method'])) != 'paypal' || trim(strtolower($data['payment_method'])) != 'stripe') {
                $overall_order_data = array(
                    'cart_data' => $cart_data,
                    'order_data' => $overall_total,
                    'subject' => 'Order received successfully',
                    'user_data' => $user[0],
                    'system_settings' => $system_settings,
                    'user_msg' => 'Hello, Dear ' . ucfirst($user[0]['username']) . ', We have received your order successfully. Your order summaries are as followed',
                    'otp_msg' => 'Here is your OTP. Please, give it to delivery boy only while getting your order.',
                );
            }
            // Async email call
            $url = site_url('home/send_order_email/' . $last_order_id);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
            curl_exec($ch);
            curl_close($ch);

            $this->cart_model->remove_from_cart($data);


            $user_balance = fetch_details('users', ['id' => $data['user_id']], 'balance');

            $response['error'] = false;
            $response['message'] = 'Order Placed Successfully';
            $response['order_id'] = $last_order_id;
            $response['order_item_data'] = $product_variant_data;
            $response['balance'] = $user_balance;
            return $response;
        } else {
            $user_balance = fetch_details('users', ['id' => $data['user_id']], 'balance');

            $response['error'] = true;
            $response['message'] = "Product(s) Not Found!";
            $response['balance'] = $user_balance;
            return $response;
        }
    }

    public function get_order_details($where = 'NULL', $status = false)
    {
        $res = $this->db->select('oi.*,a.name as user_name,a.mobile as recipient_contact,oi.id as order_item_id,p.*,v.product_id,o.*,o.id as order_id,o.total as order_total,o.wallet_balance,o.custom_charges,oi.active_status as oi_active_status,u.email,u.username as uname,o.status as order_status,p.name as pname,p.slug as product_slug,p.type,p.image as product_image,v.price as product_price,v.special_price as product_special_price,p.is_prices_inclusive_tax,(SELECT username FROM users db where db.id=o.delivery_boy_id ) as delivery_boy,(SELECT status FROM orders o where o.id=oi.order_id  ) as order_status ')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users u ', ' u.id = oi.user_id', 'left')
            ->join('orders o ', 'o.id=oi.order_id', 'left')
            ->join('addresses a', 'a.id=o.address_id', 'left');
        if (isset($where) && $where != NULL) {
            $res->where($where);
            if ($status == true) {
                $res->group_Start()
                    ->where_not_in(' `oi`.active_status ', array('cancelled', 'returned'))
                    ->group_End();
            }
        }
        if (!isset($where) && $status == true) {
            $res->where_not_in(' `oi`.active_status ', array('cancelled', 'returned'));
        }
        $order_result = $res->get(' `order_items` oi')->result_array();

        if (!empty($order_result)) {
            for ($i = 0; $i < count($order_result); $i++) {
                $order_result[$i] = output_escaping($order_result[$i]);
            }
        }
        return $order_result;
    }

    public function get_orders_list(
        $delivery_boy_id = NULL,
        $offset = 0,
        $limit = 10,
        $sort = " o.id ",
        $order = 'ASC'
    ) {
        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $filters = [
                'u.username' => $search,
                'db.username' => $search,
                'u.email' => $search,
                'o.id' => $search,
                'o.mobile' => $search,
                'o.address' => $search,
                'o.wallet_balance' => $search,
                'o.total' => $search,
                'o.final_total' => $search,
                'o.total_payable' => $search,
                'o.payment_method' => $search,
                'o.delivery_charge' => $search,
                'o.delivery_time' => $search,
                'o.status' => $search,
                'o.active_status' => $search,
                'o.date_added' => $search
            ];
        }

        $count_res = $this->db->select(' COUNT(DISTINCT(o.id)) as `total`')
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('order_tracking ot ', ' ot.order_id = o.id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users db ', ' db.id = o.delivery_boy_id', 'left');
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $count_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }

        if (isset($delivery_boy_id)) {
            $count_res->where("o.delivery_boy_id", $delivery_boy_id);
        }

        if (isset($_GET['user_id']) && $_GET['user_id'] != null) {
            $count_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            $count_res->where('o.active_status', $_GET['order_status']);
        }

        // Filter By payment
        if (isset($_GET['payment_method']) && !empty($_GET['payment_method'])) {
            $count_res->where('payment_method', $_GET['payment_method']);
        }

        if (isset($_GET['delivery_boy']) && !empty($_GET['delivery_boy'])) {
            $count_res->where('delivery_boy_id', $_GET['delivery_boy']);
        }

        // Filter By order type
        if (isset($_GET['order_type']) && !empty($_GET['order_type']) && $_GET['order_type'] == 'physical_order') {
            $count_res->where('p.type!=', 'digital_product');
        }
        if (isset($_GET['order_type']) && !empty($_GET['order_type']) && $_GET['order_type'] == 'digital_order') {
            $count_res->where('p.type', 'digital_product');
        }

        $product_count = $count_res->get('`orders` o')->result_array();


        foreach ($product_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' o.*,ot.courier_agency,ot.tracking_id,ot.url , u.username, db.username as delivery_boy ')
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `users` db ', 'db.id = o.delivery_boy_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join(' `order_tracking` ot', 'ot.order_id = o.id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }

        if (isset($delivery_boy_id)) {
            $search_res->where("o.delivery_boy_id", $delivery_boy_id);
        }

        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $search_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            $search_res->where('o.active_status', $_GET['order_status']);
        }

        // Filter By payment
        if (isset($_GET['payment_method']) && !empty($_GET['payment_method'])) {
            $count_res->where('payment_method', $_GET['payment_method']);
        }

        if (isset($_GET['delivery_boy']) && !empty($_GET['delivery_boy'])) {
            $count_res->where('delivery_boy_id', $_GET['delivery_boy']);
        }

        // Filter By order type
        if (isset($_GET['order_type']) && !empty($_GET['order_type']) && $_GET['order_type'] == 'physical_order') {
            $search_res->where('p.type!=', 'digital_product');
        }
        if (isset($_GET['order_type']) && !empty($_GET['order_type']) && $_GET['order_type'] == 'digital_order') {
            $search_res->where('p.type', 'digital_product');
        }
        $user_details = $search_res->group_by('o.id')->order_by($sort, "DESC")->limit($limit, $offset)->get('`orders` o')->result_array();
        $i = 0;
        foreach ($user_details as $row) {

            $user_details[$i]['items'] = $this->db->select('oi.*,p.name as name,p.id as product_id, p.type,p.download_allowed,u.username as uname, (SELECT status FROM orders o where o.id=oi.order_id  ) as order_status  ')
                ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
                ->join('products p ', ' p.id = v.product_id ', 'left')
                ->join('users u ', ' u.id = oi.user_id', 'left')
                ->where('oi.order_id', $row['id'])
                ->get(' `order_items` oi  ')->result_array();
            ++$i;
        }

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $tota_amount = 0;
        $final_tota_amount = 0;
        $currency_symbol = get_settings('currency');
        foreach ($user_details as $row) {
            if (!empty($row['items'])) {
                $items = $row['items'];
                $items1 = '';
                $temp = '';
                $total_amt = $total_qty = 0;
                foreach ($items as $item) {
                    $product_variants = get_variants_values_by_id($item['product_variant_id']);
                    $variants = isset($product_variants[0]['variant_values']) && !empty($product_variants[0]['variant_values']) ? str_replace(',', ' | ', $product_variants[0]['variant_values']) : '-';
                    $temp .= "<b>ID :</b>" . $item['id'] . "<b> Product Variant Id :</b> " . $item['product_variant_id'] . "<b> Variants :</b> " . $variants . "<b> Name : </b>" . $item['name'] . " <b>Price : </b>" . abs($item['price']) . " <b>QTY : </b>" . $item['quantity'] . " <b>Subtotal : </b>" . abs($item['quantity'] * $item['price']) . "<br>------<br>";
                    $total_amt += abs($item['sub_total']);
                    $total_qty += $item['quantity'];
                }

                $items1 = $temp;
                $temp = '';
                if (!empty($row['items'][0]['order_status'])) {
                    $status = json_decode($row['items'][0]['order_status'], 1);
                    foreach ($status as $st) {
                        $temp .= @$st[0] . " : " . @$st[1] . "<br>------<br>";
                    }
                }

                if (trim($row['active_status']) == 'awaiting') {
                    $active_status = '<label class="badge bg-secondary">' . $row['active_status'] . '</label>';
                }
                if ($row['active_status'] == 'received') {
                    $active_status = '<label class="badge bg-primary">' . $row['active_status'] . '</label>';
                }
                if ($row['active_status'] == 'processed') {
                    $active_status = '<label class="badge bg-info">' . $row['active_status'] . '</label>';
                }
                if ($row['active_status'] == 'shipped') {
                    $active_status = '<label class="badge bg-warning">' . str_replace('_', ' ', $row['active_status']) . '</label>';
                }
                if ($row['active_status'] == 'ready_to_pickup') {
                    $active_status = '<label class="badge bg-warning">' . str_replace('_', ' ', $row['active_status']) . '</label>';
                }
                if ($row['active_status'] == 'delivered') {
                    $active_status = '<label class="badge bg-success">' . $row['active_status'] . '</label>';
                }
                if ($row['active_status'] == 'returned' || $row['active_status'] == 'cancelled') {
                    $active_status = '<label class="badge bg-danger">' . $row['active_status'] . '</label>';
                }


                $payment_method = $row['payment_method'];

                $status = $temp;
                $discounted_amount = $row['total'] * $row['items'][0]['discount'] / 100; /*  */
                $final_total = $row['total'] - $discounted_amount;
                $discount_in_rupees = $row['total'] - $final_total;
                $discount_in_rupees = floor($discount_in_rupees);
                $tempRow['id'] = $row['id'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['name'] = $row['items'][0]['uname'];
                if (isset($row['mobile']) && !empty($row['mobile']) && $row['mobile'] != "" && $row['mobile'] != " ") {
                    $maskedMobile = (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3) : $row['mobile'];
                    $tempRow['mobile'] = '<div class="d-flex gap-1 align-items-center justify-content-between">' .
                        $maskedMobile . "<a href='https://api.whatsapp.com/send?phone=" .
                        $maskedMobile . "&text=Hello " . $row['items'][0]['uname'] .
                        ", Your order with ID :" . $row['id'] . " and is " .
                        $row['active_status'] . ". Please take a note of it. If you have further queries feel free to contact us. Thank you.' target='_blank' title='Send Whatsapp Notification' class='btn btn-success btn-xs rounded-1 py-1'><ion-icon class='align-bottom fs-3' name='logo-whatsapp'></ion-icon></a></div>";
                } else {
                    $tempRow['mobile'] = "";
                }

                $tempRow['notes'] = $row['notes'];
                $tempRow['delivery_charge'] = $currency_symbol . ' ' . abs($row['delivery_charge']);
                $tempRow['items'] = $items1;
                $tempRow['total'] = $currency_symbol . ' ' . abs($row['total']);
                $tota_amount += abs(intval($row['total']));
                $tempRow['wallet_balance'] = $currency_symbol . ' ' . abs($row['wallet_balance']);
                $tempRow['discount'] = $currency_symbol . ' ' . abs($discount_in_rupees) . '(' . $row['items'][0]['discount'] . '%)';
                $tempRow['promo_discount'] = $currency_symbol . ' ' . abs($row['promo_discount']);
                $tempRow['promo_code'] = $row['promo_code'];
                $tempRow['bulk_discount'] = $currency_symbol . ' ' . abs($row['bulk_discount']);
                $tempRow['qty'] = $total_qty;
                $tempRow['final_total'] = $currency_symbol . ' ' . abs($row['final_total']);
                $final_total = abs($row['final_total'] - $row['wallet_balance'] - $row['discount']);
                $final_tota_amount += abs(intval($row['final_total']));
                $tempRow['deliver_by'] = $row['delivery_boy'];
                $tempRow['payment_method'] = str_replace('_', ' ', $payment_method);
                $tempRow['address'] = output_escaping(str_replace('\r\n', '</br>', $row['address']));
                $tempRow['delivery_date'] = $row['delivery_date'];
                $tempRow['delivery_time'] = $row['delivery_time'];
                $tempRow['status'] = $status;
                $tempRow['active_status'] = $active_status;
                $tempRow['local_pickup'] = ($row['is_local_pickup'] == 1) ? '<label class="badge bg-success">Yes</label>' : '<label class="badge bg-danger">NO</label>';
                $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));
                $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . '" class="btn action-btn btn-primary btn-xs mr-1 mb-1" title="View" ><i class="fa fa-eye"></i></a>';
                if (!$this->ion_auth->is_delivery_boy()) {
                    $operate = '';
                    $operate = '<div class="dropdown">
                    <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                      <a class="dropdown-item" href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . '><i class="fa fa-eye"></i> View Order</a>
                      <a href="javascript:void(0)" class="delete-orders dropdown-item" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i> Delete</a>
                      <a class="dropdown-item" href=' . base_url('admin/invoice') . '?edit_id=' . $row['id'] . '><i class="fa fa-file"></i> Invoice</a>';

                    if (trim($row['active_status']) != 'awaiting' && $row['is_local_pickup'] == 0 && $row['items'][0]['type'] != 'digital_product') {
                        $operate .= ' <a class="dropdown-item btn edit_order_tracking" href="javascript:void(0)" title="Order Tracking" data-order_id="' . $row['id'] . '"  data-courier_agency="' . $row['courier_agency'] . '"  data-tracking_id="' . $row['tracking_id'] . '" data-url="' . $row['url'] . '" data-target="#transaction_modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i> Order Tracking</a>';
                    }
                    if ($row['items'][0]['type'] == 'digital_product' && $row['items'][0]['download_allowed'] == 0) {

                        $operate .= ' <a class="dropdown-item" href="javascript:void(0)" class="edit_digital_order_mails" title="Digital Order Mails" data-order_id="' . $row['id'] . '"  data-target="#digital-order-mails" data-toggle="modal"><i class="bx bxs-envelope"></i> Digital Order Mails</a>';
                    }
                    '<a class="dropdown-item" href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['url'] . '><i class="fa fa-map-marker"></i> Order Tracking</a>
                    </div>';
                } else {
                    $operate = '<a href=' . base_url('delivery_boy/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn action-btn btn-primary btn-xs mr-1 mb-1" title="View"><i class="fa fa-eye"></i></a>';
                }
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
        }
        if (!empty($user_details)) {
            $tempRow['id'] = '-';
            $tempRow['user_id'] = '-';
            $tempRow['name'] = '-';
            $tempRow['mobile'] = '-';
            $tempRow['delivery_charge'] = '-';
            $tempRow['items'] = '-';
            $tempRow['total'] = '<span class="badge bg-danger">' . $currency_symbol . ' ' . $tota_amount . '</span>';
            $tempRow['wallet_balance'] = '-';
            $tempRow['discount'] = '-';
            $tempRow['promo_discount'] = '-';
            $tempRow['promo_code'] = '-';
            $tempRow['bulk_discount'] = '-';
            $tempRow['qty'] = '-';
            $tempRow['final_total'] = '<span class="badge bg-danger">' . $currency_symbol . ' ' . $final_tota_amount . '</span>';
            $tempRow['deliver_by'] = '-';
            $tempRow['payment_method'] = '-';
            $tempRow['address'] = '-';
            $tempRow['delivery_time'] = '-';
            $tempRow['status'] = '-';
            $tempRow['active_status'] = '-';
            $tempRow['wallet_balance'] = '-';
            $tempRow['date_added'] = '-';
            $tempRow['operate'] = '-';
            array_push($rows, $tempRow);
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_digital_product_orders_list(
        $delivery_boy_id = NULL,
        $offset = 0,
        $limit = 10,
        $sort = " o.id ",
        $order = 'ASC'
    ) {

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $filters = [
                'u.username' => $search,
                'db.username' => $search,
                'u.email' => $search,
                'o.id' => $search,
                'o.mobile' => $search,
                'o.address' => $search,
                'o.wallet_balance' => $search,
                'o.total' => $search,
                'o.final_total' => $search,
                'o.total_payable' => $search,
                'o.payment_method' => $search,
                'o.delivery_charge' => $search,
                'o.delivery_time' => $search,
                'oi.status' => $search,
                'oi.active_status' => $search,
                'o.date_added' => $search
            ];
        }


        $count_res = $this->db->select(' COUNT(o.id) as `total` ,p.type')
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users db ', ' db.id = o.delivery_boy_id', 'left');
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $count_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }
        $count_res->where("p.type", 'digital_product');

        if (isset($delivery_boy_id)) {
            $count_res->where("o.delivery_boy_id", $delivery_boy_id);
        }

        if (isset($_GET['user_id']) && $_GET['user_id'] != null) {
            $count_res->where("o.user_id", $_GET['user_id']);
        }
        // Filter By payment
        if (isset($_GET['payment_method']) && !empty($_GET['payment_method'])) {
            $count_res->where('payment_method', $_GET['payment_method']);
        }
        $product_count = $count_res->get('`orders` o')->result_array();

        foreach ($product_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' o.* , u.username, db.username as delivery_boy,p.type')
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users db ', ' db.id = o.delivery_boy_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }

        if (isset($delivery_boy_id)) {
            $search_res->where("o.delivery_boy_id", $delivery_boy_id);
        }
        $search_res->where("p.type", 'digital_product');

        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $search_res->where("o.user_id", $_GET['user_id']);
        }

        // Filter By payment
        if (isset($_GET['payment_method']) && !empty($_GET['payment_method'])) {
            $count_res->where('payment_method', $_GET['payment_method']);
        }
        $user_details = $search_res->group_by('o.id')->order_by($sort, "DESC")->limit($limit, $offset)->get('`orders` o')->result_array();

        $i = 0;
        foreach ($user_details as $row) {


            $user_details[$i]['items'] = $this->db->select('oi.*,p.name as name,p.id as product_id,p.type,p.download_allowed, u.username as uname ')
                ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
                ->join('products p ', ' p.id = v.product_id ', 'left')
                ->join('users u ', ' u.id = oi.user_id', 'left')
                ->where('oi.order_id', $row['id'])
                ->where('p.type', 'digital_product')
                ->get(' `order_items` oi  ')->result_array();

            ++$i;
        }

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $tota_amount = 0;
        $final_tota_amount = 0;
        $currency_symbol = get_settings('currency');
        foreach ($user_details as $row) {

            if (!empty($row['items'])) {
                $items = $row['items'];
                $items1 = '';
                $temp = '';
                $total_amt = $total_qty = 0;
                $download_allowed = array_values(array_unique(array_column($items, "download_allowed")));

                foreach ($items as $item) {
                    $product_variants = get_variants_values_by_id($item['product_variant_id']);
                    $variants = isset($product_variants[0]['variant_values']) && !empty($product_variants[0]['variant_values']) ? str_replace(',', ' | ', $product_variants[0]['variant_values']) : '-';
                    $temp .= "<b>ID :</b>" . $item['id'] . "<b> Product Variant Id :</b> " . $item['product_variant_id'] . "<b> Variants :</b> " . $variants . "<b> Name : </b>" . $item['name'] . " <b>Price : </b>" . $item['price'] . " <b>QTY : </b>" . $item['quantity'] . " <b>Subtotal : </b>" . $item['quantity'] * $item['price'] . "<br>------<br>";
                    $total_amt += $item['sub_total'];
                    $total_qty += $item['quantity'];
                }

                $items1 = $temp;
                $discounted_amount = $row['total'] * $row['items'][0]['discount'] / 100;
                $final_total = $row['total'] - $discounted_amount;
                $discount_in_rupees = $row['total'] - $final_total;
                $discount_in_rupees = floor($discount_in_rupees);
                $tempRow['id'] = $row['id'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['name'] = $row['items'][0]['uname'];
                if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $tempRow['mobile'] = str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3);
                } else {
                    $tempRow['mobile'] = $row['mobile'];
                }
                $tempRow['delivery_charge'] = $currency_symbol . ' ' . $row['delivery_charge'];
                $tempRow['items'] = $items1;
                $tempRow['total'] = $currency_symbol . ' ' . $row['total'];
                $tota_amount += intval($row['total']);
                $tempRow['wallet_balance'] = $currency_symbol . ' ' . $row['wallet_balance'];
                $tempRow['discount'] = $currency_symbol . ' ' . $discount_in_rupees . '(' . $row['items'][0]['discount'] . '%)';
                $tempRow['promo_discount'] = $currency_symbol . ' ' . $row['promo_discount'];
                $tempRow['promo_code'] = $row['promo_code'];
                $tempRow['notes'] = $row['notes'];
                $tempRow['qty'] = $total_qty;
                $tempRow['final_total'] = $currency_symbol . ' ' . $row['total_payable'];
                $final_total = $row['final_total'] - $row['wallet_balance'] - $row['discount'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $final_total;
                $final_tota_amount += intval($row['final_total']);
                $tempRow['deliver_by'] = $row['delivery_boy'];
                $tempRow['payment_method'] = $row['payment_method'];
                $updated_username = fetch_details('users', 'id =' . $row['items'][0]['updated_by'], 'username');
                $tempRow['updated_by'] = $updated_username[0]['username'];
                $tempRow['address'] = output_escaping(str_replace('\r\n', '</br>', $row['address']));
                $tempRow['delivery_date'] = $row['delivery_date'];
                $tempRow['delivery_time'] = $row['delivery_time'];
                $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));
                $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . '" class="btn action-btn btn-primary btn-xs mr-1 mb-1" title="View" ><i class="fa fa-eye"></i></a>';
                if (!$this->ion_auth->is_delivery_boy()) {
                    $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn action-btn btn-primary btn-xs mr-1 mb-1" title="View" ><i class="fa fa-eye"></i></a>';
                    $operate .= '<a href="javascript:void(0)" class="delete-orders btn action-btn btn-danger btn-xs mr-1 mb-1" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i></a>';
                    $operate .= '<a href="' . base_url() . 'admin/invoice?edit_id=' . $row['id'] . '" class="btn action-btn btn-info btn-xs mr-1 mb-1" title="Invoice" ><i class="fa fa-file"></i></a>';
                } else {
                    $operate = '<a href=' . base_url('delivery_boy/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn action-btn btn-primary btn-xs mr-1 mb-1" title="View"><i class="fa fa-eye"></i></a>';
                }
                $tempRow['operate'] = $operate;
                if (in_array(0, $download_allowed)) {
                    $send_mail = '<a href="javascript:void(0)" class="edit_btn btn btn-primary btn-xs mr-1 mb-1" title="Edit" data-id="' . $row['id'] . '" data-url="admin/orders/digital_product_orders/"><i class="fas fa-paper-plane"></i></a></div>';
                }
                $tempRow['send_mail'] = $send_mail;
                $rows[] = $tempRow;
            }
        }
        if (!empty($user_details)) {
            $tempRow['id'] = '-';
            $tempRow['user_id'] = '-';
            $tempRow['name'] = '-';
            $tempRow['mobile'] = '-';
            $tempRow['delivery_charge'] = '-';
            $tempRow['items'] = '-';
            $tempRow['total'] = '<span class="badge bg-danger">' . $currency_symbol . ' ' . $tota_amount . '</span>';
            $tempRow['wallet_balance'] = '-';
            $tempRow['discount'] = '-';
            $tempRow['qty'] = '-';
            $tempRow['final_total'] = '<span class="badge bg-danger">' . $currency_symbol . ' ' . $final_tota_amount . '</span>';
            $tempRow['deliver_by'] = '-';
            $tempRow['payment_method'] = '-';
            $tempRow['address'] = '-';
            $tempRow['delivery_time'] = '-';
            $tempRow['status'] = '-';
            $tempRow['active_status'] = '-';
            $tempRow['wallet_balance'] = '-';
            $tempRow['date_added'] = '-';
            $tempRow['operate'] = '-';
            $tempRow['send_mail'] = '-';
            array_push($rows, $tempRow);
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    public function get_digital_product_order_items_list($delivery_boy_id = NULL, $offset = 0, $limit = 10, $sort = " o.id ", $order = 'ASC')
    {
        $customer_privacy = false;


        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $filters = [
                'un.username' => $search,
                'u.username' => $search,
                'us.username' => $search,
                'un.email' => $search,
                'oi.id' => $search,
                'o.mobile' => $search,
                'o.address' => $search,
                'o.payment_method' => $search,
                'oi.sub_total' => $search,
                'o.delivery_time' => $search,
                'oi.active_status' => $search,
                'oi.date_added' => $search
            ];
        }


        $count_res = $this->db->select(' COUNT(o.id) as `total`,p.type')
            ->join(' `orders` o', 'o.id= oi.order_id')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users un ', ' un.id = o.user_id', 'left');
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $count_res->where(" DATE(oi.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(oi.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }
        $count_res->where("p.type", 'digital_product');

        if (isset($delivery_boy_id)) {
            $count_res->where("o.delivery_boy_id", $delivery_boy_id);
        }



        if (isset($_GET['user_id']) && $_GET['user_id'] != null) {
            $count_res->where("o.user_id", $_GET['user_id']);
        }



        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            $count_res->where('oi.active_status', $_GET['order_status']);
        }
        // Filter By payment
        if (isset($_GET['payment_method']) && !empty($_GET['payment_method'])) {
            $count_res->where('payment_method', $_GET['payment_method']);
        }

        $product_count = $count_res->get('order_items oi')->result_array();
        foreach ($product_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' o.id as order_id,oi.id as order_item_id,o.*,oi.*,ot.courier_agency,ot.tracking_id,ot.url,p.type,p.download_allowed, un.username as username')
            ->join('order_tracking ot ', ' ot.order_item_id = oi.id', 'left')
            ->join('orders o', 'o.id= oi.order_id')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users un ', ' un.id = o.user_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(oi.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(oi.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }
        $search_res->where("p.type", 'digital_product');
        if (isset($delivery_boy_id)) {
            $search_res->where("o.delivery_boy_id", $delivery_boy_id);
        }



        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $search_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            $search_res->where('oi.active_status', $_GET['order_status']);
        }
        // Filter By payment
        if (isset($_GET['payment_method']) && !empty($_GET['payment_method'])) {
            $count_res->where('payment_method', $_GET['payment_method']);
        }
        $user_details = $search_res->order_by($sort, "DESC")->limit($limit, $offset)->get('order_items oi')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $tota_amount = 0;
        $final_tota_amount = 0;
        $currency_symbol = get_settings('currency');
        $count = 1;
        foreach ($user_details as $row) {

            $temp = '';
            if (!empty($row['items'][0]['order_status'])) {
                $status = json_decode($row['items'][0]['order_status'], 1);
                foreach ($status as $st) {
                    $temp .= @$st[0] . " : " . @$st[1] . "<br>------<br>";
                }
            }

            if (trim($row['active_status']) == 'awaiting') {
                $active_status = '<label class="badge bg-secondary">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'received') {
                $active_status = '<label class="badge bg-primary">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'processed') {
                $active_status = '<label class="badge bg-info">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'shipped') {
                $active_status = '<label class="badge bg-warning">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'delivered') {
                $active_status = '<label class="badge bg-success">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'returned' || $row['active_status'] == 'cancelled') {
                $active_status = '<label class="badge bg-danger">' . $row['active_status'] . '</label>';
            }

            $status = $temp;
            $tempRow['id'] = $count;
            $tempRow['order_id'] = $row['order_id'];
            $tempRow['order_item_id'] = $row['order_item_id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['notes'] = (isset($row['notes']) && !empty($row['notes'])) ? $row['notes'] : "";
            $tempRow['username'] = $row['username'];
            $tempRow['is_credited'] = ($row['is_credited']) ? '<label class="badge bg-success">Credited</label>' : '<label class="badge bg-danger">Not Credited</label>';
            $tempRow['product_name'] = $row['product_name'];
            $tempRow['product_name'] .= (!empty($row['variant_name'])) ? '(' . $row['variant_name'] . ')' : "";
            $tempRow['sub_total'] = $currency_symbol . ' ' . $row['sub_total'];
            $tempRow['quantity'] = $row['quantity'];
            $final_tota_amount += intval($row['sub_total']);
            $tempRow['delivery_boy'] = $row['delivery_boy'];
            $tempRow['payment_method'] = $row['payment_method'];
            $tempRow['delivery_boy_id'] = $row['delivery_boy_id'];
            $tempRow['product_variant_id'] = $row['product_variant_id'];
            $tempRow['delivery_date'] = $row['delivery_date'];
            $tempRow['delivery_time'] = $row['delivery_time'];
            $tempRow['courier_agency'] = (isset($row['courier_agency']) && !empty($row['courier_agency'])) ? $row['courier_agency'] : "";
            $tempRow['tracking_id'] = (isset($row['tracking_id']) && !empty($row['tracking_id'])) ? $row['tracking_id'] : "";
            $tempRow['url'] = (isset($row['url']) && !empty($row['url'])) ? $row['url'] : "";
            $updated_username = fetch_details('users', 'id =' . $row['updated_by'], 'username');
            $tempRow['updated_by'] = $updated_username[0]['username'];
            $tempRow['status'] = $status;
            $tempRow['active_status'] = $active_status;
            $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));
            $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['order_id'] . '" class="btn action-btn btn-primary btn-xs mr-1 mb-1" title="View" ><i class="fa fa-eye"></i></a>';
            if ($this->ion_auth->is_delivery_boy()) {
                $operate = '<a href=' . base_url('delivery_boy/orders/edit_orders') . '?edit_id=' . $row['order_id'] . ' class="btn action-btn btn-primary btn-xs mr-1 mb-1" title="View"><i class="fa fa-eye"></i></a>';
            } else if ($this->ion_auth->is_admin()) {
                $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['order_id'] . ' class="btn action-btn btn-primary btn-xs mr-1 mb-1" title="View" ><i class="fa fa-eye"></i></a>';
                $operate .= '<a href="javascript:void(0)" class="delete-order-items btn action-btn btn-danger btn-xs mr-1 mb-1" data-id=' . $row['order_item_id'] . ' title="Delete" ><i class="fa fa-trash"></i></a>';
                $operate .= '<a href="' . base_url() . 'admin/invoice?edit_id=' . $row['order_id'] . '" class="btn action-btn btn-info btn-xs mr-1 mb-1" title="Invoice" ><i class="fa fa-file"></i></a>';
                if ($row['download_allowed'] == 0) {
                    $send_mail = '<a href="javascript:void(0)" class="edit_btn btn btn-primary btn-xs mr-1 mb-1" title="Edit" data-id="' . $row['order_id'] . '" data-url="admin/orders/digital_product_orders/"><i class="fas fa-paper-plane"></i></a>';
                    $send_mail .= '<a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=' . $row['email'] . '" class="btn btn-danger btn-xs mr-1 mb-1" target="_blank"><i class="fab fa-google"></i></a>';
                }
            } else {
                $operate = "";
            }
            $tempRow['operate'] = $operate;
            $tempRow['send_mail'] = $send_mail;

            $rows[] = $tempRow;
            $count++;
        }
        if (!empty($user_details)) {
            $tempRow['id'] = '-';
            $tempRow['order_id'] = '-';
            $tempRow['order_item_id'] = '-';
            $tempRow['user_id'] = '-';
            $tempRow['username'] = '-';

            $tempRow['is_credited'] = '-';
            $tempRow['mobile'] = '-';
            $tempRow['delivery_charge'] = '-';
            $tempRow['product_name'] = '-';
            $tempRow['sub_total'] = '<span class="badge bg-danger">' . $currency_symbol . ' ' . $final_tota_amount . '</span>';
            $tempRow['discount'] = '-';
            $tempRow['quantity'] = '-';
            $tempRow['delivery_boy'] = '-';
            $tempRow['delivery_time'] = '-';
            $tempRow['status'] = '-';
            $tempRow['active_status'] = '-';
            $tempRow['date_added'] = '-';
            $tempRow['operate'] = '-';
            $tempRow['send_mail'] = '-';
            array_push($rows, $tempRow);
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    public function add_bank_transfer_proof($data)
    {
        $data = escape_array($data);
        for ($i = 0; $i < count($data['attachments']); $i++) {
            $order_data = [
                'order_id' => $data['order_id'],
                'attachments' => $data['attachments'][$i],
            ];
            $this->db->insert('order_bank_transfer', $order_data);
        }
        return true;
    }

    public function get_order_tracking_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $multipleWhere = '';
        $where = [];

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
            $multipleWhere = ['`id`' => $search, '`order_id`' => $search, '`tracking_id`' => $search, 'courier_agency' => $search, 'url' => $search];
        }
        if (isset($_GET['order_id']) and $_GET['order_id'] != '') {
            $where = ['order_id' => $_GET['order_id']];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_Start();
            $count_res->or_like($multipleWhere);
            $this->db->group_End();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }


        $txn_count = $count_res->get('order_tracking')->result_array();

        foreach ($txn_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_Start();
            $search_res->or_like($multipleWhere);
            $this->db->group_End();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $txn_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('order_tracking')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($txn_search_res as $row) {
            $row = output_escaping($row);
            if ($this->ion_auth->is_admin()) {
                $operate = '<div class="dropdown">
                <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['order_id'] . '><i class="fa fa-eye"></i> View Order</a>
                 
                </div>
              </div>';
            } else {
                $operate = "";
            }

            $tempRow['id'] = $row['id'];
            $tempRow['order_id'] = $row['order_id'];
            $tempRow['courier_agency'] = $row['courier_agency'];
            $tempRow['tracking_id'] = $row['tracking_id'];
            $tempRow['url'] = $row['url'];
            $tempRow['date'] = date('d-m-Y', strtotime($row['date_created']));
            $tempRow['operate'] = $operate;

            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_digital_order_mail_list($from_app = false)
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $multipleWhere = '';
        $where = [];

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
            $multipleWhere = ['`id`' => $search, '`order_id`' => $search, '`order_item_id`' => $search, 'subject' => $search, 'message' => $search, 'file_url' => $search];
        }
        if (isset($_GET['order_id']) and $_GET['order_id'] != '') {
            $where = ['order_id' => $_GET['order_id']];
        }

        if (isset($_POST['order_id']) and $_POST['order_id'] != '') {
            $where = ['order_id' => $_POST['order_id']];
        }

        if (isset($_GET['order_item_id']) and $_GET['order_item_id'] != '') {
            $where = ['order_item_id' => $_GET['order_item_id']];
        }


        if (isset($_POST['order_item_id']) and $_POST['order_item_id'] != '') {
            $where = ['order_item_id' => $_POST['order_item_id']];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_Start();
            $count_res->or_like($multipleWhere);
            $this->db->group_End();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }


        $txn_count = $count_res->get('digital_orders_mails')->result_array();

        foreach ($txn_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_Start();
            $search_res->or_like($multipleWhere);
            $this->db->group_End();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $txn_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('digital_orders_mails')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($txn_search_res as $row) {
            $row = output_escaping($row);

            $tempRow['id'] = $row['id'];
            $tempRow['order_id'] = $row['order_id'];
            $tempRow['order_item_id'] = $row['order_item_id'];
            $tempRow['subject'] = $row['subject'];
            $tempRow['message'] = description_word_limit(output_escaping(str_replace('\r\n', '&#13;&#10;', $row['message'])));
            $tempRow['file_url'] = $row['file_url'];
            $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        if ($from_app == true) {
            return $bulkData;
        } else {
            print_r(json_encode($bulkData));
        }
    }

    public function get_order_tracking($limit = "", $offset = '', $sort = 'id', $order = 'DESC', $search = NULL)
    {

        $multipleWhere = '';

        if (isset($search) and $search != '') {
            $multipleWhere = ['ot.id' => $search, 'ot.order_id' => $search, 'tracking_id' => $search, 'courier_agency' => $search, 'url' => $search];
        }

        $count_res = $this->db->select(' COUNT(ot.id) as `total` ')->join("orders o", "o.id=ot.order_id");

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }
        $attr_count = $count_res->get('order_tracking ot')->result_array();

        foreach ($attr_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select('ot.*')->join("orders o", "o.id=ot.order_id");
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $city_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('order_tracking ot')->result_array();
        $bulkData = array();
        $bulkData['error'] = (empty($city_search_res)) ? true : false;
        $bulkData['message'] = (empty($city_search_res)) ? 'Order Tracking details does not exist' : 'Order Tracking details are retrieve successfully';
        $bulkData['total'] = (empty($city_search_res)) ? 0 : $total;
        $rows = $tempRow = array();

        foreach ($city_search_res as $row) {
            $tempRow['id'] = $row['id'];
            $tempRow['order_id'] = $row['order_id'];
            $tempRow['courier_agency'] = $row['courier_agency'];
            $tempRow['tracking_id'] = $row['tracking_id'];
            $tempRow['url'] = $row['url'];
            $tempRow['date'] = $row['date_created'];
            $rows[] = $tempRow;
        }
        $bulkData['data'] = $rows;
        return $bulkData;
    }

    function create_shiprocket_order($data)
    {
        $this->load->library(['Shiprocket']);

        $order_items = $data['order_items'];
        $items = [];
        $subtotal = 0;
        $order_id = 0;

        $pickup_location_pincode = fetch_details('pickup_locations', ['pickup_location' => $data['pickup_location']], 'pin_code');
        $user_data = fetch_details('users', ['id' => $data['user_id']], 'username,email');
        $order_data = fetch_details('orders', ['id' => $data['order_id']], 'date_added,address_id,mobile,payment_method');
        $address_data = fetch_details('addresses', ['id' => $order_data[0]['address_id']], 'address,city_id,pincode,state,country');
        $city_data = fetch_details('cities', ['id' => $address_data[0]['city_id']], 'name');

        $availibility_data = [
            'pickup_postcode' => $pickup_location_pincode[0]['pin_code'],
            'delivery_postcode' => $address_data[0]['pincode'],
            'cod' => ($order_data[0]['payment_method'] == 'COD') ? '1' : '0',
            'weight' => $data['parcel_weight'],
        ];

        $check_deliveribility = $this->shiprocket->check_serviceability($availibility_data);
        $get_currier_id = shiprocket_recomended_data($check_deliveribility);

        foreach ($order_items as $row) {
            if ($row['pickup_location'] == $data['pickup_location']) {
                $order_item_id[] = $row['id'];
                $order_id .= '-' . $row['id'];
                $order_item_data = fetch_details('order_items', ['id' => $row['id']], 'sub_total');
                $subtotal += $order_item_data[0]['sub_total'];
                if (isset($row['product_variants']) && !empty($row['product_variants'])) {
                    $sku = $row['product_variants'][0]['sku'];
                } else {
                    $sku = $row['sku'];
                }
                $temp['name'] = $row['pname'];
                $temp['sku'] = $sku;
                $temp['units'] = $row['quantity'];
                $temp['selling_price'] = $row['price'];
                $temp['discount'] = $row['discounted_price'];
                $temp['tax'] = $row['tax_amount'];
                array_push($items, $temp);
            }
        }
        $order_item_ids = implode(",", $order_item_id);

        $create_order = [
            'order_id' => $data['order_id'] . $order_id,
            'order_date' => $order_data[0]['date_added'],
            'pickup_location' => $data['pickup_location'],
            'billing_customer_name' => $user_data[0]['username'],
            'billing_last_name' => "",
            'billing_address' => $address_data[0]['address'],
            'billing_city' => $city_data[0]['name'],
            'billing_pincode' => $address_data[0]['pincode'],
            'billing_state' => $address_data[0]['state'],
            'billing_country' => $address_data[0]['country'],
            'billing_email' => $user_data[0]['email'],
            'billing_phone' => $order_data[0]['mobile'],
            'shipping_is_billing' => true,
            'order_items' => $items,
            'payment_method' => $order_data[0]['payment_method'],
            'sub_total' => $subtotal,
            'length' => $data['parcel_length'],
            'breadth' => $data['parcel_breadth'],
            'height' => $data['parcel_height'],
            'weight' => $data['parcel_weight'],
        ];

        $response = $this->shiprocket->create_order($create_order);

        if (isset($response['status_code']) && $response['status_code'] == 1) {
            $courier_company_id = $get_currier_id['courier_company_id'];
            $order_tracking_data = [
                'order_id' => $data['order_id'],
                'order_item_id' => $order_item_ids,
                'shiprocket_order_id' => $response['order_id'],
                'shipment_id' => $response['shipment_id'],
                'courier_company_id' => $courier_company_id,
                'pickup_status' => 0,
                'pickup_scheduled_date' => '',
                'pickup_token_number' => '',
                'status' => 0,
                'pickup_generated_date' => '',
                'data' => '',
                'date' => '',
                'manifest_url' => '',
                'label_url' => '',
                'invoice_url' => '',
                'is_canceled' => 0,
                'tracking_id' => '',
                'url' => ''
            ];
            $this->db->insert('order_tracking', $order_tracking_data);

            return $response;
        }
    }

    public function send_digital_product($data)
    {
        $message = str_replace('\r\n\\', '&#13;&#10;', $data['message']);
        $data = escape_array($data);
        $attachment = base_url($data['pro_input_file']);
        $to = $data['email'];
        $subject = $data['subject'];
        $email_message = array(
            'username' => 'Hello, Dear <b>' . ucfirst($data['username']) . '</b>, ',
            'subject' => $subject,
            'email' => 'email : ' . $to,
            'message' => $message
        );
        $mail = send_digital_product_mail($to, $subject, $this->load->view('admin/pages/view/contact-email-template', $email_message, TRUE), $attachment);
        return $mail;
    }

    public function get_order_items_list($delivery_boy_id = NULL, $offset = 0, $limit = 10, $sort = " o.id ", $order = 'ASC')
    {
        $customer_privacy = false;

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $filters = [
                'un.username' => $search,
                'db.username' => $search,
                'p.name' => $search,
                'un.email' => $search,
                'oi.id' => $search,
                'o.id' => $search,
                'o.mobile' => $search,
                'o.address' => $search,
                'o.payment_method' => $search,
                'oi.sub_total' => $search,
                'o.delivery_time' => $search,
                'oi.active_status' => $search,
                'oi.date_added' => $search
            ];
        }

        $count_res = $this->db->select(' COUNT(o.id) as `total`,p.type')
            ->join(' `orders` o', 'o.id= oi.order_id')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users db ', ' db.id = oi.deliver_by', 'left')
            ->join('users un ', ' un.id = o.user_id', 'left');


        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $count_res->where(" DATE(oi.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(oi.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }

        if (isset($delivery_boy_id)) {
            $count_res->where("delivery_boy_id", $delivery_boy_id);
        }



        if (isset($_GET['user_id']) && $_GET['user_id'] != null) {
            $count_res->where("o.user_id", $_GET['user_id']);
        }



        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            $count_res->where('oi.active_status', $_GET['order_status']);
        }
        // Filter By payment
        if (isset($_GET['payment_method']) && !empty($_GET['payment_method'])) {
            $count_res->where('payment_method', $_GET['payment_method']);
        }

        if (isset($_GET['delivery_boy']) && !empty($_GET['delivery_boy'])) {
            $count_res->where('delivery_boy_id', $_GET['delivery_boy']);
        }

        // Filter By order type
        if (isset($_GET['order_type']) && !empty($_GET['order_type']) && $_GET['order_type'] == 'physical_order') {
            $count_res->where('p.type!=', 'digital_product');
        }
        if (isset($_GET['order_type']) && !empty($_GET['order_type']) && $_GET['order_type'] == 'digital_order') {
            $count_res->where('p.type', 'digital_product');
        }

        $product_count = $count_res->get('order_items oi')->result_array();
        foreach ($product_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' o.id as order_id,oi.id as order_item_id,o.*,oi.*,ot.courier_agency,ot.tracking_id,ot.url,t.status as transaction_status, un.username as username,p.download_allowed,p.type')
            ->join(' `users` db ', 'db.id = oi.deliver_by', 'left')
            ->join('order_tracking ot ', ' ot.order_item_id = oi.id', 'left')
            ->join('orders o', 'o.id= oi.order_id')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('transactions t ', ' t.order_item_id = oi.id ', 'left')
            ->join('users un ', ' un.id = o.user_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(oi.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(oi.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }


        if (isset($delivery_boy_id)) {
            $search_res->where("delivery_boy_id", $delivery_boy_id);
        }
        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $search_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            $search_res->where('oi.active_status', $_GET['order_status']);
        }
        // Filter By payment
        if (isset($_GET['payment_method']) && !empty($_GET['payment_method'])) {
            $count_res->where('payment_method', $_GET['payment_method']);
        }

        if (isset($_GET['delivery_boy']) && !empty($_GET['delivery_boy'])) {
            $count_res->where('delivery_boy_id', $_GET['delivery_boy']);
        }

        // Filter By order type
        if (isset($_GET['order_type']) && !empty($_GET['order_type']) && $_GET['order_type'] == 'physical_order') {
            $search_res->where('p.type!=', 'digital_product');
        }
        if (isset($_GET['order_type']) && !empty($_GET['order_type']) && $_GET['order_type'] == 'digital_order') {
            $search_res->where('p.type', 'digital_product');
        }


        $user_details = $search_res->order_by($sort, "DESC")->limit($limit, $offset)->get('order_items oi')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $tota_amount = 0;
        $final_tota_amount = 0;
        $currency_symbol = get_settings('currency');
        $count = 1;
        foreach ($user_details as $row) {
            $temp = '';
            if (!empty($row['items'][0]['order_status'])) {
                $status = json_decode($row['items'][0]['order_status'], 1);
                foreach ($status as $st) {
                    $temp .= @$st[0] . " : " . @$st[1] . "<br>------<br>";
                }
            }

            if (trim($row['active_status']) == 'awaiting') {
                $active_status = '<label class="badge bg-secondary">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'received') {
                $active_status = '<label class="badge bg-primary">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'processed') {
                $active_status = '<label class="badge bg-info">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'shipped') {
                $active_status = '<label class="badge bg-warning">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'delivered') {
                $active_status = '<label class="badge bg-success">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'returned' || $row['active_status'] == 'cancelled') {
                $active_status = '<label class="badge bg-danger">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'return_request_decline') {
                $active_status = '<label class="badge badge-danger">' . str_replace('_', ' ', $row['active_status']) . '</label>';
            }
            if ($row['active_status'] == 'return_request_approved') {
                $active_status = '<label class="badge badge-success">' . str_replace('_', ' ', $row['active_status']) . '</label>';
            }
            if ($row['active_status'] == 'return_request_pending') {
                $active_status = '<label class="badge badge-secondary">' . str_replace('_', ' ', $row['active_status']) . '</label>';
            }
            if ($row['type'] == 'digital_product' && $row['download_allowed'] == 0) {
                if ($row['is_sent'] == 1) {
                    $mail_status = '<label class="badge bg-success">SENT </label>';
                } else if ($row['is_sent'] == 0) {
                    $mail_status = '<label class="badge bg-danger">NOT SENT</label>';
                } else {
                    $mail_status = '';
                }
            } else {
                $mail_status = '';
            }
            $transaction_status = '<label class="badge bg-primary">' . $row['transaction_status'] . '</label>';
            $status = $temp;
            $tempRow['id'] = $count;
            $tempRow['order_id'] = $row['order_id'];
            $tempRow['order_item_id'] = $row['order_item_id'];
            $tempRow['user_id'] = $row['user_id'];

            $tempRow['notes'] = (isset($row['notes']) && !empty($row['notes'])) ? $row['notes'] : "";
            $tempRow['username'] = $row['username'];

            $tempRow['is_credited'] = ($row['is_credited']) ? '<label class="badge bg-success">Credited</label>' : '<label class="badge bg-danger">Not Credited</label>';
            $tempRow['product_name'] = $row['product_name'];
            $tempRow['product_name'] .= (!empty($row['variant_name'])) ? '(' . $row['variant_name'] . ')' : "";
            if ((ALLOW_MODIFICATION == 0 && !defined(ALLOW_MODIFICATION)) && $customer_privacy == false) {
                $tempRow['mobile'] = $row['mobile'];
            } else {
                $tempRow['mobile'] = '<div class="d-flex gap-1 align-items-center justify-content-between">' . $row['mobile'] . "<a href='https://api.whatsapp.com/send?phone=" . $row['mobile'] . "&text=Hello " . $row['username'] . ", Your order with ID :" . $row['id'] . " and is " . $row['active_status'] . ". Please take a note of it. If you have further queries feel free to contact us. Thank you.' target='_blank' title='Send Whatsapp Notification' class='btn btn-success btn-xs rounded-1 py-1'><ion-icon class='align-bottom fs-3' name='logo-whatsapp'></ion-icon></a></div>";
            }
            $tempRow['sub_total'] = $currency_symbol . ' ' . $row['sub_total'];
            $tempRow['quantity'] = $row['quantity'];
            $final_tota_amount += intval($row['sub_total']);
            $tempRow['delivery_boy'] = $row['delivery_boy'];
            $tempRow['payment_method'] = $row['payment_method'];
            $tempRow['delivery_boy_id'] = $row['delivery_boy_id'];
            $tempRow['product_variant_id'] = $row['product_variant_id'];
            $tempRow['delivery_date'] = $row['delivery_date'];
            $tempRow['delivery_time'] = $row['delivery_time'];
            $tempRow['courier_agency'] = (isset($row['courier_agency']) && !empty($row['courier_agency'])) ? $row['courier_agency'] : "";
            $tempRow['tracking_id'] = (isset($row['tracking_id']) && !empty($row['tracking_id'])) ? $row['tracking_id'] : "";
            $tempRow['url'] = (isset($row['url']) && !empty($row['url'])) ? $row['url'] : "";
            $updated_username = fetch_details('users', 'id =' . $row['updated_by'], 'username');
            $tempRow['updated_by'] = $updated_username[0]['username'];
            $tempRow['status'] = $status;
            $tempRow['transaction_status'] = $transaction_status;
            $tempRow['active_status'] = $active_status;
            $tempRow['mail_status'] = $mail_status;
            $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));
            $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['order_id'] . '" class="btn action-btn btn-primary btn-xs mr-1 mb-1 ml-1" title="View" ><i class="fa fa-eye"></i></a>';
            if ($this->ion_auth->is_delivery_boy()) {
                $operate = '<a href=' . base_url('delivery_boy/orders/edit_orders') . '?edit_id=' . $row['order_id'] . ' class="btn action-btn btn-primary btn-xs mr-1 mb-1" title="View"><i class="fa fa-eye"></i></a>';
            } else if ($this->ion_auth->is_admin()) {
                $operate = '<div class="dropdown">
                <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['order_id'] . '><i class="fa fa-eye"></i> View Order</a>
                  <a href="' . base_url() . 'admin/invoice?edit_id=' . $row['order_id'] . '" class="dropdown-item" title="Invoice" ><i class="fa fa-file"></i> Invoice</a>

                  <a href="javascript:void(0)" class="delete-brand dropdown-item" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i> Delete</a>';

                if ($row['type'] != 'digital_product') {
                    $operate .= ' <a href="javascript:void(0)" class="edit_order_tracking dropdown-item" title="Order Tracking" data-order_id="' . $row['order_id'] . '" data-order_item_id="' . $row['order_item_id'] . '" data-courier_agency="' . $row['courier_agency'] . '"  data-tracking_id="' . $row['tracking_id'] . '" data-url="' . $row['url'] . '" data-target="#transaction_modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i> Order Tracking</a>';
                }

                if ($row['download_allowed'] == 0 && $row['type'] == 'digital_product') {
                    $operate .= '<div><a href="javascript:void(0)" class="edit_btn dropdown-item" title="Edit" data-id="' . $row['order_item_id'] . '" data-url="admin/orders/"><i class="fas fa-paper-plane"></i> Edit</a>';

                    $operate .= '<a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=' . $row['email'] . '" class="dropdown-item" target="_blank"><i class="fab fa-google"></i> Send mail</a>';
                    $operate .= ' <a href="javascript:void(0)" class="edit_digital_order_mails dropdown-item" title="Digital Order Mails" data-order_item_id="' . $row['order_item_id'] . '"  data-target="#digital-order-mails" data-toggle="modal"><i class="far fa-envelope-open"></i> Digital Order Mails</a></div>';
                }
                '</div>';
            } else {
                $operate = "";
            }
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }
        if (!empty($user_details)) {
            $tempRow['id'] = '-';
            $tempRow['order_id'] = '-';
            $tempRow['order_item_id'] = '-';
            $tempRow['user_id'] = '-';

            $tempRow['username'] = '-';

            $tempRow['is_credited'] = '-';
            $tempRow['mobile'] = '-';
            $tempRow['delivery_charge'] = '-';
            $tempRow['product_name'] = '-';
            $tempRow['sub_total'] = '<span class="badge bg-danger">' . $currency_symbol . ' ' . $final_tota_amount . '</span>';
            $tempRow['discount'] = '-';
            $tempRow['quantity'] = '-';
            $tempRow['delivery_boy'] = '-';
            $tempRow['delivery_time'] = '-';
            $tempRow['status'] = '-';
            $tempRow['active_status'] = '-';
            $tempRow['transaction_status'] = '-';
            $tempRow['date_added'] = '-';
            $tempRow['operate'] = '-';
            $tempRow['mail_status'] = '-';
            array_push($rows, $tempRow);
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function send_order_notifications($order_id)
    {
        $order = fetch_orders($order_id);
        if (empty($order) || empty($order['order_data']))
            return;
        $order_data = $order['order_data'][0];
        $items = $order_data['order_items'];
        $user_id = $order_data['user_id'];
        $user = fetch_details('users', ['id' => $user_id]);
        $system_settings = get_settings('system_settings', true);
        // Reconstruct $cart_data
        $cart_data = [];
        $total_tax_amount = 0;
        $total_tax_percentage = 0;
        $otp = 0;
        foreach ($items as $item) {
            $cart_data[] = [
                'name' => $item['product_name'],
                'tax_amount' => $item['tax_amount'],
                'qty' => $item['quantity'],
                'sub_total' => $item['sub_total']
            ];
            $total_tax_amount += $item['tax_amount'];
            $total_tax_percentage += $item['tax_percent'];
            if (isset($item['otp']) && $item['otp'] != 0) {
                $otp = $item['otp'];
            }
        }
        $overall_total = [
            'total_amount' => $order_data['total'],
            'delivery_charge' => $order_data['delivery_charge'],
            'discount' => $order_data['promo_discount'],
            'tax_amount' => $total_tax_amount,
            'tax_percentage' => $total_tax_percentage,
            'wallet' => $order_data['wallet_balance'],
            'final_total' => $order_data['final_total'],
            'total_payable' => $order_data['total_payable'],
            'otp' => $otp,
            'address' => $order_data['address'],
            'payment_method' => $order_data['payment_method']
        ];
        $system_settings = get_settings('system_settings', true);
        $firebase_project_id_details = fetch_details('settings', ['variable' => 'firebase_project_id']);
        $service_account_file = fetch_details('settings', ['variable' => 'service_account_file']);
        $vap_id_Key = fetch_details('settings', ['variable' => 'vap_id_Key']);
        $custom_notification = fetch_details('custom_notifications', ['type' => "place_order"], '');
        //send custom notification message
        // Custom notification found, proceed with processing
        $hashtag_order_id = '< order_id >';
        $string = json_encode($custom_notification[0]['title'], JSON_UNESCAPED_UNICODE);
        $hashtag = html_entity_decode($string);
        $data1 = str_replace($hashtag_order_id, $order_id, $hashtag); // Fixed: use $order_id instead of undefined $last_order_id
        $title = output_escaping(trim($data1, '"'));
        $hashtag_application_name = '< application_name >';
        $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
        $hashtag = html_entity_decode($string);
        $data2 = str_replace($hashtag_application_name, $system_settings['app_name'], $hashtag);
        $message = output_escaping(trim($data2, '"'));
        $fcm_admin_subject = (!empty($custom_notification)) ? $title : 'New order placed ID #' . $order_id; // Fixed: use $order_id
        $fcm_admin_msg = (!empty($custom_notification)) ? $message : 'New order received for ' . $system_settings['app_name'] . ' please process it.';
        $user_data = fetch_details('users', ['id' => $order_data['user_id']]); // Fixed: use $order_data['user_id'] instead of undefined $data['user_id']
        $fcm_ids = []; // Initialize $fcm_ids
        if (isset($user_data[0]['fcm_id']) && !empty($user_data[0]['fcm_id'])) {
            $fcm_ids[0][] = $user_data[0]['fcm_id'];
            $fcmMsg = array(
                'title' => $fcm_admin_subject,
                'body' => $fcm_admin_msg,
                'type' => "place_order",
                // 'content_available' => true
            );
            log_message('warn', "No email found for user {$user_data[0]['fcm_id']}, skipping user email for order {$order_id}");


            if (
                isset($custom_notification) && !empty($custom_notification) && isset($firebase_project_id_details) && !empty($firebase_project_id_details) && isset($service_account_file) && !empty($service_account_file) &&
                isset($vap_id_Key) && !empty($vap_id_Key)
            ) {
                if (isset($order_data['active_status']) && $order_data['active_status'] != 'awaiting') {
                    try {
                        $result = send_notification($fcmMsg, $fcm_ids, $fcmMsg);
                        if (!$result) {
                            log_message('error', "Failed to send FCM notification for order {$order_id}");
                        }
                    } catch (Exception $e) {
                        log_message('error', "Exception sending FCM notification for order {$order_id}: " . $e->getMessage());
                    }
                }
            }
        }
        if (isset($system_settings['support_email']) && !empty($system_settings['support_email'])) {
            try {
                $result = send_mail($system_settings['support_email'], $fcm_admin_subject, $fcm_admin_msg, $system_settings['support_email']);
                if (!$result) {
                    log_message('error', "Failed to send admin email for order {$order_id}");
                }
            } catch (Exception $e) {
                log_message('error', "Exception sending admin email for order {$order_id}: " . $e->getMessage());
            }
        }

        // Send admin app notification
        $admin_users = $this->db->select('u.fcm_id')
            ->from('users u')
            ->join('users_groups ug', 'ug.user_id = u.id')
            ->join('groups g', 'g.id = ug.group_id')
            ->where('g.name', 'admin')
            ->where('u.active', 1)
            ->get()->result_array();

        $admin_fcm_ids = [];
        foreach ($admin_users as $admin) {
            if (!empty($admin['fcm_id']) && strlen($admin['fcm_id']) > 50) {
                $admin_fcm_ids[] = $admin['fcm_id'];
            }
        }

        if (!empty($admin_fcm_ids)) {
            $fcm_admin_msg_payload = array(
                'title' => (string) $fcm_admin_subject,
                'body' => (string) $fcm_admin_msg,
                'type' => "place_order",
                'type_id' => (string) $order_id,
            );

            // Chunk IDs if > 1000 (standard fcm limit)
            $admin_fcm_chunks = array_chunk($admin_fcm_ids, 1000);

            send_notification($fcm_admin_msg_payload, $admin_fcm_chunks, $fcm_admin_msg_payload, $fcm_admin_subject, $fcm_admin_msg, "place_order");
        }

        $admin_notifi = array(
            'title' => $fcm_admin_subject,
            'message' => $fcm_admin_msg,
            'type' => "place_order",
            'type_id' => $order_id // Fixed: use $order_id
        );

        try {
            $result = insert_details($admin_notifi, 'system_notification');
            if (!$result) {
                log_message('error', "Failed to insert admin panel notification for order {$order_id}");
            }
        } catch (Exception $e) {
            log_message('error', "Exception inserting admin panel notification for order {$order_id}: " . $e->getMessage());
        }

        $overall_order_data = array_merge($overall_total, ['cart_data' => $cart_data, 'order_id' => $order_id]);
        try {
            notify_event(
                "place_order",
                ["customer" => [$user_data[0]['email']]],
                ["customer" => [$user_data[0]['mobile']]],
                ["orders.id" => $order_id],
                $overall_order_data
            );
            log_message('info', "Event notification 'place_order' completed successfully for order {$order_id}");
        } catch (Exception $e) {
            log_message('error', "Exception notifying event 'place_order' for order {$order_id}: " . $e->getMessage());
        }
        // Send user email
        if (isset($user_data[0]['email']) && !empty($user_data[0]['email'])) {
            $email_subject = 'Order Confirmation - ' . $system_settings['app_name'];
            $fcm_user_msg = 'Your order has been placed successfully! Order ID: #' . $order_id . '. We will notify you once it is processed.';

            $overall_order_data = array(
                'rows' => $cart_data,
                'order_id' => $order_id,
                'order_data' => $overall_total,
                'subject' => $email_subject,
                'user_data' => $user_data[0],
                'system_settings' => $system_settings,
                'user_msg' => $fcm_user_msg,
                'otp_msg' => 'Here is your OTP. Please, give it to delivery boy only while getting your order.',
            );

            $email_body = $this->load->view('admin/pages/view/email-template.php', $overall_order_data, TRUE);


            try {
                $result = send_mail($user_data[0]['email'], $email_subject, $email_body);
                if (!$result) { // Assuming send_mail returns true on success
                    log_message('error', "Failed to send user email for order {$order_id}");
                }
            } catch (Exception $e) {
                log_message('error', "Exception sending user email for order {$order_id}: " . $e->getMessage());
            }
        } else {
            log_message('warn', "No email found for user {$order_data['user_id']}, skipping user email for order {$order_id}");

        }
    }
}
