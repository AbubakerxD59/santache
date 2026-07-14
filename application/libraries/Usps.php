<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * USPS REST API (v3) — OAuth + Domestic Prices for dynamic shipping rates.
 */
class Usps
{
    private $consumer_key = '';
    private $consumer_secret = '';
    private $origin_zip = '';
    private $price_type = 'RETAIL';
    private $mail_class = 'USPS_GROUND_ADVANTAGE';
    private $base_url = 'https://apis.usps.com';
    private $token_cache_file = '';
    private $payment_token_cache_file = '';
    private $crid = '';
    private $mid = '';
    private $manifest_mid = '';
    private $account_number = '';
    private $from_first_name = '';
    private $from_last_name = '';
    private $from_street = '';
    private $from_city = '';
    private $from_state = '';
    private $from_phone = '';
    private $from_email = '';

    /** Default package size in inches when product dims are missing */
    const DEFAULT_LENGTH = 6;
    const DEFAULT_WIDTH = 6;
    const DEFAULT_HEIGHT = 6;
    const MIN_WEIGHT_LB = 0.1;
    const MAX_WEIGHT_LB = 70;

    function __construct()
    {
        $settings = get_settings('shipping_method', true);
        if (!is_array($settings)) {
            $settings = [];
        }

        $this->consumer_key = isset($settings['usps_consumer_key']) ? trim($settings['usps_consumer_key']) : '';
        $this->consumer_secret = isset($settings['usps_consumer_secret']) ? trim($settings['usps_consumer_secret']) : '';
        $this->origin_zip = isset($settings['usps_origin_zip']) ? preg_replace('/\D/', '', $settings['usps_origin_zip']) : '';
        if (strlen($this->origin_zip) > 5) {
            $this->origin_zip = substr($this->origin_zip, 0, 5);
        }
        $this->price_type = 'RETAIL';
        $this->mail_class = 'USPS_GROUND_ADVANTAGE';

        $this->crid = isset($settings['usps_crid']) ? trim($settings['usps_crid']) : '';
        $this->mid = isset($settings['usps_mid']) ? trim($settings['usps_mid']) : '';
        $this->manifest_mid = isset($settings['usps_manifest_mid']) ? trim($settings['usps_manifest_mid']) : $this->mid;
        $this->account_number = isset($settings['usps_account_number']) ? trim($settings['usps_account_number']) : '';
        $this->from_first_name = isset($settings['usps_from_first_name']) ? trim($settings['usps_from_first_name']) : '';
        $this->from_last_name = isset($settings['usps_from_last_name']) ? trim($settings['usps_from_last_name']) : '';
        $this->from_street = isset($settings['usps_from_street']) ? trim($settings['usps_from_street']) : '';
        $this->from_city = isset($settings['usps_from_city']) ? trim($settings['usps_from_city']) : '';
        $this->from_state = isset($settings['usps_from_state']) ? strtoupper(trim($settings['usps_from_state'])) : '';
        $this->from_phone = isset($settings['usps_from_phone']) ? preg_replace('/\D/', '', $settings['usps_from_phone']) : '';
        $this->from_email = isset($settings['usps_from_email']) ? trim($settings['usps_from_email']) : '';

        if (isset($settings['usps_environment']) && strtolower($settings['usps_environment']) === 'tem') {
            $this->base_url = 'https://apis-tem.usps.com';
        }

        $cache_dir = APPPATH . 'cache';
        if (!is_dir($cache_dir)) {
            @mkdir($cache_dir, 0755, true);
        }
        $this->token_cache_file = $cache_dir . '/usps_oauth_token.json';
        $this->payment_token_cache_file = $cache_dir . '/usps_payment_token.json';
    }

    public function get_credentials()
    {
        return [
            'consumer_key' => $this->consumer_key,
            'consumer_secret' => $this->consumer_secret,
            'origin_zip' => $this->origin_zip,
            'price_type' => $this->price_type,
            'mail_class' => $this->mail_class,
            'base_url' => $this->base_url,
            'crid' => $this->crid,
            'mid' => $this->mid,
            'manifest_mid' => $this->manifest_mid,
            'account_number' => $this->account_number,
        ];
    }

    public function is_configured()
    {
        return $this->has_api_credentials() && !empty($this->origin_zip);
    }

    public function is_label_configured()
    {
        return $this->is_configured()
            && !empty($this->crid)
            && !empty($this->mid)
            && !empty($this->account_number)
            && !empty($this->from_street)
            && !empty($this->from_city)
            && !empty($this->from_state)
            && !empty($this->from_first_name);
    }

    /**
     * Ship-from address + OAuth credentials are enough for Carrier Pickup.
     */
    public function is_pickup_configured()
    {
        return $this->has_api_credentials()
            && !empty($this->origin_zip)
            && !empty($this->from_street)
            && !empty($this->from_city)
            && !empty($this->from_state)
            && !empty($this->from_first_name);
    }

    /**
     * True when Consumer Key and Secret are set (enough for OAuth + ZIP lookup).
     */
    public function has_api_credentials()
    {
        return !empty($this->consumer_key) && !empty($this->consumer_secret);
    }

