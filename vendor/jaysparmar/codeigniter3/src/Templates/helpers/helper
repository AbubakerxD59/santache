<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * {{HELPER_NAME}} Helper
 * 
 * @package    CodeIgniter
 * @subpackage Helpers
 * @category   Helper
 * @author     {{AUTHOR}}
 * @created    {{DATE}}
 */

// ------------------------------------------------------------------------

if (!function_exists('{{HELPER_FUNCTION_PREFIX}}_format'))
{
    /**
     * Format data using {{HELPER_NAME}} helper
     * 
     * @param mixed $data
     * @param string $format
     * @return mixed
     */
    function {{HELPER_FUNCTION_PREFIX}}_format($data, $format = 'default')
    {
        if (empty($data)) {
            return $data;
        }
        
        switch ($format) {
            case 'uppercase':
                return is_string($data) ? strtoupper($data) : $data;
                
            case 'lowercase':
                return is_string($data) ? strtolower($data) : $data;
                
            case 'title':
                return is_string($data) ? ucwords($data) : $data;
                
            case 'trim':
                return is_string($data) ? trim($data) : $data;
                
            case 'clean':
                return is_string($data) ? strip_tags(trim($data)) : $data;
                
            default:
                return $data;
        }
    }
}

// ------------------------------------------------------------------------

if (!function_exists('{{HELPER_FUNCTION_PREFIX}}_validate'))
{
    /**
     * Validate data using {{HELPER_NAME}} helper
     * 
     * @param mixed $data
     * @param string $type
     * @return bool
     */
    function {{HELPER_FUNCTION_PREFIX}}_validate($data, $type = 'required')
    {
        switch ($type) {
            case 'required':
                return !empty($data);
                
            case 'email':
                return filter_var($data, FILTER_VALIDATE_EMAIL) !== FALSE;
                
            case 'url':
                return filter_var($data, FILTER_VALIDATE_URL) !== FALSE;
                
            case 'numeric':
                return is_numeric($data);
                
            case 'integer':
                return filter_var($data, FILTER_VALIDATE_INT) !== FALSE;
                
            case 'float':
                return filter_var($data, FILTER_VALIDATE_FLOAT) !== FALSE;
                
            case 'alpha':
                return ctype_alpha($data);
                
            case 'alphanumeric':
                return ctype_alnum($data);
                
            case 'date':
                return strtotime($data) !== FALSE;
                
            default:
                return TRUE;
        }
    }
}

// ------------------------------------------------------------------------

if (!function_exists('{{HELPER_FUNCTION_PREFIX}}_generate'))
{
    /**
     * Generate data using {{HELPER_NAME}} helper
     * 
     * @param string $type
     * @param int $length
     * @return mixed
     */
    function {{HELPER_FUNCTION_PREFIX}}_generate($type = 'string', $length = 10)
    {
        switch ($type) {
            case 'string':
                return {{HELPER_FUNCTION_PREFIX}}_random_string($length);
                
            case 'number':
                return {{HELPER_FUNCTION_PREFIX}}_random_number($length);
                
            case 'uuid':
                return {{HELPER_FUNCTION_PREFIX}}_generate_uuid();
                
            case 'token':
                return {{HELPER_FUNCTION_PREFIX}}_generate_token($length);
                
            case 'password':
                return {{HELPER_FUNCTION_PREFIX}}_generate_password($length);
                
            default:
                return '';
        }
    }
}

// ------------------------------------------------------------------------

if (!function_exists('{{HELPER_FUNCTION_PREFIX}}_random_string'))
{
    /**
     * Generate random string
     * 
     * @param int $length
     * @param string $characters
     * @return string
     */
    function {{HELPER_FUNCTION_PREFIX}}_random_string($length = 10, $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
    {
        $string = '';
        $max = strlen($characters) - 1;
        
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[random_int(0, $max)];
        }
        
        return $string;
    }
}

// ------------------------------------------------------------------------

if (!function_exists('{{HELPER_FUNCTION_PREFIX}}_random_number'))
{
    /**
     * Generate random number
     * 
     * @param int $length
     * @return string
     */
    function {{HELPER_FUNCTION_PREFIX}}_random_number($length = 6)
    {
        $number = '';
        
        for ($i = 0; $i < $length; $i++) {
            $number .= random_int(0, 9);
        }
        
        return $number;
    }
}

// ------------------------------------------------------------------------

