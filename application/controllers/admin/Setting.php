<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model('Setting_model');
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'settings';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Settings | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Settings  | ' . $settings['app_name'];
            $this->data['timezone'] = get_timezone_array();
            $this->data['logo'] = get_settings('logo');
            $this->data['favicon'] = get_settings('favicon');
            $this->data['settings'] = get_settings('system_settings', true);
            $this->data['shiprocket_settings'] = get_settings('shipping_method', true);
            $this->data['currency'] = get_settings('currency');
            $this->data['custom_charges'] = $this->Setting_model->get_custom_charges();
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
        if (!has_permissions('read', 'settings')) {
            $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
            redirect('admin/home', 'refresh');
        }
    }

    public function system_page()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'system_page';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Settings | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Settings  | ' . $settings['app_name'];
            $this->data['web_settings'] = get_settings('web_settings', true);
            $this->data['logo'] = get_settings('web_logo');
            $this->data['favicon'] = get_settings('web_favicon');
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
        if (!has_permissions('read', 'settings')) {
            $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
            redirect('admin/home', 'refresh');
        }
    }


    public function update_system_settings()
    {

        // AUTH & PERMISSION

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('admin/login', 'refresh');
            return;
        }

        if (print_msg(!has_permissions('update', 'settings'), PERMISSION_ERROR_MSG, 'settings')) {
            return false;
        }


        //INPUT FLAGS

        $area_wise_delivery_charge = !empty($this->input->post('area_wise_delivery_charge'));

        //COMMON VALIDATIONS

        $this->form_validation->set_rules('app_name', 'App Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('support_number', 'Support number', 'trim|required|numeric|greater_than[0]|xss_clean');
        $this->form_validation->set_rules('support_email', 'Support Email', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('current_version', 'Current Version Of Android APP', 'trim|required|xss_clean');
        $this->form_validation->set_rules('current_version_ios', 'Current Version Of IOS APP', 'trim|required|xss_clean');
        $this->form_validation->set_rules('system_timezone_gmt', 'System GMT timezone', 'trim|required|xss_clean');
        $this->form_validation->set_rules('system_timezone', 'System timezone', 'trim|required|xss_clean');
        $this->form_validation->set_rules('currency', 'Currency', 'trim|required|xss_clean');
        $this->form_validation->set_rules('max_product_return_days', 'Maximum Product Return Day', 'trim|required|numeric|greater_than_equal_to[0]|xss_clean');
        $this->form_validation->set_rules('delivery_boy_bonus_percentage', 'Delivery Boy Bonus', 'trim|required|numeric|greater_than_equal_to[0]|xss_clean');
        $this->form_validation->set_rules('minimum_cart_amt', 'Minimum Cart Amount', 'trim|required|numeric|greater_than_equal_to[0]|xss_clean');
        $this->form_validation->set_rules('low_stock_limit', 'Low stock limit', 'trim|required|numeric|greater_than[0]|xss_clean');
        $this->form_validation->set_rules('max_items_cart', 'Max items allowed in cart', 'trim|required|numeric|greater_than[0]|xss_clean');
        $this->form_validation->set_rules('logo', 'Logo', 'trim|required|xss_clean');
        $this->form_validation->set_rules('favicon', 'Favicon', 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
        $this->form_validation->set_rules('admin_store_state', 'Store State', 'trim|required|xss_clean');
        $this->form_validation->set_rules('latitude', 'Latitude', 'trim|required|numeric|greater_than_equal_to[0]|xss_clean');
        $this->form_validation->set_rules('longitude', 'Longitude', 'trim|required|numeric|greater_than_equal_to[0]|xss_clean');
        $this->form_validation->set_rules('min_cod_order_amount', 'Minimum COD Order Amount', 'trim|required|numeric|greater_than_equal_to[0]|xss_clean');
        $this->form_validation->set_rules('max_cod_order_amount', 'Maximum COD Order Amount', 'trim|required|numeric|greater_than_equal_to[0]|xss_clean');
        if ($this->input->post('city_wise_deliverability')) {
            $this->form_validation->set_rules('global_free_delivery_amount_on_city', 'Global Free Delivery Amount', 'trim|required|numeric|greater_than_equal_to[0]|xss_clean');
            $this->form_validation->set_rules('global_delivery_charge_on_city', 'Global Delivery Charge', 'trim|required|numeric|greater_than_equal_to[0]|xss_clean');
        }
        if ($this->input->post('ai_settings_status')) {
            $this->form_validation->set_rules('ai_provider', 'AI Provider', 'trim|required|xss_clean');
            $ai_provider = $this->input->post('ai_provider', true);
            if (trim($ai_provider) == 'gemini') {
                $this->form_validation->set_rules('gemini_api_key', 'Gemini API Key', 'trim|required|xss_clean');
            } elseif (trim($ai_provider) == 'openrouter') {
                $this->form_validation->set_rules('openrouter_api_key', 'OpenRouter API Key', 'trim|required|xss_clean');
            }
        }


        // CONDITIONAL DELIVERY VALIDATION

        if (!$area_wise_delivery_charge) {
            // Area-wise OFF → global delivery required
            $this->form_validation->set_rules(
                'delivery_charge',
                'Delivery charge',
                'trim|required|numeric|greater_than_equal_to[0]|xss_clean'
            );

            $this->form_validation->set_rules(
                'min_amount',
                'Minimum amount',
                'trim|required|numeric|greater_than_equal_to[0]|xss_clean'
            );
        }


        // MAINTENANCE MODE VALIDATIONS

        if ($this->input->post('is_customer_app_under_maintenance')) {
            $this->form_validation->set_rules('message_for_customer_app', 'Message for Customer App', 'trim|required|xss_clean');
        }

        if ($this->input->post('is_delivery_boy_app_under_maintenance')) {
            $this->form_validation->set_rules('message_for_delivery_boy_app', 'Message for Delivery Boy App', 'trim|required|xss_clean');
        }

        if ($this->input->post('is_admin_app_under_maintenance')) {
            $this->form_validation->set_rules('message_for_admin_app', 'Message for Admin App', 'trim|required|xss_clean');
        }

        if ($this->input->post('is_web_under_maintenance')) {
            $this->form_validation->set_rules('message_for_web', 'Message for Web Maintenance Mode', 'trim|required|xss_clean');
        }

        if ($this->input->post('whatsapp_status')) {
            $this->form_validation->set_rules('whatsapp_number', 'WhatsApp Number', 'trim|required|numeric|greater_than[0]|xss_clean');
        }

        if ($this->input->post('is_offer_popup_on')) {
            $this->form_validation->set_rules('offer_popup_method', 'Offer Popup Method', 'trim|required|xss_clean');
        }

        if ($this->input->post('welcome_wallet_balance_on')) {
            $this->form_validation->set_rules('wallet_balance_amount', 'Wallet Balance Amount', 'trim|required|numeric|greater_than_equal_to[0]|xss_clean');
        }

        if ($this->input->post('is_refer_earn_on')) {
            $this->form_validation->set_rules('refer_earn_method', 'Refer Earn Method', 'trim|required|xss_clean');
            $this->form_validation->set_rules('min_refer_earn_order_amount', 'Minimum Refer Earn Order Amount', 'trim|required|numeric|greater_than_equal_to[0]|xss_clean');
            $this->form_validation->set_rules('refer_earn_bonus', 'Refer Earn Bonus', 'trim|required|numeric|greater_than_equal_to[0]|xss_clean');
            $this->form_validation->set_rules('max_refer_earn_amount', 'Maximum Refer Earn Amount', 'trim|required|numeric|greater_than_equal_to[0]|xss_clean');
            $this->form_validation->set_rules('refer_earn_bonus_times', 'Refer Earn Bonus Times', 'trim|required|numeric|greater_than_equal_to[0]|xss_clean');
        }


        $this->form_validation->set_rules('android_app_store_link', 'Android App Store Link', 'trim|required|valid_url|xss_clean');
        $this->form_validation->set_rules('ios_app_store_link', 'iOS App Store Link', 'trim|required|valid_url|xss_clean');
        $this->form_validation->set_rules('scheme', 'Scheme For APP', 'trim|required|xss_clean');
        $this->form_validation->set_rules('host', 'Host For APP', 'trim|required|valid_url|xss_clean');


        $custom_charges = $this->input->post('custom_charges');
        if (!empty($custom_charges) && is_array($custom_charges)) {
            foreach ($custom_charges as $index => $charge) {
                $this->form_validation->set_rules("custom_charges[$index][name]", "Charge Name " . ($index + 1), "trim|required|xss_clean");
                $this->form_validation->set_rules("custom_charges[$index][amount]", "Charge Amount " . ($index + 1), "trim|required|numeric|greater_than_equal_to[0]|xss_clean");
            }
        }
        


        if (!$this->form_validation->run()) {
            echo json_encode([
                'error' => true,
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                'message' => validation_errors()
            ]);
            return;
        }

        $pincode_wise = !empty($this->input->post('pincode_wise_deliverability'));
        $city_wise = !empty($this->input->post('city_wise_deliverability'));

        if ($pincode_wise && $city_wise) {
            echo json_encode([
                'error' => true,
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                'message' => 'You cannot enable both Pincode Wise and City Wise Deliverability at the same time.'
            ]);
            return;
        }


        // $_POST['area_wise_delivery_charge'] = $area_wise_delivery_charge ? 1 : 0;

        $timezone_gmt = preg_replace('/\s+/', '', $this->input->post('system_timezone_gmt', true));
        $_POST['system_timezone_gmt'] = ($timezone_gmt === "00:00") ? "+00:00" : $timezone_gmt;

        $this->Setting_model->update_system_setting($_POST);

        $final = [];
        if (!empty($custom_charges) && is_array($custom_charges)) {
            foreach ($custom_charges as $charge) {
                if (!empty($charge['name'])) {
                    $final[] = [
                        'name' => trim($charge['name']),
                        'amount' => (float) $charge['amount'],

                        // Apply options (0 / 1)
                        'apply_pos' => isset($charge['apply_pos']) ? 1 : 0,
                        'apply_doorstep' => isset($charge['apply_doorstep']) ? 1 : 0,
                        'apply_pickup' => isset($charge['apply_pickup']) ? 1 : 0,
                        'apply_digital' => isset($charge['apply_digital']) ? 1 : 0,
                        'is_refundable' => isset($charge['is_refundable']) ? 1 : 0,
                    ];
                }
            }
        }


        $this->Setting_model->update_custom_charges(json_encode($final));

        echo json_encode([
            'error' => false,
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash(),
            'message' => 'Updated Successfully'
        ]);
    }

    public function web()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'web-settings';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Settings | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Settings  | ' . $settings['app_name'];
            $this->data['web_settings'] = get_settings('web_settings', true);
            $this->data['logo'] = get_settings('web_logo');
            $this->data['favicon'] = get_settings('web_favicon');
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function update_web_settings()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('admin/login', 'refresh');
        }

        if (!has_permissions('update', 'web_settings')) {
            $this->response['error'] = true;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = PERMISSION_ERROR_MSG;
            echo json_encode($this->response);
            return;
        }

        $this->form_validation->set_rules('site_title', 'Site Title', 'trim|required|xss_clean');
        $this->form_validation->set_rules('support_number', 'Support number', 'trim|required|numeric|greater_than[0]|xss_clean');
        $this->form_validation->set_rules('support_email', 'Support Email', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('copyright_details', 'Copyright Details', 'trim|xss_clean');
        $this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
        $this->form_validation->set_rules('app_short_description', 'App Short Description', 'trim|xss_clean');
        $this->form_validation->set_rules('meta_keywords', 'Meta Keywords', 'trim|xss_clean');
        $this->form_validation->set_rules('meta_description', 'Meta Description', 'trim|xss_clean');
        $this->form_validation->set_rules('promo_head_description', 'Promo Head Description', 'trim|xss_clean');

        // Map iframe validation - allow iframe tags for embedded maps
        $map_iframe = $this->input->post('map_iframe', true);
        if (!empty($map_iframe)) {
            $this->form_validation->set_rules('map_iframe', 'Map Iframe', 'trim|xss_clean');
        }

        // File path validations for media
        $this->form_validation->set_rules('logo', 'Logo', 'trim|xss_clean');
        $this->form_validation->set_rules('favicon', 'Favicon', 'trim|xss_clean');

        // App download section validations
        $this->form_validation->set_rules('app_download_section_title', 'App Download Section Title', 'trim|xss_clean');
        $this->form_validation->set_rules('app_download_section_tagline', 'App Download Section Tagline', 'trim|xss_clean');
        $this->form_validation->set_rules('app_download_section_short_description', 'App Download Short Description', 'trim|xss_clean');

        if (!empty($this->input->post('app_download_section_playstore_url'))) {
            $this->form_validation->set_rules(
                'app_download_section_playstore_url',
                'Play Store URL',
                'trim|required|xss_clean|valid_url'
            );
        }

        if (!empty($this->input->post('app_download_section_appstore_url'))) {
            $this->form_validation->set_rules(
                'app_download_section_appstore_url',
                'App Store URL',
                'trim|required|xss_clean|valid_url'
            );
        }

        if (!empty($this->input->post('twitter_link'))) {
            $this->form_validation->set_rules(
                'twitter_link',
                'Twitter Link',
                'trim|xss_clean|valid_url'
            );
        }

        if (!empty($this->input->post('instagram_link'))) {
            $this->form_validation->set_rules(
                'instagram_link',
                'Instagram Link',
                'trim|xss_clean|valid_url'
            );
        }

        if (!empty($this->input->post('youtube_link'))) {
            $this->form_validation->set_rules(
                'youtube_link',
                'YouTube Link',
                'trim|xss_clean|valid_url'
            );
        }

        if (!empty($this->input->post('whatsapp_link'))) {
            $this->form_validation->set_rules(
                'whatsapp_link',
                'WhatsApp Link',
                'trim|xss_clean|valid_url'
            );
        }

        if (!empty($this->input->post('linkedin_link'))) {
            $this->form_validation->set_rules(
                'linkedin_link',
                'LinkedIn Link',
                'trim|xss_clean|valid_url'
            );
        }

        if (!empty($this->input->post('tiktok_link'))) {
            $this->form_validation->set_rules(
                'tiktok_link',
                'TikTok Link',
                'trim|xss_clean|valid_url'
            );
        }

        // Shipping mode conditional validation
        if ($this->input->post('shipping_mode') == 'on' || $this->input->post('shipping_mode') == '1') {
            $this->form_validation->set_rules('shipping_title', 'Shipping Title', 'trim|required|xss_clean');
            $this->form_validation->set_rules('shipping_description', 'Shipping Description', 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('shipping_title', 'Shipping Title', 'trim|xss_clean');
            $this->form_validation->set_rules('shipping_description', 'Shipping Description', 'trim|xss_clean');
        }

        // Return mode conditional validation
        if ($this->input->post('return_mode') == 'on' || $this->input->post('return_mode') == '1') {
            $this->form_validation->set_rules('return_title', 'Return Title', 'trim|required|xss_clean');
            $this->form_validation->set_rules('return_description', 'Return Description', 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('return_title', 'Return Title', 'trim|xss_clean');
            $this->form_validation->set_rules('return_description', 'Return Description', 'trim|xss_clean');
        }

        // Support mode conditional validation
        if ($this->input->post('support_mode') == 'on' || $this->input->post('support_mode') == '1') {
            $this->form_validation->set_rules('support_title', 'Support Title', 'trim|required|xss_clean');
            $this->form_validation->set_rules('support_description', 'Support Description', 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('support_title', 'Support Title', 'trim|xss_clean');
            $this->form_validation->set_rules('support_description', 'Support Description', 'trim|xss_clean');
        }

        // Safety & Security mode conditional validation
        if ($this->input->post('safety_security_mode') == 'on' || $this->input->post('safety_security_mode') == '1') {
            $this->form_validation->set_rules('safety_security_title', 'Safety & Security Title', 'trim|required|xss_clean');
            $this->form_validation->set_rules('safety_security_description', 'Safety & Security Description', 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('safety_security_title', 'Safety & Security Title', 'trim|xss_clean');
            $this->form_validation->set_rules('safety_security_description', 'Safety & Security Description', 'trim|xss_clean');
        }

        if (!empty($this->input->post('primary_color'))) {
            $color = $this->input->post('primary_color', true);
            if (!preg_match('~^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$~i', $color)) {
                $this->form_validation->set_rules('primary_color', 'Primary Color', 'trim|required|xss_clean');
            }
        }

        if (!empty($this->input->post('secondary_color'))) {
            $color = $this->input->post('secondary_color', true);
            if (!preg_match('~^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$~i', $color)) {
                $this->form_validation->set_rules('secondary_color', 'Secondary Color', 'trim|required|xss_clean');
            }
        }

        if (!empty($this->input->post('font_color'))) {
            $color = $this->input->post('font_color', true);
            if (!preg_match('~^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$~i', $color)) {
                $this->form_validation->set_rules('font_color', 'Font Color', 'trim|required|xss_clean');
            }
        }

        $this->form_validation->set_rules('modern_theme_color', 'Modern Theme Color', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = validation_errors();
            echo json_encode($this->response);
        } else {
            $this->Setting_model->update_web_setting($this->input->post(null, true));
            $this->response['error'] = false;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = 'System Setting Updated Successfully';
            echo json_encode($this->response);
        }
    }
    public function get_themes()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->Setting_model->get_theme_list();
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function set_default_theme()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (print_msg(!has_permissions('update', 'web_settings'), PERMISSION_ERROR_MSG, 'web_settings')) {
                return false;
            }
            $this->form_validation->set_rules('theme_id', 'Theme', 'trim|required|xss_clean|numeric');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
                return false;
            }
            $theme_id = $this->input->post('theme_id', true);
            $theme = $this->db->where('id', $theme_id)->get('themes')->row_array();
            if (empty($theme)) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = "No theme found.";
                $this->response['test'] = $theme;
                print_r(json_encode($this->response));
                return false;
            }

            if ($theme['status'] == 0) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = "You can not set Inactive theme as default.";
                print_r(json_encode($this->response));
                return false;
            }
            $this->db->trans_start();

            $this->db->set('is_default', 0);
            $this->db->update('themes');

            $this->db->set('is_default', 1);
            $this->db->where('id', $theme_id)->update('themes');

            $this->db->trans_complete();
            $error = true;
            if ($this->db->trans_status() === true) {
                $error = false;
            }
            $this->response['error'] = $error;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = "Default Theme Updated.";
            print_r(json_encode($this->response));
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
