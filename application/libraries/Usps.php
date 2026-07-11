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

        if (isset($settings['usps_environment']) && strtolower($settings['usps_environment']) === 'tem') {
            $this->base_url = 'https://apis-tem.usps.com';
        }

        $cache_dir = APPPATH . 'cache';
        if (!is_dir($cache_dir)) {
            @mkdir($cache_dir, 0755, true);
        }
        $this->token_cache_file = $cache_dir . '/usps_oauth_token.json';
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
        ];
    }

    public function is_configured()
    {
        return !empty($this->consumer_key) && !empty($this->consumer_secret) && !empty($this->origin_zip);
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

    public function generate_token()
    {
        if (empty($this->consumer_key) || empty($this->consumer_secret)) {
            return '';
        }

        $cached = $this->read_cached_token();
        if (!empty($cached)) {
            return $cached;
        }

        $url = $this->base_url . '/oauth2/v3/token';
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
        $token = (isset($response['access_token'])) ? $response['access_token'] : '';

        if (!empty($token) && $http_code >= 200 && $http_code < 300) {
            $expires_in = isset($response['expires_in']) ? intval($response['expires_in']) : 28800;
            $this->write_cached_token($token, $expires_in);
        }

        return $token;
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
                'message' => $message,
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

    public function curl($url, $method = 'GET', $data = [])
    {
        $token = $this->generate_token();
        if (empty($token)) {
            return [
                'error' => [
                    'message' => 'Failed to obtain USPS OAuth token. Check Consumer Key and Secret.',
                ],
            ];
        }

        $ch = curl_init();
        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/json',
        ];
        $curl_options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30,
        ];
        if (strtolower($method) == 'post') {
            $curl_options[CURLOPT_POST] = 1;
            $curl_options[CURLOPT_POSTFIELDS] = $data;
        } else {
            $curl_options[CURLOPT_CUSTOMREQUEST] = 'GET';
        }
        curl_setopt_array($ch, $curl_options);

        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded = (!empty($result)) ? json_decode($result, true) : [];
        if (!is_array($decoded)) {
            $decoded = [];
        }
        $decoded['apiStatus'] = $http_code;
        return $decoded;
    }

    private function read_cached_token()
    {
        if (!is_file($this->token_cache_file)) {
            return '';
        }
        $raw = @file_get_contents($this->token_cache_file);
        if (empty($raw)) {
            return '';
        }
        $data = json_decode($raw, true);
        if (empty($data['access_token']) || empty($data['expires_at'])) {
            return '';
        }
        // Refresh 5 minutes early
        if (time() >= (intval($data['expires_at']) - 300)) {
            return '';
        }
        return $data['access_token'];
    }

    private function write_cached_token($token, $expires_in)
    {
        $payload = json_encode([
            'access_token' => $token,
            'expires_at' => time() + max(60, intval($expires_in)),
        ]);
        @file_put_contents($this->token_cache_file, $payload);
    }
}