    /**
     * Verify ZIP is a real US ZIP Code via USPS City/State API.
     *
     * @param string $zipcode e.g. 10001 or 10001-1234
     * @return array error, message, city, state, zipcode, access_denied (optional)
     */
    public function validate_zipcode($zipcode)
    {
        $zipcode = trim((string) $zipcode);
        if (!preg_match('/^\d{5}(-\d{4})?$/', $zipcode)) {
            return [
                'error' => true,
                'message' => 'ZIP Code must be a valid US format (e.g. 10001 or 10001-1234).',
                'city' => '',
                'state' => '',
                'zipcode' => $zipcode,
            ];
        }

        if (!$this->has_api_credentials()) {
            return [
                'error' => true,
                'message' => 'USPS API credentials are not configured. Cannot verify ZIP Code.',
                'city' => '',
                'state' => '',
                'zipcode' => $zipcode,
            ];
        }

        $zip5 = substr(preg_replace('/\D/', '', $zipcode), 0, 5);
        $url = $this->base_url . '/addresses/v3/city-state?ZIPCode=' . urlencode($zip5);
        $response = $this->curl($url, 'GET');

        $http = isset($response['apiStatus']) ? intval($response['apiStatus']) : 0;
        $city = isset($response['city']) ? trim((string) $response['city']) : '';
        $state = isset($response['state']) ? trim((string) $response['state']) : '';

        if ($http >= 200 && $http < 300 && $city !== '' && $state !== '') {
            return [
                'error' => false,
                'message' => 'Valid US ZIP Code.',
                'city' => $city,
                'state' => $state,
                'zipcode' => $zip5,
            ];
        }

        $api_message = '';
        if (isset($response['error']['message'])) {
            $api_message = $response['error']['message'];
        } elseif (isset($response['error']['errors'][0]['detail'])) {
            $api_message = $response['error']['errors'][0]['detail'];
        } elseif (isset($response['message'])) {
            $api_message = $response['message'];
        } elseif (is_string($response['error'] ?? null)) {
            $api_message = $response['error'];
        }

        // Addresses API license / access controls — allow callers to soft-skip
        if ($this->is_addresses_api_access_denied($http, $api_message, $response)) {
            return [
                'error' => true,
                'access_denied' => true,
                'message' => !empty($api_message)
                    ? $api_message
                    : 'USPS Addresses API access is not authorized.',
                'city' => '',
                'state' => '',
                'zipcode' => $zip5,
                'raw' => $response,
            ];
        }

        return [
            'error' => true,
            'access_denied' => false,
            'message' => !empty($api_message)
                ? $api_message
                : 'Please enter a valid US ZIP Code.',
            'city' => '',
            'state' => '',
            'zipcode' => $zip5,
            'raw' => $response,
        ];
    }