if (!function_exists('{{HELPER_FUNCTION_PREFIX}}_generate_uuid'))
{
    /**
     * Generate UUID v4
     * 
     * @return string
     */
    function {{HELPER_FUNCTION_PREFIX}}_generate_uuid()
    {
        $data = random_bytes(16);
        
        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

// ------------------------------------------------------------------------

if (!function_exists('{{HELPER_FUNCTION_PREFIX}}_generate_token'))
{
    /**
     * Generate secure token
     * 
     * @param int $length
     * @return string
     */
    function {{HELPER_FUNCTION_PREFIX}}_generate_token($length = 32)
    {
        return bin2hex(random_bytes($length / 2));
    }
}

// ------------------------------------------------------------------------

if (!function_exists('{{HELPER_FUNCTION_PREFIX}}_generate_password'))
{
    /**
     * Generate secure password
     * 
     * @param int $length
     * @return string
     */
    function {{HELPER_FUNCTION_PREFIX}}_generate_password($length = 12)
    {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';
        
        $password = '';
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];
        
        $all_characters = $lowercase . $uppercase . $numbers . $symbols;
        
        for ($i = 4; $i < $length; $i++) {
            $password .= $all_characters[random_int(0, strlen($all_characters) - 1)];
        }
        
        return str_shuffle($password);
    }
}

// ------------------------------------------------------------------------

if (!function_exists('{{HELPER_FUNCTION_PREFIX}}_array_to_object'))
{
    /**
     * Convert array to object
     * 
     * @param array $array
     * @return object
     */
    function {{HELPER_FUNCTION_PREFIX}}_array_to_object($array)
    {
        if (is_array($array)) {
            return (object) array_map('{{HELPER_FUNCTION_PREFIX}}_array_to_object', $array);
        }
        
        return $array;
    }
}

// ------------------------------------------------------------------------

if (!function_exists('{{HELPER_FUNCTION_PREFIX}}_object_to_array'))
{
    /**
     * Convert object to array
     * 
     * @param object $object
     * @return array
     */
    function {{HELPER_FUNCTION_PREFIX}}_object_to_array($object)
    {
        if (is_object($object)) {
            $object = get_object_vars($object);
        }
        
        if (is_array($object)) {
            return array_map('{{HELPER_FUNCTION_PREFIX}}_object_to_array', $object);
        }
        
        return $object;
    }
}

// ------------------------------------------------------------------------

if (!function_exists('{{HELPER_FUNCTION_PREFIX}}_sanitize'))
{
    /**
     * Sanitize input data
     * 
     * @param mixed $data
     * @param string $type
     * @return mixed
     */
    function {{HELPER_FUNCTION_PREFIX}}_sanitize($data, $type = 'string')
    {
        switch ($type) {
            case 'string':
                return is_string($data) ? htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8') : $data;
                
            case 'email':
                return filter_var($data, FILTER_SANITIZE_EMAIL);
                
            case 'url':
                return filter_var($data, FILTER_SANITIZE_URL);
                
            case 'int':
                return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
                
            case 'float':
                return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                
            default:
                return $data;
        }
    }
}

// ------------------------------------------------------------------------

if (!function_exists('{{HELPER_FUNCTION_PREFIX}}_time_ago'))
{
    /**
     * Get time ago string
     * 
     * @param string $datetime
     * @return string
     */
    function {{HELPER_FUNCTION_PREFIX}}_time_ago($datetime)
    {
        $time = time() - strtotime($datetime);
        
        if ($time < 1) return 'just now';
        
        $tokens = array(
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . ' ago';
        }
        
        return $datetime;
    }
}

// ------------------------------------------------------------------------

if (!function_exists('{{HELPER_FUNCTION_PREFIX}}_file_size'))
{
    /**
     * Format file size
     * 
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    function {{HELPER_FUNCTION_PREFIX}}_file_size($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

// ------------------------------------------------------------------------

if (!function_exists('{{HELPER_FUNCTION_PREFIX}}_truncate'))
{
    /**
     * Truncate string
     * 
     * @param string $string
     * @param int $length
     * @param string $suffix
     * @return string
     */
    function {{HELPER_FUNCTION_PREFIX}}_truncate($string, $length = 100, $suffix = '...')
    {
        if (strlen($string) <= $length) {
            return $string;
        }
        
        return substr($string, 0, $length) . $suffix;
    }
}

/* End of file {{HELPER_NAME}}_helper.php */
/* Location: ./application/helpers/{{HELPER_NAME}}_helper.php */