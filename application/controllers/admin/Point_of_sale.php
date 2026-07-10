<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Point_of_sale extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation', 'upload', 'pagination']);
        $this->load->helper(['url', 'language', 'file']);
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->model(['point_of_sale_model', 'customer_model', 'ion_auth_model', 'transaction_model', 'order_model', 'category_model', 'cart_model']);
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (!has_permissions('read', 'point_of_sale')) {
                $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
                redirect('admin/home', 'refresh');
            }
        }
    }
    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = VIEW . 'point-of-sale';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Point of Sale | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Point of Sale |' . $settings['app_name'];
            $this->data['categories'] = $this->category_model->get_categories();
            $this->data['csrfName'] = $this->security->get_csrf_token_name();
            $this->data['csrfHash'] = $this->security->get_csrf_hash();
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }
    public function get_products()
    {
        $max_limit = 25;
        $category_id = (isset($_GET['category_id']) && !empty($_GET['category_id']) && is_numeric($_GET['category_id'])) ? $_GET['category_id'] : "";
        $limit = (isset($_GET['limit']) && !empty($_GET['limit']) && is_numeric($_GET['limit']) && $_GET['limit'] <= $max_limit) ? $_GET['limit'] : $max_limit;
        $offset = (isset($_GET['offset']) && !empty($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : 0;
        $sort = (isset($_GET['sort']) && !empty($_GET['sort'])) ? $_GET['sort'] : 'p.id';
        $order = (isset($_GET['order']) && !empty($_GET['order'])) ? $_GET['order'] : 'desc';
        $filter['search'] = (isset($_GET['search']) && !empty($_GET['search'])) ? $_GET['search'] : '';
        $products =  $this->data['products'] = fetch_product("", $filter, "", $category_id, $limit, $offset, $sort, $order);
        $response['error'] = (!empty($products)) ? false : true;
        $response['message'] = (!empty($products)) ? "Products fetched successfully" : "No products found";
        $response['products'] = (!empty($products)) ? $products : [];
        print_r(json_encode($response));
    }

    public function get_users()
    {
        $search = $this->input->get('search');
        $response = $this->point_of_sale_model->get_users($search);
        echo json_encode($response);
    }

    public function calculate_cart_total()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $cart_data = $this->input->post('cart_data', true);
            $response = ['error' => false, 'bulk_discount' => 0, 'cart_total' => 0, 'final_total' => 0];

            if (!empty($cart_data)) {
                $cart_data = json_decode($cart_data, true);

                if (!empty($cart_data)) {
                    $cart_items_by_variant = [];
                    foreach ($cart_data as $item) {
                        $cart_items_by_variant[$item['variant_id']] = $item;
                    }
                    $product_variant_ids = array_keys($cart_items_by_variant);

                    $cart_total = 0;
                    foreach ($cart_data as $item) {
                        $cart_total += floatval($item['display_price']) * intval($item['quantity']);
                    }

                    $bulk_discount = 0;
                    $bulk_discount_products = $this->db->select('p.id as product_id, p.bulk_discount_min_qty, p.bulk_discount_amount, pv.id as variant_id')
                        ->join('product_variants pv', 'p.id = pv.product_id')
                        ->where_in('pv.id', $product_variant_ids)
                        ->where('p.bulk_discount_min_qty > 0')
                        ->where('p.bulk_discount_amount > 0')
                        ->get('products p')
                        ->result_array();

                    if (!empty($bulk_discount_products)) {
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
                            $product_quantities[$pid] += intval($cart_items_by_variant[$vid]['quantity']);
                        }

                        foreach ($product_quantities as $pid => $total_qty) {
                            if ($total_qty >= $product_discount_info[$pid]['min_qty']) {
                                $bulk_discount += $product_discount_info[$pid]['amount'];
                            }
                        }
                    }

                    $final_total = max(0, $cart_total - $bulk_discount);


                    $response['bulk_discount'] = $bulk_discount;
                    $response['cart_total'] = $cart_total;
                    $response['final_total'] = $final_total;
                }
            }

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($response);
        } else {
            echo json_encode(['error' => true, 'message' => 'Unauthorized']);
        }
    }
    public function register_user()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (print_msg(!has_permissions('create', 'point_of_sale'), PERMISSION_ERROR_MSG, 'point_of_sale')) {
                return false;
            }
            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean|min_length[5]|numeric|is_unique[users.mobile]', array('is_unique' => ' The mobile number is already registered . Please login'));
            $this->form_validation->set_rules('password', 'Password', 'required|xss_clean|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']');
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            if ($this->form_validation->run() == false) {
                $this->response['error'] = true;
                $this->response['message'] = strip_tags(validation_errors());
                $this->response['data'] = array();
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
            } else {
                $identity_column = $this->config->item('identity', 'ion_auth');
                $mobile = $this->input->post('mobile');
                $password = $this->input->post('password');
                $identity =  $mobile;
                $additional_data = [
                    'username' => $this->input->post('name'),
                    'active' => 1,
                    'type' => 'phone',
                ];
                $res = $this->ion_auth->register($identity, $password, " ", $additional_data, ['2']);
                update_details(['active' => 1], [$identity_column => $identity], 'users');
                $data = $this->db->select('u.id,u.username,u.mobile')->where([$identity_column => $identity])->get('users u')->result_array();
                $this->response['error'] = (!empty($data)) ? false : true;
                $this->response['message'] = (!empty($data)) ? "Registered Successfully" : "Not Registered";
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = (!empty($data)) ? $data : [];
            }
            print_r(json_encode($this->response));
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function place_order()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (print_msg(!has_permissions('create', 'point_of_sale'), PERMISSION_ERROR_MSG, 'point_of_sale')) {
                return false;
            }

            $data = $this->input->post('data', true);
            if (!isset($data) || empty($data)) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }

            $post_data = json_decode($this->input->post('data', true), true);
            $user_id = $this->input->post('user_id', true);
            if (!isset($user_id) || empty($user_id)) {
                $this->response['error'] = true;
                $this->response['message'] = "Please select the customer!";
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }

            if (isset($post_data) && !empty($post_data)) {
                for ($i = 0; $i < count($post_data); $i++) {
                    if (!isset($post_data[$i]['variant_id']) || empty($post_data[$i]['variant_id'])) {
                        $this->response['error'] = true;
                        $this->response['message'] = "The variant ID field is required";
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['data'] = array();
                        print_r(json_encode($this->response));
                        return false;
                    }

                    if (!isset($post_data[$i]['quantity']) || empty($post_data[$i]['quantity'])) {
                        $this->response['error'] = true;
                        $this->response['message'] = "Please enter valid quantity for " . $post_data[$i]['title'];
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['data'] = array();
                        print_r(json_encode($this->response));
                        return false;
                    }
                }
            } else {
                $this->response['error'] = true;
                $this->response['message'] = "Cart is empty";
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            // creating arr for place order
            $product_variant_id = array_column($post_data, "variant_id");
            $quantity = array_column($post_data, "quantity");
            $user_id = $this->input->post('user_id', true);
            $self_pickup_input = $this->input->post('self_pickup', true);
            $self_pickup = isset($self_pickup_input) ? $self_pickup_input : 0;

            $place_order_data = array();
            $place_order_data['product_variant_id'] = implode(",", $product_variant_id);
            $place_order_data['quantity'] = implode(",", $quantity);
            $place_order_data['user_id'] = $user_id;
            $user_mobile = fetch_details("users", ['id' => $user_id], "mobile");
            $place_order_data['mobile'] = $user_mobile[0]['mobile'];
            $place_order_data['is_wallet_used'] = 0;
            $place_order_data['delivery_charge'] = $this->input->post('delivery_charges', true);
            $place_order_data['discount'] = $this->input->post('discount', true);
            $place_order_data['local_pickup'] = $self_pickup;
            $place_order_data['delivery_charge'] = 0;
            $place_order_data['is_delivery_charge_returnable'] = 0;
            $place_order_data['wallet_balance_used'] = 0;
            $place_order_data['active_status'] = "delivered";
            $payment_method_name = (isset($_POST['payment_method_name']) && !empty($_POST['payment_method_name'])) ? $this->input->post('payment_method_name', true) : NULL;
            $place_order_data['payment_method'] = (isset($_POST['payment_method']) && !empty($_POST['payment_method']) && $_POST['payment_method'] != "other") ? $this->input->post('payment_method', true) : $payment_method_name;
            $txn_id = (isset($_POST['txn_id']) && !empty($_POST['txn_id'])) ? $this->input->post('txn_id', true) : NULL;

            $check_current_stock_status = validate_stock($product_variant_id, $quantity);
            if ($check_current_stock_status['error'] == true) {
                $this->response['error'] = true;
                $this->response['message'] = $check_current_stock_status['message'];
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }

            $payment_method = $this->input->post('payment_method', true);
            $payment_method_name = $this->input->post('payment_method_name', true);
            if (isset($payment_method) && !empty($payment_method) && $payment_method == "other" && empty($payment_method_name)) {
                $this->response['error'] = true;
                $this->response['message'] = "Please enter payment method name";
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            for ($i = 0; $i < count($post_data); $i++) {
                $data = array(
                    'product_variant_id' => implode(",", $product_variant_id),
                    'qty' => implode(",", $quantity),
                    'user_id' => $user_id,
                );
                if ($this->cart_model->add_to_cart($data)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Item are Not Added";
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
            }
            $cart = get_cart_total($user_id, false, '0', "", true);
            if (empty($cart)) {
                $this->response['error'] = true;
                $this->response['message'] = "Your Cart is empty.";
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            $final_total = $cart['overall_amount'];

            // Calculate Custom Charges
            $settings = get_settings('system_settings', true);
            $custom_charges_pos = isset($settings['custom_charges_pos']) ? $settings['custom_charges_pos'] : '0';
            $custom_charges_total = 0;
            $custom_charges_data = [];

            if (isset($custom_charges_pos)) {
                $custom_charges = get_settings('custom_charges', true);
                
                if (is_string($custom_charges)) {
                    $custom_charges = json_decode($custom_charges, true);
                }

                if (!empty($custom_charges) && is_array($custom_charges)) {
                    foreach ($custom_charges as $charge) {
                        // Only include charges that have apply_pos enabled
                        if (!empty($charge['apply_pos']) && ($charge['apply_pos'] == '1' || $charge['apply_pos'] == 'on' || $charge['apply_pos'] === true)) {
                            $custom_charges_total += floatval($charge['amount']);
                            $custom_charges_data[] = [
                                'name' => $charge['name'],
                                'amount' => floatval($charge['amount'])
                            ];
                        }
                    }
                }
            }

            $final_total += $custom_charges_total;
            $place_order_data['final_total'] = $final_total;
            $place_order_data['custom_charges_total'] = $custom_charges_total;
            $place_order_data['custom_charges_json'] = json_encode($custom_charges_data);
            $place_order_data['is_pos_order'] = 1;
            $place_order_data['order_note'] = '';
            $res = $this->order_model->place_order($place_order_data);
            if (isset($res) && !empty($res)) {
                // Get the actual order details to ensure transaction amount matches order final_total
                $order_details = fetch_details('orders', ['id' => $res['order_id']], 'final_total');
                $actual_final_total = !empty($order_details) ? $order_details[0]['final_total'] : $final_total;
                
                // creating transaction record for card payments
                $trans_data = [
                    'transaction_type' => 'transaction',
                    'user_id' => $user_id,
                    'order_id' => $res['order_id'],
                    'type' => strtolower($place_order_data['payment_method']),
                    'txn_id' => $txn_id,
                    'amount' => $actual_final_total,
                    'status' => "success",
                    'message' => "Order delivered Successfully",
                ];
                $this->transaction_model->add_transaction($trans_data);
            }
            $data['order_id'] = $res['order_id'];
            $this->response['error'] = false;
            $this->response['message'] = "Order delivered Successfully.";
            update_details(['is_pos_order' => 1], ['id' => $res['order_id']], 'orders');
            $this->response['data'] = $res;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            print_r(json_encode($this->response));
            return false;
        } else {
            return false;
        }
    }
}