    /**
     * Detect USPS Addresses API Access Controls / missing license responses.
     */
    private function is_addresses_api_access_denied($http, $message, $response = [])
    {
        if (in_array(intval($http), [401, 403], true)) {
            return true;
        }

        $haystack = strtolower(trim((string) $message));
        if ($haystack === '' && is_array($response)) {
            $haystack = strtolower(json_encode($response));
        }

        $needles = [
            'addresses api access controls',
            'addresses api license',
            'not authorized for access to addresses',
            'add an addresses api license',
            'api licenses',
        ];

        foreach ($needles as $needle) {
            if ($haystack !== '' && strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Convert product weight (kg) to pounds for USPS.
     */
    public function kg_to_lb($kg)
    {
        $lb = floatval($kg) * 2.20462;
        if ($lb < self::MIN_WEIGHT_LB) {
            $lb = self::MIN_WEIGHT_LB;
        }
        if ($lb > self::MAX_WEIGHT_LB) {
            $lb = self::MAX_WEIGHT_LB;
        }
        return round($lb, 2);
    }

    /**
     * Convert cm to inches.
     */
    public function cm_to_inch($cm)
    {
        $inch = floatval($cm) / 2.54;
        return ($inch > 0) ? round($inch, 2) : 0;
    }

    public function generate_token($force = false)
    {
        if (empty($this->consumer_key) || empty($this->consumer_secret)) {
            return '';
        }

        if (!$force) {
            $cached = $this->read_cached_token();
            if (!empty($cached)) {
                return $cached;
            }
        } else {
            $this->clear_token_cache();
        }

        $url = $this->base_url . '/oauth2/v3/token';
        // Do NOT send a custom scope list. USPS returns the app's full default product
        // scopes when scope is omitted. Requesting unauthorized scopes (e.g. labels)
        // can narrow the token and drop prices access needed for dynamic rates.
        $payload = json_encode([
            'client_id' => $this->consumer_key,
            'client_secret' => $this->consumer_secret,
            'grant_type' => 'client_credentials',
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
        ]);
        $result = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $response = (!empty($result)) ? json_decode($result, true) : [];
        if (!is_array($response)) {
            $response = [];
        }
        $token = (isset($response['access_token'])) ? $response['access_token'] : '';

        if (!empty($token) && $http_code >= 200 && $http_code < 300) {
            $expires_in = isset($response['expires_in']) ? intval($response['expires_in']) : 28800;
            $this->write_cached_token($token, $expires_in, $response);
        }

        return $token;
    }

    /**
     * Force a new OAuth token from USPS and persist it to settings.
     *
     * @return array error, message, data (saved token record)
     */
    public function refresh_oauth_token()
    {
        if (!$this->has_api_credentials()) {
            return [
                'error' => true,
                'message' => 'USPS Consumer Key and Secret are required. Save them in shipping settings first.',
                'data' => [],
            ];
        }

        $token = $this->generate_token(true);
        $saved = $this->get_saved_oauth_token();

        if (empty($token) || empty($saved['access_token'])) {
            return [
                'error' => true,
                'message' => 'Failed to generate USPS OAuth token. Check Consumer Key/Secret and API environment.',
                'data' => $saved,
            ];
        }

        $scope = '';
        if (!empty($saved['response']['scope'])) {
            $scope = $saved['response']['scope'];
        }

        return [
            'error' => false,
            'message' => 'USPS OAuth token generated and saved successfully.'
                . ($scope !== '' ? ' Scope: ' . $scope : ''),
            'data' => $saved,
        ];
    }

    /**
     * Clear cached OAuth / payment tokens (use after USPS grants new API products).
     */
    public function clear_token_cache()
    {
        if (!empty($this->token_cache_file) && is_file($this->token_cache_file)) {
            @unlink($this->token_cache_file);
        }
        if (!empty($this->payment_token_cache_file) && is_file($this->payment_token_cache_file)) {
            @unlink($this->payment_token_cache_file);
        }
        $this->delete_setting('usps_oauth_token');
        $this->delete_setting('usps_payment_token');
    }

    /**
     * Latest saved OAuth token record from DB (token + full USPS response), or empty array.
     */
    public function get_saved_oauth_token()
    {
        $data = $this->read_setting('usps_oauth_token');
        return is_array($data) ? $data : [];
    }

    private function enrich_scope_error($message)
    {
        $message = trim((string) $message);
        if ($message === '') {
            $message = 'Insufficient OAuth scope';
        }
        if (stripos($message, 'scope') === false && stripos($message, 'unauthorized') === false) {
            return $message;
        }

        $this->clear_token_cache();

        return $message . ' Your USPS Developer app likely only has “Public Access I”. '
            . 'Request Payments v3 + Domestic Labels v3 for this app (include CRID, MID, EPS account), '
            . 'then refresh claims at https://cop.usps.com. '
            . 'Service request: https://emailus.usps.com/s/web-tools-inquiry';
    }

    /**
     * Get domestic shipping rate for a package.
     *
     * @param array $data origin_zip, destination_zip, weight_kg, length_cm, width_cm, height_cm, mail_class (optional)
     * @return array error, rate, mail_class, description, zone, raw
     */
    public function get_domestic_rate($data)
    {
        if (!$this->is_configured()) {
            return [
                'error' => true,
                'message' => 'USPS is not configured. Add Consumer Key, Secret, and Origin ZIP in shipping settings.',
                'rate' => 0,
            ];
        }

        $origin = !empty($data['origin_zip']) ? preg_replace('/\D/', '', $data['origin_zip']) : $this->origin_zip;
        $destination = isset($data['destination_zip']) ? preg_replace('/\D/', '', $data['destination_zip']) : '';
        if (strlen($origin) > 5) {
            $origin = substr($origin, 0, 5);
        }
        if (strlen($destination) > 5) {
            $destination = substr($destination, 0, 5);
        }

        if (empty($origin) || empty($destination) || strlen($destination) < 5) {
            return [
                'error' => true,
                'message' => 'Origin and destination ZIP codes are required.',
                'rate' => 0,
            ];
        }

        $weight_lb = isset($data['weight_lb'])
            ? floatval($data['weight_lb'])
            : $this->kg_to_lb(isset($data['weight_kg']) ? $data['weight_kg'] : 0);

        $length = isset($data['length_in']) ? floatval($data['length_in']) : $this->cm_to_inch(isset($data['length_cm']) ? $data['length_cm'] : 0);
        $width = isset($data['width_in']) ? floatval($data['width_in']) : $this->cm_to_inch(isset($data['width_cm']) ? $data['width_cm'] : (isset($data['breadth_cm']) ? $data['breadth_cm'] : 0));
        $height = isset($data['height_in']) ? floatval($data['height_in']) : $this->cm_to_inch(isset($data['height_cm']) ? $data['height_cm'] : 0);

        if ($length <= 0) {
            $length = self::DEFAULT_LENGTH;
        }
        if ($width <= 0) {
            $width = self::DEFAULT_WIDTH;
        }
        if ($height <= 0) {
            $height = self::DEFAULT_HEIGHT;
        }

        return $this->request_base_rate([
            'originZIPCode' => $origin,
            'destinationZIPCode' => $destination,
            'weight' => $weight_lb,
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'mailClass' => $this->mail_class,
            'processingCategory' => 'MACHINABLE',
            'destinationEntryFacilityType' => 'NONE',
            'rateIndicator' => 'SP',
            'priceType' => $this->price_type,
            'mailingDate' => date('Y-m-d'),
        ]);
    }

    /**
     * Request a single base rate from Domestic Prices API.
     */
    private function request_base_rate($payload)
    {
        $url = $this->base_url . '/prices/v3/base-rates/search';
        $response = $this->curl($url, 'POST', json_encode($payload));

        if (empty($response) || !is_array($response)) {
            return [
                'error' => true,
                'message' => 'Empty response from USPS Prices API.',
                'rate' => 0,
            ];
        }

        if (isset($response['error']) || isset($response['error']['message']) || (isset($response['apiStatus']) && $response['apiStatus'] >= 400)) {
            $message = '';
            if (isset($response['error']['message'])) {
                $message = $response['error']['message'];
            } elseif (isset($response['error']['errors'][0]['detail'])) {
                $message = $response['error']['errors'][0]['detail'];
            } elseif (isset($response['message'])) {
                $message = $response['message'];
            } else {
                $message = 'USPS rate request failed.';
            }
            return [
                'error' => true,
                'message' => $this->enrich_scope_error($message),
                'rate' => 0,
                'raw' => $response,
            ];
        }

        $rate = 0;
        $description = '';
        $zone = '';
        $mail_class = isset($payload['mailClass']) ? $payload['mailClass'] : '';

        if (isset($response['totalBasePrice'])) {
            $rate = floatval($response['totalBasePrice']);
        }
        if (!empty($response['rates'][0])) {
            $first = $response['rates'][0];
            if ($rate <= 0 && isset($first['price'])) {
                $rate = floatval($first['price']);
            }
            $description = isset($first['description']) ? $first['description'] : '';
            $zone = isset($first['zone']) ? $first['zone'] : '';
            if (!empty($first['mailClass'])) {
                $mail_class = $first['mailClass'];
            }
        }

        if ($rate <= 0) {
            return [
                'error' => true,
                'message' => 'No rate returned for mail class ' . $mail_class,
                'rate' => 0,
                'raw' => $response,
            ];
        }

        return [
            'error' => false,
            'message' => 'Rate retrieved successfully',
            'rate' => round($rate, 2),
            'mail_class' => $mail_class,
            'description' => $description,
            'zone' => $zone,
            'raw' => $response,
        ];
    }

    /**
     * Payment authorization token required for Labels API.
     */
    public function get_payment_authorization_token()
    {
        if (!$this->is_label_configured()) {
            return [
                'error' => true,
                'message' => 'USPS label settings incomplete. Add CRID, MID, EPS account number, and ship-from address in shipping settings.',
                'token' => '',
            ];
        }

        $cached = $this->read_cached_payment_token();
        if (!empty($cached)) {
            return [
                'error' => false,
                'token' => $cached,
            ];
        }

        $role = [
            'roleName' => 'PAYER',
            'CRID' => $this->crid,
            'MID' => $this->mid,
            'manifestMID' => $this->manifest_mid,
            'accountType' => 'EPS',
            'accountNumber' => $this->account_number,
        ];
        $owner = $role;
        $owner['roleName'] = 'LABEL_OWNER';

        $url = $this->base_url . '/payments/v3/payment-authorization';
        $response = $this->curl($url, 'POST', json_encode(['roles' => [$role, $owner]]));
        $token = '';
        if (!empty($response['paymentAuthorizationToken'])) {
            $token = $response['paymentAuthorizationToken'];
        }

        if (empty($token)) {
            $message = 'Failed to obtain USPS payment authorization token.';
            if (!empty($response['error']['message'])) {
                $message = $response['error']['message'];
            } elseif (!empty($response['message'])) {
                $message = $response['message'];
            } elseif (!empty($response['error_description'])) {
                $message = $response['error_description'];
            }
            return [
                'error' => true,
                'message' => $this->enrich_scope_error($message),
                'token' => '',
                'raw' => $response,
            ];
        }

        $this->write_cached_payment_token($token, 28800);
        return [
            'error' => false,
            'token' => $token,
            'raw' => $response,
        ];
    }

    /**
     * Create a domestic USPS Ground Advantage label.
     *
     * @param array $data to_address, weight_kg/weight_lb, length_cm/etc, package_value
     */
    public function create_label($data)
    {
        if (!$this->is_label_configured()) {
            return [
                'error' => true,
                'message' => 'USPS label settings incomplete. Add CRID, MID, EPS account, and ship-from address.',
            ];
        }

        $payment = $this->get_payment_authorization_token();
        if (!empty($payment['error'])) {
            return $payment;
        }

        $to = isset($data['to_address']) && is_array($data['to_address']) ? $data['to_address'] : [];
        $weight_lb = isset($data['weight_lb'])
            ? floatval($data['weight_lb'])
            : $this->kg_to_lb(isset($data['weight_kg']) ? $data['weight_kg'] : 0);

        $length = isset($data['length_in']) ? floatval($data['length_in']) : $this->cm_to_inch(isset($data['length_cm']) ? $data['length_cm'] : 0);
        $width = isset($data['width_in']) ? floatval($data['width_in']) : $this->cm_to_inch(isset($data['width_cm']) ? $data['width_cm'] : (isset($data['breadth_cm']) ? $data['breadth_cm'] : 0));
        $height = isset($data['height_in']) ? floatval($data['height_in']) : $this->cm_to_inch(isset($data['height_cm']) ? $data['height_cm'] : 0);
        if ($length <= 0) {
            $length = self::DEFAULT_LENGTH;
        }
        if ($width <= 0) {
            $width = self::DEFAULT_WIDTH;
        }
        if ($height <= 0) {
            $height = self::DEFAULT_HEIGHT;
        }

        $to_zip = isset($to['ZIPCode']) ? preg_replace('/\D/', '', $to['ZIPCode']) : '';
        if (strlen($to_zip) > 5) {
            $to_zip = substr($to_zip, 0, 5);
        }

        $payload = [
            'imageInfo' => [
                'imageType' => 'PDF',
                'labelType' => '4X6LABEL',
                'receiptOption' => 'NONE',
                'suppressPostage' => false,
                'suppressMailDate' => false,
                'returnLabel' => false,
            ],
            'toAddress' => [
                'firstName' => isset($to['firstName']) ? $to['firstName'] : 'Customer',
                'lastName' => isset($to['lastName']) ? $to['lastName'] : '',
                'streetAddress' => isset($to['streetAddress']) ? $to['streetAddress'] : '',
                'secondaryAddress' => isset($to['secondaryAddress']) ? $to['secondaryAddress'] : '',
                'city' => isset($to['city']) ? $to['city'] : '',
                'state' => isset($to['state']) ? $this->normalize_state($to['state']) : '',
                'ZIPCode' => $to_zip,
            ],
            'fromAddress' => [
                'firstName' => $this->from_first_name,
                'lastName' => $this->from_last_name !== '' ? $this->from_last_name : 'Store',
                'streetAddress' => $this->from_street,
                'city' => $this->from_city,
                'state' => $this->normalize_state($this->from_state),
                'ZIPCode' => $this->origin_zip,
            ],
            'packageDescription' => [
                'mailClass' => $this->mail_class,
                'rateIndicator' => 'SP',
                'weightUOM' => 'lb',
                'weight' => $weight_lb,
                'dimensionsUOM' => 'in',
                'length' => $length,
                'width' => $width,
                'height' => $height,
                'processingCategory' => 'MACHINABLE',
                'mailingDate' => date('Y-m-d'),
                'destinationEntryFacilityType' => 'NONE',
                'packageOptions' => [
                    'packageValue' => isset($data['package_value']) ? floatval($data['package_value']) : 0,
                ],
            ],
            'senderInfo' => [
                'CRID' => $this->crid,
                'MID' => $this->mid,
                'manifestMID' => $this->manifest_mid,
            ],
            'paymentInfo' => [
                'paymentMethod' => 'USPS_ACCOUNT',
                'accountType' => 'EPS',
                'accountNumber' => $this->account_number,
            ],
        ];

        if (!empty($this->from_phone)) {
            $payload['fromAddress']['phone'] = $this->from_phone;
        }
        if (!empty($to['phone'])) {
            $payload['toAddress']['phone'] = preg_replace('/\D/', '', $to['phone']);
        }

        $url = $this->base_url . '/labels/v3/label';
        $extra_headers = [
            'X-Payment-Authorization-Token: ' . $payment['token'],
            'Accept: application/json, multipart/form-data, application/pdf, */*',
        ];
        if (!empty($this->crid)) {
            $extra_headers[] = 'X-USPS-CRID: ' . $this->crid;
        }
        $raw = $this->curl_raw($url, 'POST', json_encode($payload), $extra_headers);

        $parsed = $this->parse_label_response($raw['body'], $raw['content_type'], $raw['http_code']);
        if (!empty($parsed['error'])) {
            if (!empty($parsed['message'])) {
                $parsed['message'] = $this->enrich_scope_error($parsed['message']);
            }
            return $parsed;
        }

        return [
            'error' => false,
            'message' => 'Label created successfully',
            'tracking_number' => $parsed['tracking_number'],
            'postage' => $parsed['postage'],
            'label_pdf' => $parsed['label_pdf'],
            'metadata' => $parsed['metadata'],
            'tracking_url' => !empty($parsed['tracking_number'])
                ? ('https://tools.usps.com/go/TrackConfirmAction_input?origTrackNum=' . urlencode($parsed['tracking_number']))
                : '',
        ];
    }

    /**
     * Track a package by tracking number.
     */
    public function track($tracking_number, $expand = 'DETAIL')
    {
        $tracking_number = trim((string) $tracking_number);
        if ($tracking_number === '') {
            return [
                'error' => true,
                'message' => 'Tracking number is required.',
            ];
        }

        if (!$this->has_api_credentials()) {
            return [
                'error' => true,
                'message' => 'USPS API credentials are not configured.',
            ];
        }

        $url = $this->base_url . '/tracking/v3/tracking/' . rawurlencode($tracking_number) . '?expand=' . rawurlencode($expand);
        $response = $this->curl($url, 'GET');
        $http = isset($response['apiStatus']) ? intval($response['apiStatus']) : 0;

        if ($http >= 200 && $http < 300) {
            $status = '';
            if (!empty($response['statusSummary'])) {
                $status = $response['statusSummary'];
            } elseif (!empty($response['status'])) {
                $status = $response['status'];
            } elseif (!empty($response['TrackResults']['TrackInfo']['TrackSummary'])) {
                $status = $response['TrackResults']['TrackInfo']['TrackSummary'];
            }

            return [
                'error' => false,
                'message' => 'Tracking retrieved successfully',
                'status' => $status,
                'status_category' => isset($response['statusCategory']) ? $response['statusCategory'] : '',
                'tracking_number' => isset($response['trackingNumber']) ? $response['trackingNumber'] : $tracking_number,
                'events' => isset($response['trackingEvents']) ? $response['trackingEvents'] : [],
                'raw' => $response,
            ];
        }

        $message = 'Unable to retrieve USPS tracking status.';
        if (!empty($response['error']['message'])) {
            $message = $response['error']['message'];
        } elseif (!empty($response['message'])) {
            $message = $response['message'];
        }

        return [
            'error' => true,
            'message' => $message,
            'raw' => $response,
        ];
    }

    /**
     * Check whether the ship-from (or provided) address is eligible for carrier pickup.
     *
     * @param array $address streetAddress, city, state, ZIPCode
     */
    public function check_pickup_eligibility($address = [])
    {
        if (!$this->has_api_credentials()) {
            return [
                'error' => true,
                'message' => 'USPS API credentials are not configured.',
                'eligible' => false,
            ];
        }

        $street = !empty($address['streetAddress']) ? $address['streetAddress'] : $this->from_street;
        $city = !empty($address['city']) ? $address['city'] : $this->from_city;
        $state = !empty($address['state']) ? $this->normalize_state($address['state']) : $this->normalize_state($this->from_state);
        $zip = !empty($address['ZIPCode']) ? preg_replace('/\D/', '', $address['ZIPCode']) : $this->origin_zip;
        if (strlen($zip) > 5) {
            $zip = substr($zip, 0, 5);
        }

        if ($street === '' || ($zip === '' && ($city === '' || $state === ''))) {
            return [
                'error' => true,
                'message' => 'Street address plus ZIP (or city/state) is required for pickup eligibility.',
                'eligible' => false,
            ];
        }

        $query = [
            'streetAddress' => $street,
            'city' => $city,
            'state' => $state,
            'ZIPCode' => $zip,
        ];
        $url = $this->base_url . '/pickup/v3/carrier-pickup/eligibility?' . http_build_query($query);
        $response = $this->curl($url, 'GET');
        $http = isset($response['apiStatus']) ? intval($response['apiStatus']) : 0;

        if ($http >= 200 && $http < 300) {
            return [
                'error' => false,
                'eligible' => true,
                'message' => 'Address is eligible for USPS carrier pickup.',
                'pickup_address' => isset($response['pickupAddress']) ? $response['pickupAddress'] : $response,
                'raw' => $response,
            ];
        }

        $message = 'Address is not eligible for USPS carrier pickup.';
        if (!empty($response['error']['message'])) {
            $message = $response['error']['message'];
        } elseif (!empty($response['message'])) {
            $message = $response['message'];
        }

        return [
            'error' => true,
            'eligible' => false,
            'message' => $this->enrich_scope_error($message),
            'raw' => $response,
        ];
    }

    /**
     * Schedule a USPS carrier package pickup at the configured ship-from address.
     *
     * @param array $data pickup_date, estimated_weight_lb/kg, package_count, package_type, package_location, special_instructions, email, phone
     */
    public function schedule_pickup($data = [])
    {
        if (!$this->is_pickup_configured()) {
            return [
                'error' => true,
                'message' => 'USPS pickup settings incomplete. Add Consumer Key/Secret, Origin ZIP, and ship-from address in shipping settings.',
            ];
        }

        $eligibility = $this->check_pickup_eligibility();
        if (!empty($eligibility['error'])) {
            return $eligibility;
        }

        $pickup_date = !empty($data['pickup_date']) ? $data['pickup_date'] : $this->next_pickup_date();
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $pickup_date)) {
            return [
                'error' => true,
                'message' => 'Pickup date must be YYYY-MM-DD.',
            ];
        }

        $weight_lb = isset($data['estimated_weight_lb'])
            ? floatval($data['estimated_weight_lb'])
            : $this->kg_to_lb(isset($data['estimated_weight_kg']) ? $data['estimated_weight_kg'] : 0);
        if ($weight_lb < self::MIN_WEIGHT_LB) {
            $weight_lb = self::MIN_WEIGHT_LB;
        }

        $package_count = isset($data['package_count']) ? max(1, intval($data['package_count'])) : 1;
        $package_type = !empty($data['package_type']) ? strtoupper(trim($data['package_type'])) : 'FIRST-CLASS_PACKAGE_SERVICE';
        $allowed_types = [
            'FIRST-CLASS_PACKAGE_SERVICE',
            'PRIORITY_MAIL_EXPRESS',
            'PRIORITY_MAIL',
            'RETURNS',
            'INTERNATIONAL',
            'OTHER',
        ];
        if (!in_array($package_type, $allowed_types, true)) {
            $package_type = 'OTHER';
        }

        $package_location = !empty($data['package_location']) ? strtoupper(trim($data['package_location'])) : 'FRONT_DOOR';
        $allowed_locations = [
            'FRONT_DOOR', 'BACK_DOOR', 'SIDE_DOOR', 'KNOCK_ON_DOOR', 'MAIL_ROOM',
            'OFFICE', 'RECEPTION', 'MAILBOX', 'OTHER',
        ];
        if (!in_array($package_location, $allowed_locations, true)) {
            $package_location = 'FRONT_DOOR';
        }

        $phone = !empty($data['phone']) ? preg_replace('/\D/', '', $data['phone']) : $this->from_phone;
        $email = !empty($data['email']) ? trim($data['email']) : $this->from_email;

        $contact = [];
        if ($email !== '') {
            $contact[] = ['email' => $email];
        }
        if ($phone !== '') {
            $contact[] = ['phone' => $phone];
        }
        if (empty($contact)) {
            return [
                'error' => true,
                'message' => 'A contact phone or email is required to schedule a USPS pickup. Add ship-from phone/email in shipping settings.',
            ];
        }

        $payload = [
            'pickupDate' => $pickup_date,
            'pickupAddress' => [
                'firstName' => $this->from_first_name,
                'lastName' => $this->from_last_name !== '' ? $this->from_last_name : 'Store',
                'address' => [
                    'streetAddress' => $this->from_street,
                    'city' => $this->from_city,
                    'state' => $this->normalize_state($this->from_state),
                    'ZIPCode' => $this->origin_zip,
                ],
                'contact' => $contact,
            ],
            'packages' => [
                [
                    'packageType' => $package_type,
                    'packageCount' => $package_count,
                ],
            ],
            'estimatedWeight' => round($weight_lb, 2),
            'pickupLocation' => [
                'packageLocation' => $package_location,
                'specialInstructions' => !empty($data['special_instructions'])
                    ? substr(trim($data['special_instructions']), 0, 255)
                    : '',
            ],
        ];

        $url = $this->base_url . '/pickup/v3/carrier-pickup';
        $response = $this->curl($url, 'POST', json_encode($payload));
        $http = isset($response['apiStatus']) ? intval($response['apiStatus']) : 0;

        if ($http >= 200 && $http < 300 && !empty($response['confirmationNumber'])) {
            return [
                'error' => false,
                'message' => 'USPS carrier pickup scheduled successfully.',
                'confirmation_number' => $response['confirmationNumber'],
                'pickup_date' => !empty($response['pickupDate']) ? $response['pickupDate'] : $pickup_date,
                'package_location' => $package_location,
                'raw' => $response,
            ];
        }

        $message = 'Failed to schedule USPS carrier pickup.';
        if (!empty($response['error']['message'])) {
            $message = $response['error']['message'];
        } elseif (!empty($response['message'])) {
            $message = $response['message'];
        }

        return [
            'error' => true,
            'message' => $this->enrich_scope_error($message),
            'raw' => $response,
        ];
    }

    /**
     * Cancel a scheduled carrier pickup by confirmation number.
     */
    public function cancel_pickup($confirmation_number)
    {
        $confirmation_number = trim((string) $confirmation_number);
        if ($confirmation_number === '') {
            return [
                'error' => true,
                'message' => 'Confirmation number is required.',
            ];
        }

        if (!$this->has_api_credentials()) {
            return [
                'error' => true,
                'message' => 'USPS API credentials are not configured.',
            ];
        }

        $url = $this->base_url . '/pickup/v3/carrier-pickup/' . rawurlencode($confirmation_number);
        $response = $this->curl($url, 'DELETE');
        $http = isset($response['apiStatus']) ? intval($response['apiStatus']) : 0;

        if ($http >= 200 && $http < 300) {
            return [
                'error' => false,
                'message' => 'USPS carrier pickup cancelled successfully.',
                'confirmation_number' => $confirmation_number,
                'raw' => $response,
            ];
        }

        // Some cancels return empty body with 204
        if ($http === 204) {
            return [
                'error' => false,
                'message' => 'USPS carrier pickup cancelled successfully.',
                'confirmation_number' => $confirmation_number,
                'raw' => $response,
            ];
        }

        $message = 'Failed to cancel USPS carrier pickup.';
        if (!empty($response['error']['message'])) {
            $message = $response['error']['message'];
        } elseif (!empty($response['message'])) {
            $message = $response['message'];
        }

        return [
            'error' => true,
            'message' => $this->enrich_scope_error($message),
            'raw' => $response,
        ];
    }

    /**
     * Next USPS delivery day (Mon–Sat). Does not account for federal holidays.
     */
    public function next_pickup_date($from_timestamp = null)
    {
        $ts = $from_timestamp ? intval($from_timestamp) : time();
        // Start from tomorrow
        $ts = strtotime('+1 day', $ts);
        for ($i = 0; $i < 10; $i++) {
            $dow = intval(date('N', $ts)); // 1=Mon ... 7=Sun
            if ($dow <= 6) {
                return date('Y-m-d', $ts);
            }
            $ts = strtotime('+1 day', $ts);
        }
        return date('Y-m-d', strtotime('+1 day'));
    }

    public function get_from_address()
    {
        return [
            'firstName' => $this->from_first_name,
            'lastName' => $this->from_last_name,
            'streetAddress' => $this->from_street,
            'city' => $this->from_city,
            'state' => $this->from_state,
            'ZIPCode' => $this->origin_zip,
            'phone' => $this->from_phone,
            'email' => $this->from_email,
        ];
    }

    private function normalize_state($state)
    {
        $state = trim((string) $state);
        if (strlen($state) === 2) {
            return strtoupper($state);
        }

        $map = [
            'alabama' => 'AL', 'alaska' => 'AK', 'arizona' => 'AZ', 'arkansas' => 'AR', 'california' => 'CA',
            'colorado' => 'CO', 'connecticut' => 'CT', 'delaware' => 'DE', 'florida' => 'FL', 'georgia' => 'GA',
            'hawaii' => 'HI', 'idaho' => 'ID', 'illinois' => 'IL', 'indiana' => 'IN', 'iowa' => 'IA',
            'kansas' => 'KS', 'kentucky' => 'KY', 'louisiana' => 'LA', 'maine' => 'ME', 'maryland' => 'MD',
            'massachusetts' => 'MA', 'michigan' => 'MI', 'minnesota' => 'MN', 'mississippi' => 'MS', 'missouri' => 'MO',
            'montana' => 'MT', 'nebraska' => 'NE', 'nevada' => 'NV', 'new hampshire' => 'NH', 'new jersey' => 'NJ',
            'new mexico' => 'NM', 'new york' => 'NY', 'north carolina' => 'NC', 'north dakota' => 'ND', 'ohio' => 'OH',
            'oklahoma' => 'OK', 'oregon' => 'OR', 'pennsylvania' => 'PA', 'rhode island' => 'RI', 'south carolina' => 'SC',
            'south dakota' => 'SD', 'tennessee' => 'TN', 'texas' => 'TX', 'utah' => 'UT', 'vermont' => 'VT',
            'virginia' => 'VA', 'washington' => 'WA', 'west virginia' => 'WV', 'wisconsin' => 'WI', 'wyoming' => 'WY',
            'district of columbia' => 'DC',
        ];
        $key = strtolower($state);
        return isset($map[$key]) ? $map[$key] : strtoupper(substr($state, 0, 2));
    }

    private function parse_label_response($body, $content_type, $http_code)
    {
        if ($http_code < 200 || $http_code >= 300) {
            $decoded = json_decode($body, true);
            $message = 'USPS label creation failed.';
            if (is_array($decoded)) {
                if (!empty($decoded['error']['message'])) {
                    $message = $decoded['error']['message'];
                } elseif (!empty($decoded['message'])) {
                    $message = $decoded['message'];
                }
            } elseif (is_string($body) && $body !== '') {
                $message = substr(strip_tags($body), 0, 300);
            }
            return [
                'error' => true,
                'message' => $message,
                'raw_body' => $body,
            ];
        }

        $metadata = null;
        $pdf = '';

        if (stripos((string) $content_type, 'multipart/') !== false && preg_match('/boundary="?([^";]+)"?/i', $content_type, $m)) {
            $boundary = $m[1];
            $parts = preg_split('/--' . preg_quote($boundary, '/') . '(?:--)?\r?\n/', $body);
            foreach ($parts as $part) {
                if (trim($part) === '' || trim($part) === '--') {
                    continue;
                }
                $split = preg_split("/\r?\n\r?\n/", $part, 2);
                if (count($split) < 2) {
                    continue;
                }
                $headers = $split[0];
                $content = rtrim($split[1], "\r\n");
                if (stripos($headers, 'application/json') !== false || stripos($headers, 'name="labelMetadata"') !== false) {
                    $metadata = json_decode($content, true);
                } elseif (stripos($headers, 'application/pdf') !== false || stripos($headers, 'labelImage') !== false) {
                    $pdf = $content;
                }
            }
        } else {
            $decoded = json_decode($body, true);
            if (is_array($decoded)) {
                $metadata = $decoded;
            } elseif (strpos($body, '%PDF') === 0) {
                $pdf = $body;
            }
        }

        $tracking = '';
        $postage = 0;
        if (is_array($metadata)) {
            if (!empty($metadata['trackingNumber'])) {
                $tracking = $metadata['trackingNumber'];
            }
            if (isset($metadata['postage'])) {
                $postage = floatval($metadata['postage']);
            }
        }

        if ($tracking === '' && $pdf === '') {
            return [
                'error' => true,
                'message' => 'USPS label response did not include tracking number or label image.',
                'raw_body' => substr((string) $body, 0, 500),
            ];
        }

        return [
            'error' => false,
            'tracking_number' => $tracking,
            'postage' => $postage,
            'label_pdf' => $pdf,
            'metadata' => $metadata,
        ];
    }

    public function curl($url, $method = 'GET', $data = [])
    {
        $raw = $this->curl_raw($url, $method, $data);
        $decoded = (!empty($raw['body'])) ? json_decode($raw['body'], true) : [];
        if (!is_array($decoded)) {
            $decoded = [];
        }
        $decoded['apiStatus'] = $raw['http_code'];
        return $decoded;
    }

    private function curl_raw($url, $method = 'GET', $data = [], $extra_headers = [])
    {
        $token = $this->generate_token();
        if (empty($token)) {
            return [
                'http_code' => 0,
                'content_type' => '',
                'body' => json_encode([
                    'error' => [
                        'message' => 'Failed to obtain USPS OAuth token. Check Consumer Key and Secret.',
                    ],
                ]),
            ];
        }

        $headers = array_merge([
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/json',
        ], $extra_headers);

        $ch = curl_init();
        $curl_options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 60,
        ];
        if (strtolower($method) == 'post') {
            $curl_options[CURLOPT_POST] = 1;
            $curl_options[CURLOPT_POSTFIELDS] = $data;
        } elseif (strtoupper($method) === 'DELETE') {
            $curl_options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
            if (!empty($data)) {
                $curl_options[CURLOPT_POSTFIELDS] = $data;
            }
        } else {
            $curl_options[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
        }
        curl_setopt_array($ch, $curl_options);

        $body = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        return [
            'http_code' => $http_code,
            'content_type' => $content_type,
            'body' => $body === false ? '' : $body,
        ];
    }

    private function read_cached_payment_token()
    {
        $data = $this->read_setting('usps_payment_token');
        if (empty($data['payment_token']) || empty($data['expires_at'])) {
            // Fallback: legacy file cache
            if (!is_file($this->payment_token_cache_file)) {
                return '';
            }
            $raw = @file_get_contents($this->payment_token_cache_file);
            $data = (!empty($raw)) ? json_decode($raw, true) : [];
            if (empty($data['payment_token']) || empty($data['expires_at'])) {
                return '';
            }
        }
        if (time() >= (intval($data['expires_at']) - 300)) {
            return '';
        }
        return $data['payment_token'];
    }

    private function write_cached_payment_token($token, $expires_in)
    {
        $payload = [
            'payment_token' => $token,
            'expires_at' => time() + max(60, intval($expires_in)),
            'saved_at' => date('Y-m-d H:i:s'),
            'environment' => (strpos($this->base_url, 'apis-tem') !== false) ? 'tem' : 'production',
        ];
        $this->write_setting('usps_payment_token', $payload);
        @file_put_contents($this->payment_token_cache_file, json_encode($payload));
    }

    private function read_cached_token()
    {
        $data = $this->read_setting('usps_oauth_token');
        if (empty($data['access_token']) || empty($data['expires_at'])) {
            // Fallback: legacy file cache
            if (!is_file($this->token_cache_file)) {
                return '';
            }
            $raw = @file_get_contents($this->token_cache_file);
            $data = (!empty($raw)) ? json_decode($raw, true) : [];
            if (empty($data['access_token']) || empty($data['expires_at'])) {
                return '';
            }
        }

        $current_env = (strpos($this->base_url, 'apis-tem') !== false) ? 'tem' : 'production';
        if (!empty($data['environment']) && $data['environment'] !== $current_env) {
            return '';
        }

        if (time() >= (intval($data['expires_at']) - 300)) {
            return '';
        }

        return $data['access_token'];
    }

    /**
     * Persist OAuth access token + full USPS token API response in settings DB.
     *
     * @param string $token
     * @param int $expires_in
     * @param array $response Full JSON body from /oauth2/v3/token
     */
    private function write_cached_token($token, $expires_in, $response = [])
    {
        $payload = [
            'access_token' => $token,
            'expires_at' => time() + max(60, intval($expires_in)),
            'expires_in' => intval($expires_in),
            'saved_at' => date('Y-m-d H:i:s'),
            'environment' => (strpos($this->base_url, 'apis-tem') !== false) ? 'tem' : 'production',
            'response' => is_array($response) ? $response : [],
        ];

        $this->write_setting('usps_oauth_token', $payload);
        @file_put_contents($this->token_cache_file, json_encode($payload));
    }

    private function read_setting($variable)
    {
        $CI = &get_instance();
        if (!isset($CI->db)) {
            return [];
        }
        $row = $CI->db->select('value')->where('variable', $variable)->get('settings')->row_array();
        if (empty($row['value'])) {
            return [];
        }
        $decoded = json_decode($row['value'], true);
        return is_array($decoded) ? $decoded : [];
    }

    private function write_setting($variable, $data)
    {
        $CI = &get_instance();
        if (!isset($CI->db)) {
            return;
        }
        $value = json_encode($data);
        $exists = $CI->db->where('variable', $variable)->count_all_results('settings');
        if ($exists > 0) {
            $CI->db->where('variable', $variable)->update('settings', ['value' => $value]);
        } else {
            $CI->db->insert('settings', [
                'variable' => $variable,
                'value' => $value,
            ]);
        }
    }

    private function delete_setting($variable)
    {
        $CI = &get_instance();
        if (!isset($CI->db)) {
            return;
        }
        $CI->db->where('variable', $variable)->delete('settings');
    }
}
