<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Api extends CI_Controller
{

    /*
---------------------------------------------------------------------------
Defined Methods:-
---------------------------------------------------------------------------
1. login
2. get_delivery_boy_details
3. get_orders
4. get_fund_transfers
5. update_user
6. update_fcm
7. reset_password
8. get_notifications
9. verify_user
10. get_settings
11. send_withdrawal_request
12. get_withdrawal_request
13. update_order_status
14. update_order_item_status
---------------------------------------------------------------------------
*/

    private $user_details = [];

    protected $excluded_routes =
        [
            "delivery_boy/app/v1/api/login",
            "delivery_boy/app/v1/api/get_settings",
            "delivery_boy/app/v1/api/reset_password",
            "delivery_boy/app/v1/api/get_notifications",
            "delivery_boy/app/v1/api/verify_user",
            "delivery_boy/app/v1/api/verify_otp",
            "delivery_boy/app/v1/api/resend_otp",
            "delivery_boy/app/v1/api/get_settings",
            "delivery_boy/app/v1/api/register",
        ];

    public function __construct()
    {
        parent::__construct();
        header("Content-Type: application/json");
        header("Expires: 0");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $this->load->library(['upload', 'jwt', 'ion_auth', 'form_validation', 'paypal_lib', 'Key']);
        $this->load->model(['category_model', 'order_model', 'rating_model', 'cart_model', 'address_model', 'transaction_model', 'notification_model', 'delivery_boy_model', 'Order_model', 'Delivery_boy_model']);
        $this->load->helper(['language', 'string', 'file', 'function_helper', 'sms_helper']);
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
        $response = $temp = $bulkdata = array();
        $this->identity_column = $this->config->item('identity', 'ion_auth');
        // initialize db tables data
        $this->tables = $this->config->item('tables', 'ion_auth');

        $current_uri = uri_string();
        if (!in_array($current_uri, $this->excluded_routes)) {
            $token = verify_app_request();
            if ($token['error']) {
                header('Content-Type: application/json');
                http_response_code($token['status']);
                print_r(json_encode($token));
                die();
            }
            $this->user_details = $token['data'];
        }
    }


    public function index()
    {
        $this->load->helper('file');
        $this->output->set_content_type(get_mime_by_extension(base_url('api-doc.txt')));
        $this->output->set_output(file_get_contents(base_url('delivery-boy-api-doc.txt')));
    }

    public function generate_token()
    {
        $payload = [
            'iat' => time(), /* issued at time */
            'iss' => 'eshop',
            'exp' => time() + (60 * 60 * 24 * 365), /* expires after 1 minute */
            'sub' => 'eshop Authentication'
        ];
        $token = $this->jwt->encode($payload, JWT_SECRET_KEY);
        print_r(json_encode($token));
    }

    public function verify_token()
    {
        try {
            $token = $this->jwt->getBearerToken();
        } catch (Exception $e) {
            $response['error'] = true;
            $response['message'] = $e->getMessage();
            print_r(json_encode($response));
            return false;
        }

        if (!empty($token)) {

            $api_keys = fetch_details('client_api_keys', ['status' => 1]);
            if (empty($api_keys)) {
                $response['error'] = true;
                $response['message'] = 'No Client(s) Data Found !';
                print_r(json_encode($response));
                return false;
            }
            JWT::$leeway = 60;
            $flag = true; //For payload indication that it return some data or throws an expection.
            $error = true; //It will indicate that the payload had verified the signature and hash is valid or not.

            $message = '';
            try {
                $payload = $this->jwt->decode($token, new Key(JWT_SECRET_KEY, 'HS256'));
                if (isset($payload->iss) && $payload->iss == 'eshop') {
                    $error = false;
                    $flag = false;
                } else {
                    $error = true;
                    $flag = false;
                    $message = 'Invalid Hash';
                }
            } catch (Exception $e) {
                $message = $e->getMessage();
            }


            if ($flag) {
                $response['error'] = true;
                $response['message'] = $message;
                print_r(json_encode($response));
                return false;
            } else {
                if ($error == true) {
                    $response['error'] = true;
                    $response['message'] = $message;
                    print_r(json_encode($response));
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Unauthorized access not allowed";
            print_r(json_encode($response));
            return false;
        }
    }

    public function login()
    {
        /* Parameters to be passed
            mobile: 9874565478
            password: 12345678
            fcm_id: FCM_ID //{ optional }
        */

        $identity_column = $this->config->item('identity', 'ion_auth');
        if ($identity_column == 'mobile') {
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|numeric|required|xss_clean');
        } elseif ($identity_column == 'email') {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
        } else {
            $this->form_validation->set_rules('identity', 'Identity', 'trim|required|xss_clean');
        }
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('fcm_id', 'FCM ID', 'trim|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        }

        $login = $this->ion_auth->login($this->input->post('mobile'), $this->input->post('password'), false);
        if ($login) {
            $data = fetch_details('users', ['mobile' => $this->input->post('mobile', true)]);

            $existing_token = ($data[0]['apikey'] !== null && !empty($data[0]['apikey'])) ? $data[0]['apikey'] : "";


            if ($this->ion_auth->in_group('delivery_boy', $data[0]['id'])) {
                if (isset($_POST['fcm_id']) && $this->input->post('fcm_id', true) != '') {
                    update_details(['fcm_id' => $this->input->post('fcm_id', true)], ['mobile' => $this->input->post('mobile', true)], 'users');
                }

                if ($existing_token == '') {
                    $token = generate_token($this->input->post('mobile'));
                    update_details(['apikey' => $token], ['mobile' => $this->input->post('mobile')], "users");
                }
                /** set user jwt token  */

                unset($data[0]['password']);
                $data = array_map(function ($value) {
                    return $value === NULL ? "" : $value;
                }, $data[0]);
                //if the login is successful

                $messages = array("0" => "Your account is not yet approved.", "1" => "Logged in successfully");

                $response['error'] = ($data['status'] != "" && ($data['status'] != 0)) ? false : true;
                $response['message'] = ($data['status'] != "" && ($data['status'] != 0)) ? $messages[1] : $messages[0];
                $response['token'] = $existing_token !== "" ? $existing_token : $token;
                $response['data'] = (isset($data['status']) && $data['status'] != "" && ($data['status'] == 1)) ? $data : [];
                echo json_encode($response);
                return false;
            } else {
                $response['error'] = true;
                $response['message'] = 'Incorrect Login.';
                echo json_encode($response);
                return false;
            }
        } else {
            $response['error'] = true;
            $response['message'] = strip_tags($this->ion_auth->errors());
            echo json_encode($response);
            return false;
        }
    }

    public function get_delivery_boy_details()
    {
        /* id:28 */

        if (!$this->verify_token()) {
            echo json_encode(["error" => true, "message" => "Token verification failed"]);
            return false;
        }

        $user_id = $this->user_details['id'];
        if (empty($user_id)) {
            echo json_encode(["error" => true, "message" => "User ID is missing"]);
            return false;
        }

        //Add validation rules (before running validation)
        $this->form_validation->set_data(['id' => $user_id]);
        $this->form_validation->set_rules('id', 'User ID', 'trim|required|numeric');

        if (!$this->form_validation->run()) {
            echo json_encode([
                "error" => true,
                "message" => strip_tags(validation_errors()),
                "data" => []
            ]);
            return false;
        }

        // Fetch user details
        $data = fetch_details('users', ['id' => $user_id]);

        // Debug: Check what fetch_details returns
        if (empty($data)) {
            echo json_encode(["error" => true, "message" => "User not found", "data" => []]);
            return false;
        }

        // Fix array handling (no need for `$data[0]`)
        $data = array_map(function ($value) {
            return $value === NULL ? "" : $value;
        }, $data[0]);

        // Ensure `balance` and `bonus` are properly formatted
        $data['balance'] = !empty($data['balance']) ? $data['balance'] : "0";
        $data['bonus'] = !empty($data['bonus']) ? $data['bonus'] : "0";

        //  Process driving license data correctly
        $driving_license_data = [];
        if (!empty($data['driving_license'])) {
            $driving_license = explode(',', $data['driving_license']);
            foreach ($driving_license as $row) {
                $driving_license_data[] = base_url($row);
            }
        }
        $data['driving_license'] = $driving_license_data;

        // Remove sensitive information
        unset($data['password']);

        //  Success response
        echo json_encode([
            "error" => false,
            "message" => "Data retrieved successfully",
            "data" => $data
        ]);
        return false;
    }


    /* 11.get_orders

        user_id:101
        active_status: received  {received,delivered,cancelled,processed,returned}     // optional

        limit:25            // { default - 25 } optional
        offset:0            // { default - 0 } optional
        sort: id / date_added // { default - id } optional
        order:DESC/ASC      // { default - DESC } optional
    */

    public function get_orders()
    {
        if (!$this->verify_token()) {
            return false;
        }

        $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
        $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
        $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'o.id';
        $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'DESC';

        $this->form_validation->set_rules('active_status', 'status', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $user_id = $this->user_details['id'];
            $where = ['delivery_boy_id' => $user_id];
            if (isset($_POST['active_status']) && !empty($_POST['active_status'])) {
                $where['active_status'] = $_POST['active_status'];
            }

            $multiple_status = (isset($_POST['active_status']) && !empty($_POST['active_status'])) ? explode(',', $this->input->post('active_status', true) ?? '') : false;
            $download_invoice = (isset($_POST['download_invoice']) && !empty($_POST['download_invoice'])) ? $this->input->post('download_invoice', true) : 1;
            $order_details = fetch_orders(false, false, $multiple_status, $user_id, $limit, $offset, $sort, $order, $download_invoice);
            if (!empty($order_details)) {
                $this->response['error'] = false;
                $this->response['message'] = 'Data retrieved successfully';
                $this->response['total'] = $order_details['total'];
                $this->response['data'] = $order_details['order_data'];
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'User Does Not Exists';
                $this->response['total'] = "0";
                $this->response['data'] = array();
            }
        }
        print_r(json_encode($this->response));
    }


    /* 3.get_fund_transfers

        user_id:101
        limit:25            // { default - 25 } optional
        offset:0            // { default - 0 } optional
        sort: id / date_added // { default - id } optional
        order:DESC/ASC      // { default - DESC } optional

    */

    public function get_fund_transfers()
    {
        if (!$this->verify_token()) {
            return false;
        }

        $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
        $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
        $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'id';
        $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'DESC';

        // Set form validation rules
        $this->form_validation->set_rules('limit', 'Limit', 'trim|numeric');
        $this->form_validation->set_rules('offset', 'Offset', 'trim|numeric');
        $this->form_validation->set_rules('sort', 'Sort', 'trim');
        $this->form_validation->set_rules('order', 'Order', 'trim');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $user_id = $this->user_details['id'];
            $where = ['delivery_boy_id' => $user_id];
            $this->db->select('count(`id`) as total');
            $total_fund_transfers = $this->db->where($where)->get('fund_transfers')->result_array();

            $this->db->select('*');
            $this->db->order_by($sort, $order);
            $this->db->limit($limit, $offset);
            $fund_transfer_details = $this->db->where($where)->get('fund_transfers')->result_array();
            if (!empty($fund_transfer_details)) {
                $this->response['error'] = false;
                $this->response['message'] = 'Data retrieved successfully';
                $this->response['total'] = $total_fund_transfers[0]['total'];
                $this->response['data'] = $fund_transfer_details;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'No fund transfer has been made yet';
                $this->response['total'] = "0";
                $this->response['data'] = array();
            }
        }
        print_r(json_encode($this->response));
    }

    public function update_user()
    {
        /*
            user_id:34
            username:hiten
            mobile:7852347890 {optional}
            email:amangoswami@gmail.com	{optional}
            //optional parameters
            old:12345
            new:345234
            driving_license : FILE {optional}
        */
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->response['error'] = true;
            $this->response['message'] = DEMO_VERSION_MSG;
            echo json_encode($this->response);
            return false;
            exit();
        }
        if (!$this->verify_token()) {
            return false;
        }
        $user_id = $this->user_details['id'];



        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->form_validation->set_rules('email', 'Email', 'xss_clean|trim|valid_email|edit_unique[users.id.' . $this->input->post('user_id', true) . ']');
        $this->form_validation->set_rules('mobile', 'Mobile', 'xss_clean|trim|numeric|edit_unique[users.id.' . $user_id . ']');
        $this->form_validation->set_rules('username', 'Username', 'xss_clean|trim');
        $delivery_boy_data = fetch_details('users', ['id' => $user_id], 'driving_license');
        $driving_license = explode(',', $delivery_boy_data[0]['driving_license']);

        if (isset($_POST['user_id'])) {
            if (isset($_FILES) && !empty($_FILES) && !empty($_FILES['driving_license']['name'][0]) && count($_FILES['driving_license']['name']) < 2) {
                $this->form_validation->set_rules('driving_license', 'driving_license', 'trim|required|xss_clean', array('required' => 'Please add front and back image of Driving license'));
            }
            if (isset($_FILES) && !empty($_FILES) && !empty($_FILES['driving_license']['name'][0]) && count($_FILES['driving_license']['name']) > 2) {
                $this->form_validation->set_rules('driving_license', 'driving_license', 'trim|required|xss_clean', array('required' => 'You can only choose two images'));
            } elseif (isset($driving_license) && !empty($driving_license[0]) && count($driving_license) > 2) {
                $this->form_validation->set_rules('driving_license', 'driving_license', 'trim|required|xss_clean', array('required' => 'You can only choose two images'));
            }
        }

        if (!empty($_POST['old']) || !empty($_POST['new'])) {
            $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required|xss_clean');
            $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|xss_clean|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']');
        }


        $tables = $this->config->item('tables', 'ion_auth');
        if (!$this->form_validation->run()) {
            if (validation_errors()) {
                $response['error'] = true;
                $response['message'] = validation_errors();
                echo json_encode($response);
                return false;
                exit();
            }
        } else {

            //Driving license

            if (!file_exists(FCPATH . DELIVERY_BOY_DOCUMENTS_PATH)) {
                mkdir(FCPATH . DELIVERY_BOY_DOCUMENTS_PATH, 0777);
            }

            $temp_array = array();
            $files = $_FILES;
            $images_new_name_arr = array();
            $images_info_error = "";
            $allowed_media_types = implode('|', allowed_media_types());
            $config = [
                'upload_path' => FCPATH . DELIVERY_BOY_DOCUMENTS_PATH,
                'allowed_types' => $allowed_media_types,
                'max_size' => 8000,
            ];

            if (!empty($_FILES['driving_license']['name']) && isset($_FILES['driving_license']['name']) && !empty($files['driving_license']['name'][0])) {
                $other_image_cnt = count((array) $_FILES['driving_license']['name']);

                $other_img = $this->upload;
                $other_img->initialize($config);

                for ($i = 0; $i < $other_image_cnt; $i++) {

                    if (!empty($_FILES['driving_license']['name'][$i])) {

                        $_FILES['temp_image']['name'] = $files['driving_license']['name'][$i];
                        $_FILES['temp_image']['type'] = $files['driving_license']['type'][$i];
                        $_FILES['temp_image']['tmp_name'] = $files['driving_license']['tmp_name'][$i];
                        $_FILES['temp_image']['error'] = $files['driving_license']['error'][$i];
                        $_FILES['temp_image']['size'] = $files['driving_license']['size'][$i];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = 'driving_license :' . $images_info_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array = $other_img->data();
                            resize_review_images($temp_array, FCPATH . DELIVERY_BOY_DOCUMENTS_PATH);
                            $images_new_name_arr[$i] = DELIVERY_BOY_DOCUMENTS_PATH . $temp_array['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files['driving_license']['name'][$i];
                        $_FILES['temp_image']['type'] = $files['driving_license']['type'][$i];
                        $_FILES['temp_image']['tmp_name'] = $files['driving_license']['tmp_name'][$i];
                        $_FILES['temp_image']['error'] = $files['driving_license']['error'][$i];
                        $_FILES['temp_image']['size'] = $files['driving_license']['size'][$i];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = $other_img->display_errors();
                        }
                    }
                }
                //Deleting Uploaded attachments if any overall error occured
                if ($images_info_error != NULL || !$this->form_validation->run()) {
                    if (isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
                        foreach ($images_new_name_arr as $key => $val) {
                            unlink(FCPATH . DELIVERY_BOY_DOCUMENTS_PATH . $images_new_name_arr[$key]);
                        }
                    }
                }
            }


            if ($images_info_error != NULL) {
                $this->response['error'] = true;
                $this->response['message'] = $images_info_error;
                print_r(json_encode($this->response));
                return false;
            }
            if (!empty($_POST['old']) || !empty($_POST['new'])) {
                $identity = ($identity_column == 'mobile') ? 'mobile' : 'email';
                $res = fetch_details('users', ['id' => $_POST['user_id']], '*');
                if (!empty($res) && $this->ion_auth->in_group('delivery_boy', $res[0]['id'])) {
                    if (!$this->ion_auth->change_password($res[0][$identity], $this->input->post('old'), $this->input->post('new'))) {
                        // if the login was un-successful
                        $response['error'] = true;
                        $response['message'] = strip_tags($this->ion_auth->errors());
                        echo json_encode($response);
                        return;
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = 'User does not exists';
                    echo json_encode($response);
                    return;
                }
            }

            $set = [];

            if (isset($_POST['username']) && !empty($_POST['username'])) {
                $set['username'] = $this->input->post('username', true);
            }
            if (isset($_POST['email']) && !empty($_POST['email'])) {
                $set['email'] = $this->input->post('email', true);
            }
            if (isset($_POST['mobile']) && !empty($_POST['mobile'])) {
                $set['mobile'] = $this->input->post('mobile', true);
            }
            if (isset($_FILES['driving_license']) && !empty($_FILES['driving_license'])) {
                $set['driving_license'] = isset($images_new_name_arr) && !empty($images_new_name_arr) ? implode(',', (array) $images_new_name_arr) : implode(',', (array) $delivery_boy_data[0]['driving_license']);
                ;
            }

            $set = escape_array($set);

            $this->db->set($set)->where('id', $this->input->post('user_id', true))->update($tables['login_users']);
            $data = fetch_details('users', ['id' => $this->input->post('user_id', true)], 'driving_license')[0];
            $driving_license_data = [];
            if (isset($data['driving_license']) && !empty($data['driving_license'])) {
                $driving_license = explode(',', $data['driving_license']);
                foreach ($driving_license as $row) {
                    array_push($driving_license_data, base_url($row));
                }
            }

            $response['error'] = false;
            $response['message'] = 'Profile Update Successfully';
            $response['driving_license'] = isset($data['driving_license']) && !empty($data['driving_license']) ? $driving_license_data : [];
            echo json_encode($response);
            return;
        }
    }
    // // 6. update_fcm


    public function update_fcm()
    {
        /* Parameters to be passed
        user_id:12
        fcm_id: FCM_ID
    */

        if (!$this->verify_token()) {
            echo json_encode(['error' => true, 'message' => 'Unauthorized access']);
            return false;
        }

        $user_id = $this->user_details['id'];
        $fcm_id = $this->input->post('fcm_id', true);

        // Set validation rules
        $this->form_validation->set_rules('fcm_id', 'FCM ID', 'trim|required');

        if (!$this->form_validation->run()) {
            echo json_encode(['error' => true, 'message' => strip_tags(validation_errors())]);
            return false;
        }

        $user_res = update_details(['fcm_id' => $fcm_id], ['id' => $user_id], 'users');
        echo json_encode([
            'error' => !$user_res,
            'message' => $user_res ? 'Updated Successfully' : 'Updation Failed!',
            'data' => $user_res ? ['user_id' => $user_id, 'fcm_id' => $fcm_id] : []
        ]);

        return false;
    }

    // 7. reset_password
    public function reset_password()
    {
        /* Parameters to be passed
            user_id:12
            new: pass@123
        */

        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->response['error'] = true;
            $this->response['message'] = DEMO_VERSION_MSG;
            echo json_encode($this->response);
            return false;
            exit();
        }

        $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|numeric|required|xss_clean|min_length[10]');
        $this->form_validation->set_rules('new', 'New Password', 'trim|required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        }

        $identity_column = $this->config->item('identity', 'ion_auth');
        $res = fetch_details('users', ['mobile' => $_POST['mobile_no']]);
        if (!empty($res) && $this->ion_auth->in_group('delivery_boy', $res[0]['id'])) {
            $identity = ($identity_column == 'email') ? $res[0]['email'] : $res[0]['mobile'];
            if (!$this->ion_auth->reset_password($identity, $_POST['new'])) {
                $response['error'] = true;
                $response['message'] = strip_tags($this->ion_auth->messages());
                ;
                $response['data'] = array();
                echo json_encode($response);
                return false;
            } else {
                $response['error'] = false;
                $response['message'] = 'Reset Password Successfully';
                $response['data'] = array();
                echo json_encode($response);
                return false;
            }
        } else {
            $response['error'] = false;
            $response['message'] = 'User does not exists !';
            $response['data'] = array();
            echo json_encode($response);
            return false;
        }
    }

    //8. get_notification()
    public function get_notifications()
    {
        /*
            limit:25                // { default - 25 } optional
            offset:0                // { default - 0 } optional
            sort: type   			// { default - type } optional
            order:DESC/ASC          // { default - DESC } optional
        */

        $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'DESC';
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'id';
            $res = $this->notification_model->get_notifications($offset, $limit, $sort, $order);
            $this->response['error'] = false;
            $this->response['message'] = 'Notification Retrieved Successfully';
            $this->response['total'] = $res['total'];
            $this->response['data'] = $res['data'];
        }

        print_r(json_encode($this->response));
    }

    //9. verify-user
    public function verify_user()
    {
        /* Parameters to be passed
            mobile: 9874565478
            email: test@gmail.com // { optional }
        */

        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|valid_email');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return;
        } else {
            if (isset($_POST['is_forgot_password']) && ($_POST['is_forgot_password'] == 1) && !is_exist(['mobile' => $_POST['mobile']], 'users')) {
                $this->response['error'] = true;
                $this->response['message'] = 'Mobile is not register yet !';
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return;
            } else {
                $mobile = $this->input->post('mobile');
                $auth_settings = get_settings('authentication_settings', true);
                if ($auth_settings['authentication_method'] == "sms") {
                    $otps = fetch_details('otps', ['mobile' => $mobile]);
                    $query = $this->db->select(' * ')->where('id', $otps[0]['id'])->get('otps')->result_array();
                    $otp = random_int(100000, 999999);
                    $data = set_user_otp($mobile, $otp);
                    $this->response['error'] = false;
                    $this->response['message'] = 'Ready to sent OTP request from sms!';
                    print_r(json_encode($this->response));
                    return;
                }
            }
            if (isset($_POST['mobile']) && is_exist(['mobile' => $_POST['mobile']], 'users')) {
                $user_id = fetch_details('users', ['mobile' => $_POST['mobile']], 'id');

                //Check if this mobile no. is registered as a delivery boy or not.
                if (!$this->ion_auth->in_group('delivery_boy', $user_id[0]['id'])) {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Mobile number / email could not be found!';
                    print_r(json_encode($this->response));
                    return;
                } else {
                    $this->response['error'] = false;
                    $this->response['message'] = 'Mobile number is registered. ';
                    print_r(json_encode($this->response));
                    return;
                }
            }
            if (isset($_POST['email']) && is_exist(['email' => $_POST['email']], 'users')) {
                $this->response['error'] = false;
                $this->response['message'] = 'Email is registered.';
                print_r(json_encode($this->response));
                return;
            }

            $this->response['error'] = true;
            $this->response['message'] = 'Mobile number / email could not be found!';
            print_r(json_encode($this->response));
            return;
        }
    }

    //verify_otp
    public function verify_otp()
    {
        /* 
        otp: 123456
        phone number: 9876543210
        */
        $mobile = $this->input->post('mobile');
        $auth_settings = get_settings('authentication_settings', true);
        if ($auth_settings['authentication_method'] == "sms") {
            $otps = fetch_details('otps', ['mobile' => $mobile]);
            $time = $otps[0]['created_at'];
            $time_expire = checkOTPExpiration($time);
            if ($time_expire['error'] == 1) {
                $response['error'] = true;
                $response['message'] = $time_expire['message'];
                echo json_encode($response);
                return false;
            }
            if (($otps[0]['otp'] != $_POST['otp'])) {
                $response['error'] = true;
                $response['message'] = "OTP not valid , check again ";
                echo json_encode($response);
                return false;
            } else {
                update_details(['varified' => 1], ['mobile' => $mobile], 'otps');
            }
        }
        $this->response['error'] = false;
        $this->response['message'] = 'Otp Verified Successfully';
        print_r(json_encode($this->response));
    }

    //resend_otp
    public function resend_otp()
    {
        /*
        mobile:9876543210
        */

        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean|max_length[16]|numeric');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $mobile = $this->input->post('mobile');
            $auth_settings = get_settings('authentication_settings', true);
            if ($auth_settings['authentication_method'] == "sms") {
                $otps = fetch_details('otps', ['mobile' => $mobile]);
                $query = $this->db->select(' * ')->where('id', $otps[0]['id'])->get('otps')->result_array();
                $otp = random_int(100000, 999999);
                $data = set_user_otp($mobile, $otp);
                $this->response['error'] = false;
                $this->response['message'] = 'Ready to sent OTP request from sms!';
                $this->response['data'] = $otps;
                print_r(json_encode($this->response));
                return;
            }
        }
    }

    //10. get_settings
    public function get_settings()
    {
        /* 
            type : delivery_boy_privacy_policy / delivery_boy_terms_conditions / currency
        */

        $settings = get_settings('system_settings', true);
        $this->form_validation->set_rules('type', 'Setting Type', 'trim|required|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $allowed_settings = array('delivery_boy_terms_conditions', 'delivery_boy_privacy_policy', 'currency', 'authentication_settings', 'sms_gateway_settings');
            $type = $_POST['type'];
            $settings_res = get_settings($type);
            if (!in_array($type, $allowed_settings)) {
                $this->response['error'] = false;
                $this->response['message'] = 'Currency';
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
                exit();
            }
            $settings = [
                'system_settings' => 1,
            ];
            foreach ($settings as $type => $isjson) {
                if ($type == 'payment_method') {
                    continue;
                }
                $general_settings[$type] = [];
                $settings_result = get_settings($type, $isjson);
                array_push($settings_result, $settings_res);
            }

            if (!empty($settings_res)) {
                $this->response['error'] = false;
                $this->response['message'] = 'Settings retrieved successfully';
                $this->response['data'] = $settings_res;
                $this->response['currency'] = get_settings('currency');
                $this->response['authentication_settings'] = get_settings('authentication_settings', true);
                $this->response['system_settings'] = get_settings('system_settings', true);
                $this->response['supported_locals'] = $settings['supported_locals'];
                $this->response['system_settings'] = $settings_result;
            } else {
                $this->response['error'] = false;
                $this->response['message'] = 'Settings Not Found';
                $this->response['data'] = array();
            }
            print_r(json_encode($this->response));
        }
    }

    //11.send_withdrawal_request
    public function send_withdrawal_request()
    {
        /* 
            user_id:15
            payment_address: 12343535
            amount: 560           
        */

        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('payment_address', 'Payment Address', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean|numeric|greater_than[0]');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $user_id = $this->user_details['id'];
            $payment_address = $this->input->post('payment_address', true);
            $amount = $this->input->post('amount', true);
            $userData = fetch_details('users', ['id' => $this->input->post('user_id', true)], 'balance');

            if (!empty($userData)) {

                if ($_POST['amount'] <= $userData[0]['balance']) {

                    $data = [
                        'user_id' => $user_id,
                        'payment_address' => $payment_address,
                        'payment_type' => 'delivery_boy',
                        'amount_requested' => $amount,
                    ];

                    if (insert_details($data, 'payment_requests')) {
                        $this->delivery_boy_model->update_balance($amount, $user_id, 'deduct');
                        $userData = fetch_details('users', ['id' => $this->input->post('user_id', true)], 'balance');
                        $this->response['error'] = false;
                        $this->response['message'] = 'Withdrawal Request Send Successfully';
                        $this->response['data'] = $userData[0]['balance'];
                    } else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Cannot send Withdrawal Request.Please Try again later.';
                        $this->response['data'] = array();
                    }
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'You don\'t have enough balance to send the withdraw request.';
                    $this->response['data'] = array();
                }

                print_r(json_encode($this->response));
            }
        }
    }

    //13.get_withdrawal_request
    public function get_withdrawal_request()
    {
        /* 
            user_id:15
            limit:10       { default - null } optional
            offset:10      { default - null } optional
            sort: id       { default - id } optional
            order:DESC     { default - DESC } optional
        */

        if (!$this->verify_token()) {
            return false;
        }


        $this->form_validation->set_rules('limit', 'Limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'Offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('sort', 'Sort', 'trim|xss_clean');
        $this->form_validation->set_rules('order', 'Order', 'trim');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $user_id = $this->user_details['id'];
            $limit = ($this->input->post('limit', true)) ? $this->input->post('limit', true) : null;
            $offset = ($this->input->post('offset', true)) ? $this->input->post('offset', true) : null;
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'id';
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'DESC';
            $userData = fetch_details('payment_requests', ['user_id' => $user_id], '*', $limit, $offset, $sort, $order);
            $totalData = fetch_details('payment_requests', ['user_id' => $user_id], 'COUNT(*) as total');
            $totalCount = isset($totalData[0]['total']) ? $totalData[0]['total'] : 0;
            $rows = array();
            foreach ($userData as $row) {
                $row = output_escaping($row);
                $tempRow['id'] = (isset($row['id']) && !empty($row['id'])) ? $row['id'] : '';
                $tempRow['user_id'] = (isset($row['user_id']) && !empty($row['user_id'])) ? $row['user_id'] : '';
                $tempRow['payment_type'] = (isset($row['payment_type']) && !empty($row['payment_type'])) ? $row['payment_type'] : '';
                $tempRow['payment_address'] = (isset($row['payment_address']) && !empty($row['payment_address'])) ? $row['payment_address'] : '';
                $tempRow['amount_requested'] = (isset($row['amount_requested']) && !empty($row['amount_requested'])) ? $row['amount_requested'] : '';
                $tempRow['remarks'] = (isset($row['remarks']) && !empty($row['remarks'])) ? $row['remarks'] : '';
                $tempRow['status'] = (isset($row['status']) && !empty($row['status'])) ? $row['status'] : '0';
                $tempRow['date_created'] = (isset($row['date_created']) && !empty($row['date_created'])) ? $row['date_created'] : '';
                $rows[] = $tempRow;
            }
            $this->response['error'] = false;
            $this->response['message'] = 'Withdrawal Request Retrieved Successfully';
            $this->response['data'] = $rows;
            $this->response['total'] = strval($totalCount);
            print_r(json_encode($this->response));
        }
    }

    /* to update the status of complete order */
    public function update_order_status()
    {

        /*
            order_id:1
            status : received / processed / shipped / delivered / cancelled / returned
            delivery_boy_id: 15
         */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('order_id', 'Order Id', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean|in_list[received,processed,shipped,delivered,cancelled,returned]');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
        $user_id = $this->user_details['id'];// this is delivery-boy 
        $order = fetch_details('orders', ['id' => $this->input->post('order_id', true)], '*');

        if (empty($order)) {
            $this->response['error'] = true;
            $this->response['message'] = 'No Order Found';
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }

        // check for bank receipt if available
        $order_method = fetch_details('orders', ['id' => $this->input->post('order_id', true)], 'payment_method');
        if ($order_method[0]['payment_method'] == 'bank_transfer') {
            $bank_receipt = fetch_details('order_bank_transfer', ['order_id' => $this->input->post('order_id', true)]);
            $transaction_status = fetch_details('transactions', ['order_id' => $this->input->post('order_id', true)], 'status');
            if (empty($bank_receipt) || strtolower($transaction_status[0]['status']) != 'success') {
                $this->response['error'] = true;
                $this->response['message'] = "Order Status can not update, Bank verification is remain from transactions.";
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
        }
        $delivery_boy = fetch_details('users', ['id' => $user_id], '*');
        if (empty($delivery_boy)) {
            $this->response['error'] = true;
            $this->response['message'] = "Invalid Delivery boy id";
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
        $res = validate_order_status($this->input->post('order_id', true), $this->input->post('status', true), 'orders');
        if ($res['error']) {
            $this->response['error'] = true;
            $this->response['message'] = $res['message'];
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }

        $priority_status = [
            'received' => 0,
            'processed' => 1,
            'shipped' => 2,
            'delivered' => 3,
            'cancelled' => 4,
            'returned' => 5,
        ];

        $update_status = 1;
        $error = TRUE;
        $message = '';

        $where_id = "id = " . $this->input->post('order_id', true) . " and (active_status != 'cancelled' and active_status != 'returned' ) ";
        $where_order_id = "order_id = " . $this->input->post('order_id', true) . " and (active_status != 'cancelled' and active_status != 'returned' ) ";

        $order_items_details = fetch_details('order_items', $where_order_id, 'active_status');
        $counter = count($order_items_details);
        $cancel_counter = 0;
        foreach ($order_items_details as $row) {
            if ($row['active_status'] == 'cancelled') {
                ++$cancel_counter;
            }
        }
        if ($cancel_counter == $counter) {
            $update_status = 0;
        }

        if (isset($_POST['order_id']) && isset($_POST['status'])) {
            if ($update_status == 1) {

                $order = fetch_details('orders', $where_id, 'user_id,delivery_boy_id,active_status');


                $current_orders_status = $order[0]['active_status'];

                /* check if the logged in delivery boy and order's delivery boy are same or not */
                if ($order[0]['delivery_boy_id'] != $user_id) {
                    $response['error'] = true;
                    $response['message'] = "You cannot modify someone else's orders.";
                    print_r(json_encode($response));
                    return false;
                }

                if ($priority_status[$this->input->post('status', true)] > $priority_status[$current_orders_status]) {
                    $set = [
                        'status' => $this->input->post('status', true)
                    ];

                    // Update Active Status of Order Table										
                    if ($this->Order_model->update_order($set, $where_id, true)) {
                        if ($this->Order_model->update_order(['active_status' => $this->input->post('status', true)], $where_id)) {
                            if ($this->Order_model->update_order($set, $where_order_id, true, 'order_items')) {
                                if ($this->Order_model->update_order(['active_status' => $this->input->post('status', true)], $where_order_id, false, 'order_items')) {
                                    $error = false;
                                }
                            }
                        }
                    }
                    if ($error == false) {
                        $settings = get_settings('system_settings', true);
                        $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : '';
                        $user_res = fetch_details('users', ['id' => $user_id], 'username,fcm_id');
                        $fcm_ids = array();
                        if (!empty($user_res[0]['fcm_id'])) {
                            /* Send custom notification message */
                            if ($this->input->post('status', true) == 'received') {
                                $type = ['type' => "customer_order_received"];
                            } elseif ($this->input->post('status', true) == 'processed') {
                                $type = ['type' => "customer_order_processed"];
                            } elseif ($this->input->post('status', true) == 'shipped') {
                                $type = ['type' => "customer_order_shipped"];
                            } elseif ($this->input->post('status', true) == 'delivered') {
                                $type = ['type' => "customer_order_delivered"];
                            } elseif ($this->input->post('status', true) == 'cancelled') {
                                $type = ['type' => "customer_order_cancelled"];
                            } elseif ($this->input->post('status', true) == 'returned') {
                                $type = ['type' => "customer_order_returned"];
                            }

                            $custom_notification = fetch_details('custom_notifications', $type, '');

                            $hashtag_customer_name = '< customer_name >';
                            $hashtag_order_id = '< order_id >';
                            $hashtag_application_name = '< application_name >';

                            $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                            $hashtag = html_entity_decode($string);

                            $data = str_replace(array($hashtag_customer_name, $hashtag_order_id, $hashtag_application_name), array($user_res[0]['username'], $this->input->post('order_id', true), $app_name), $hashtag);
                            $message = output_escaping(trim($data, '"'));

                            $customer_msg = (!empty($custom_notification)) ? $message : 'Hello Dear ' . $user_res[0]['username'] . ' order status updated to ' . $this->input->post('status', true) . ' for your order ID #' . $this->input->post('order_id', true) . ' please take note of it! Thank you for shopping with us. Regards ' . $app_name . '';

                            $fcmMsg = array(
                                'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Order status updated",
                                'body' => $customer_msg,
                                'type' => "order"
                            );

                            $fcm_ids[0][] = $user_res[0]['fcm_id'];
                            $noti = 'the notification ' . send_notification($fcmMsg, $fcm_ids, $fcmMsg);
                        }
                        /* Process refer and earn bonus */
                        process_refund($this->input->post('order_id', true), $this->input->post('status', true), 'orders');
                        if (trim($this->input->post('status', true) == 'cancelled')) {
                            $data = fetch_details('order_items', ['order_id' => $this->input->post('order_id', true)], 'product_variant_id,quantity');
                            $product_variant_ids = [];
                            $qtns = [];
                            foreach ($data as $d) {
                                array_push($product_variant_ids, $d['product_variant_id']);
                                array_push($qtns, $d['quantity']);
                            }

                            update_stock($product_variant_ids, $qtns, 'plus');
                        }
                        $response = process_referral_bonus($user_id, $this->input->post('order_id', true), $this->input->post('status', true));
                        $message = 'Status Updated Successfully';

                        // Update login id in order_item table
                        update_details(['updated_by' => $user_id], ['id' => $this->input->post('order_item_id', true)], 'order_items');
                    }
                }
            }
            if ($error == true) {
                $message = 'Status Updation Failed';
            }
        }
        $response['error'] = $error;
        $response['message'] = $noti;
        $response['total_amount'] = (!empty($data) ? $data : '');
        print_r(json_encode($response));
    }

    /* to update the status of an individual status */
    public function update_order_item_status()
    {
        /*
            order_item_id:1
            status : received / processed / shipped / delivered / cancelled / returned
            delivery_boy_id: 15
         */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('order_item_id', 'Order Item ID', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean|in_list[received,processed,shipped,delivered,cancelled,returned]');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
        $res = validate_order_status($this->input->post('order_item_id', true), $this->input->post('status', true));
        if ($res['error']) {
            $this->response['error'] = true;
            $this->response['message'] = $res['message'];
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
        $user_id = $this->user_details['id'];
        $order_item_res = $this->db->select(' * , (Select count(id) from order_items where order_id = oi.order_id ) as order_counter ,(Select count(active_status) from order_items where active_status ="cancelled" and order_id = oi.order_id ) as order_cancel_counter , (Select count(active_status) from order_items where active_status ="returned" and order_id = oi.order_id ) as order_return_counter,(Select count(active_status) from order_items where active_status ="delivered" and order_id = oi.order_id ) as order_delivered_counter , (Select count(active_status) from order_items where active_status ="processed" and order_id = oi.order_id ) as order_processed_counter , (Select count(active_status) from order_items where active_status ="shipped" and order_id = oi.order_id ) as order_shipped_counter , (Select status from orders where id = oi.order_id ) as order_status ')
            ->where(['id' => $_POST['order_item_id']])
            ->get('order_items oi')->result_array();

        $otp_system = $order_item_res[0]['deliveryboy_otp_setting_on'];
        if ($_POST['status'] == 'delivered') {
            if ($otp_system == 1) {

                if (!validate_otp($order_item_res[0]['order_id'], otp: $_POST['otp'])) {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Invalid OTP supplied!';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
            }
        }


        $order_method = fetch_details('orders', ['id' => $order_item_res[0]['order_id']], 'payment_method');
        if ($order_method[0]['payment_method'] == 'bank_transfer') {
            $bank_receipt = fetch_details('order_bank_transfer', ['order_id' => $order_item_res[0]['order_id']]);
            $transaction_status = fetch_details('transactions', ['order_id' => $order_item_res[0]['order_id']], 'status');
            if (empty($bank_receipt) || strtolower($transaction_status[0]['status']) != 'success') {
                $this->response['error'] = true;
                $this->response['message'] = "Order Status can not update, Bank verification is remain from transactions.";
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
        }

        if ($this->Order_model->update_order(['status' => $_POST['status']], ['id' => $order_item_res[0]['id']], true, 'order_items')) {
            $this->Order_model->update_order(['active_status' => $_POST['status']], ['id' => $order_item_res[0]['id']], false, 'order_items');
            process_refund($order_item_res[0]['id'], $_POST['status'], 'order_items');
            if (
                ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_cancel_counter']) + 1 &&
                    $_POST['status'] == 'cancelled') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_return_counter']) + 1 &&
                    $_POST['status'] == 'returned') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_delivered_counter']) + 1 &&
                    $_POST['status'] == 'delivered') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_processed_counter']) + 1 &&
                    $_POST['status'] == 'processed') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_shipped_counter']) + 1 &&
                    $_POST['status'] == 'shipped')
            ) {
                if ($this->Order_model->update_order(['status' => $_POST['status']], ['id' => $order_item_res[0]['order_id']], true)) {
                    $this->Order_model->update_order(['active_status' => $_POST['status']], ['id' => $order_item_res[0]['order_id']]);

                    /* process the refer and earn */
                    $user = fetch_details('orders', ['id' => $order_item_res[0]['order_id']], 'user_id');
                    $user_id = $user[0]['user_id'];
                    if (trim($_POST['status']) == 'cancelled') {
                        $data = fetch_details('order_items', ['id' => $_POST['order_item_id']], 'product_variant_id,quantity');
                        update_stock($data[0]['product_variant_id'], $data[0]['quantity'], 'plus');
                    }
                    $response = process_referral_bonus($user_id, $order_item_res[0]['order_id'], $this->input->post('status', true));
                    $settings = get_settings('system_settings', true);
                    $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : '';
                    $user_res = fetch_details('users', ['id' => $user_id], 'username,fcm_id,mobile,email');
                    $fcm_ids = array();
                    if (!empty($user_res[0]['fcm_id'])) {
                        //send custom notification message

                        if ($this->input->post('status', true) == 'received') {
                            $type = ['type' => "customer_order_received"];
                        } elseif ($this->input->post('status', true) == 'processed') {
                            $type = ['type' => "customer_order_processed"];
                        } elseif ($this->input->post('status', true) == 'shipped') {
                            $type = ['type' => "customer_order_shipped"];
                        } elseif ($this->input->post('status', true) == 'delivered') {
                            $type = ['type' => "customer_order_delivered"];
                        } elseif ($this->input->post('status', true) == 'cancelled') {
                            $type = ['type' => "customer_order_cancelled"];
                        } elseif ($this->input->post('status', true) == 'returned') {
                            $type = ['type' => "customer_order_returned"];
                        }

                        $custom_notification = fetch_details('custom_notifications', $type, '');

                        $hashtag_customer_name = '< customer_name >';
                        $hashtag_order_id = '< order_id >';
                        $hashtag_application_name = '< application_name >';

                        $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                        $hashtag = html_entity_decode($string);

                        $data = str_replace(array($hashtag_customer_name, $hashtag_order_id, $hashtag_application_name), array($user_res[0]['username'], $order_item_res[0]['order_id'], $app_name), $hashtag);
                        $message = output_escaping(trim($data, '"'));

                        $customer_msg = (!empty($custom_notification)) ? $message : 'Hello Dear ' . $user_res[0]['username'] . ' order status updated to ' . $this->input->post('status', true) . ' for your order ID #' . $order_item_res[0]['id'] . ' please take note of it! Thank you for shopping with us. Regards ' . $app_name . '';

                        $fcmMsg = array(
                            'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Order status updated",
                            'body' => $customer_msg,
                            'type' => "order"
                        );

                        $fcm_ids[0][] = $user_res[0]['fcm_id'];
                        send_notification($fcmMsg, $fcm_ids, $fcmMsg);
                    }
                    notify_event(
                        $type['type'],
                        ["customer" => [$user_res[0]['email']]],
                        ["customer" => [$user_res[0]['mobile']]],
                        ["orders.id" => $order_item_res[0]['order_id']]
                    );
                }
            }
            // Update login id in order_item table
            update_details(['updated_by' => $user_id], ['id' => $this->input->post('order_item_id', true)], 'order_items');

            $this->response['error'] = false;
            $this->response['message'] = 'Status Updated Successfully';
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function get_delivery_boy_cash_collection()
    {
        /* 
        delivery_boy_id:15  
        status:             // {delivery_boy_cash (delivery boy collected) | delivery_boy_cash_collection (admin collected)}
        limit:25            // { default - 25 } optional
        offset:0            // { default - 0 } optional
        sort:               // { id } optional
        order:DESC/ASC      // { default - DESC } optional
        search:value        // {optional} 
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean');
        $this->form_validation->set_rules('search', 'search', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $user_id = $this->user_details['id'];
            $filters['delivery_boy_id'] = $user_id;
            $filters['status'] = (isset($_POST['status']) && !empty(trim($_POST['status']))) ? $this->input->post('status', true) : '';
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 10;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'transactions.id';
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'DESC';
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : '';
            $tmpRow = $rows = array();
            $data = $this->Delivery_boy_model->get_delivery_boy_cash_collection($limit, $offset, $sort, $order, $search, (isset($filters)) ? $filters : null);
            if (isset($data['data']) && !empty($data['data'])) {
                foreach ($data['data'] as $row) {
                    $tmpRow['id'] = $row['id'];
                    $tmpRow['name'] = $row['name'];
                    $tmpRow['mobile'] = $row['mobile'];
                    $tmpRow['order_id'] = $row['order_id'];
                    $tmpRow['cash_received'] = $row['cash_received'];
                    $tmpRow['type'] = $row['type'];
                    $tmpRow['amount'] = $row['amount'];
                    $tmpRow['message'] = $row['message'];
                    $tmpRow['transaction_date'] = $row['transaction_date'];
                    $tmpRow['date'] = $row['date'];
                    if (isset($row['order_id']) && !empty($row['order_id']) && $row['order_id'] != "") {
                        $order_data = fetch_orders($row['order_id']);
                        $tmpRow['order_details'] = (isset($order_data['order_data'][0])) ? array($order_data['order_data'][0]) : [];
                    } else {
                        $tmpRow['order_details'] = [];
                    }
                    $rows[] = $tmpRow;
                }
                if ($data['error'] == false) {
                    $data['data'] = $rows;
                } else {
                    $data['data'] = array();
                }
            }
            print_r(json_encode($data));
        }
    }

    public function delete_delivery_boy()
    {
        /*
            user_id:15
            mobile:9874563214
            password:12345695
        */
        if (!$this->verify_token()) {
            return false;
        }


        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            echo json_encode($this->response);
            return false;
        } else {
            $user_id = $this->user_details['id'];
            $user_data = fetch_details('users', ['id' => $user_id, 'mobile' => $_POST['mobile']], 'id,username,password,active,mobile');
            if ($user_data) {
                $login = $this->ion_auth->login($this->input->post('mobile'), $this->input->post('password'), false);
                if ($login) {
                    $user_group = fetch_details('users_groups', ['user_id' => $user_id], 'group_id');
                    if ($user_group[0]['group_id'] == '3') {
                        delete_details(['id' => $user_id], 'users');
                        delete_details(['user_id' => $user_id], 'users_groups');
                        $response['error'] = false;
                        $response['message'] = 'Delivery Boy  Deleted Successfully';
                    } else {
                        $response['error'] = true;
                        $response['message'] = 'Details Does\'s Match';
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Details Does\'s Match';
                }
            } else {
                $response['error'] = true;
                $response['message'] = 'User Not Found';
            }
            echo json_encode($response);
            return;
        }
    }

    public function register()
    {

        /*
            name:hiten
            mobile:7852347890
            email:amangoswami@gmail.com
            password:12345678
            confirm_password:12345678
            address : test
            serviceable_zipcodes : 370001,380006
            driving_license : FILE 
        */

        if (!isset($_POST['user_id'])) {
            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean|min_length[5]');
            $this->form_validation->set_rules('email', 'Mail', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('confirm_password', 'Confirm password', 'trim|required|matches[password]|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');

            // If files are selected to upload 
            if (isset($_FILES) && !empty($_FILES) && count((array) $_FILES['driving_license']['name']) < 2) {
                $this->form_validation->set_rules('driving_license', 'driving_license', 'trim|required|xss_clean', array('required' => 'Please add front and back image of Driving license'));
            }
            if (isset($_FILES) && !empty($_FILES) && count((array) $_FILES['driving_license']['name']) > 2) {
                $this->form_validation->set_rules('driving_license', 'driving_license', 'trim|required|xss_clean', array('required' => 'You can only choose two images'));
            }
        }

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
        } else {
            // upload driving license
            if (!file_exists(FCPATH . DELIVERY_BOY_DOCUMENTS_PATH)) {
                mkdir(FCPATH . DELIVERY_BOY_DOCUMENTS_PATH, 0777);
            }

            $temp_array = array();
            $files = $_FILES;
            $images_new_name_arr = array();
            $images_info_error = "";
            $allowed_media_types = implode('|', allowed_media_types());
            $config = [
                'upload_path' => FCPATH . DELIVERY_BOY_DOCUMENTS_PATH,
                'allowed_types' => $allowed_media_types,
                'max_size' => 8000,
            ];

            if (!empty($_FILES['driving_license']['name']) && isset($_FILES['driving_license']['name']) && !empty($files['driving_license']['name'][0])) {
                $other_image_cnt = count((array) $_FILES['driving_license']['name']);

                $other_img = $this->upload;
                $other_img->initialize($config);

                for ($i = 0; $i < $other_image_cnt; $i++) {

                    if (!empty($_FILES['driving_license']['name'][$i])) {

                        $_FILES['temp_image']['name'] = $files['driving_license']['name'][$i];
                        $_FILES['temp_image']['type'] = $files['driving_license']['type'][$i];
                        $_FILES['temp_image']['tmp_name'] = $files['driving_license']['tmp_name'][$i];
                        $_FILES['temp_image']['error'] = $files['driving_license']['error'][$i];
                        $_FILES['temp_image']['size'] = $files['driving_license']['size'][$i];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = 'driving_license :' . $images_info_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array = $other_img->data();
                            resize_review_images($temp_array, FCPATH . DELIVERY_BOY_DOCUMENTS_PATH);
                            $images_new_name_arr[$i] = DELIVERY_BOY_DOCUMENTS_PATH . $temp_array['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files['driving_license']['name'][$i];
                        $_FILES['temp_image']['type'] = $files['driving_license']['type'][$i];
                        $_FILES['temp_image']['tmp_name'] = $files['driving_license']['tmp_name'][$i];
                        $_FILES['temp_image']['error'] = $files['driving_license']['error'][$i];
                        $_FILES['temp_image']['size'] = $files['driving_license']['size'][$i];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = $other_img->display_errors();
                        }
                    }
                }
                //Deleting Uploaded attachments if any overall error occured
                if ($images_info_error != NULL || !$this->form_validation->run()) {
                    if (isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
                        foreach ($images_new_name_arr as $key => $val) {
                            unlink(FCPATH . DELIVERY_BOY_DOCUMENTS_PATH . $images_new_name_arr[$key]);
                        }
                    }
                }
            }


            if ($images_info_error != NULL) {
                $this->response['error'] = true;
                $this->response['message'] = $images_info_error;
                print_r(json_encode($this->response));
                return false;
            }

            if (!$this->form_validation->is_unique($_POST['mobile'], 'users.mobile') || !$this->form_validation->is_unique($_POST['email'], 'users.email')) {
                $response["error"] = true;
                $response["message"] = "Email or mobile already exists !";
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response["data"] = array();
                echo json_encode($response);
                return false;
            }

            $identity_column = $this->config->item('identity', 'ion_auth');
            $email = strtolower($this->input->post('email'));
            $mobile = $this->input->post('mobile');
            $identity = ($identity_column == 'mobile') ? $mobile : $email;
            $password = $this->input->post('password');

            $additional_data = [
                'username' => $this->input->post('name'),
                'address' => $this->input->post('address'),
                'type' => 'phone',
                'driving_license' => implode(',', $images_new_name_arr),
            ];

            $this->ion_auth->register($identity, $password, $email, $additional_data, ['3']);
            $token = generate_token($this->input->post('mobile'));
            update_details(['apikey' => $token], ['mobile' => $this->input->post('mobile')], "users");
            update_details(['active' => 1], [$identity_column => $identity], 'users');

            $data = fetch_details('users', ['mobile' => $identity], 'driving_license')[0];
            unset($data[0]['password']);
            unset($data[0]['confirm_password']);

            $driving_license_data = [];
            if (isset($data['driving_license']) && !empty($data['driving_license'])) {
                $driving_license = explode(',', $data['driving_license']);
                foreach ($driving_license as $row) {
                    array_push($driving_license_data, base_url($row));
                }
            }
            $response['error'] = false;
            $response['message'] = 'Delivery Boy register Successfully . wait for approval of admin ';
            $response['token'] = $token;
            $response['driving_license'] = isset($data['driving_license']) && !empty($data['driving_license']) ? $driving_license_data : [];
            echo json_encode($response);
            return;
        }
    }
}
