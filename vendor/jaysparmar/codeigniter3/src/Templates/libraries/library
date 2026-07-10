<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * {{LIBRARY_NAME}} Library
 * 
 * @package    CodeIgniter
 * @subpackage Libraries
 * @category   Library
 * @author     {{AUTHOR}}
 * @created    {{DATE}}
 */
class {{LIBRARY_NAME}}
{
    /**
     * CodeIgniter instance
     * 
     * @var object
     */
    protected $CI;
    
    /**
     * Configuration array
     * 
     * @var array
     */
    protected $config = array();
    
    /**
     * Default configuration
     * 
     * @var array
     */
    protected $default_config = array(
        'debug' => FALSE,
        'cache_enabled' => TRUE,
        'cache_ttl' => 3600,
        'auto_load' => TRUE
    );
    
    /**
     * Constructor
     * 
     * @param array $config
     */
    public function __construct($config = array())
    {
        // Get CodeIgniter instance
        $this->CI =& get_instance();
        
        // Merge configuration
        $this->config = array_merge($this->default_config, $config);
        
        // Initialize library
        $this->initialize();
        
        log_message('info', '{{LIBRARY_NAME}} Library Initialized');
    }
    
    /**
     * Initialize library
     * 
     * @return void
     */
    public function initialize()
    {
        // Load required helpers
        $this->CI->load->helper(['url', 'string']);
        
        // Load required libraries
        // $this->CI->load->library(['session', 'encryption']);
        
        // Perform initialization tasks
        if ($this->config['debug']) {
            log_message('debug', '{{LIBRARY_NAME}} Library: Debug mode enabled');
        }
    }
    
    /**
     * Set configuration
     * 
     * @param array $config
     * @return $this
     */
    public function set_config($config = array())
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }
    
    /**
     * Get configuration value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get_config($key = null, $default = null)
    {
        if ($key === null) {
            return $this->config;
        }
        
        return isset($this->config[$key]) ? $this->config[$key] : $default;
    }
    
    /**
     * Main processing method
     * 
     * @param mixed $data
     * @param array $options
     * @return mixed
     */
    public function process($data, $options = array())
    {
        try {
            // Validate input
            if (empty($data)) {
                throw new Exception('Data cannot be empty');
            }
            
            // Merge options with config
            $options = array_merge($this->config, $options);
            
            // Process data
            $result = $this->_process_data($data, $options);
            
            // Log success
            if ($this->config['debug']) {
                log_message('debug', '{{LIBRARY_NAME}} Library: Data processed successfully');
            }
            
            return $result;
            
        } catch (Exception $e) {
            log_message('error', '{{LIBRARY_NAME}} Library Error: ' . $e->getMessage());
            return FALSE;
        }
    }
    
    /**
     * Validate data
     * 
     * @param mixed $data
     * @return bool
     */
    public function validate($data)
    {
        // Basic validation
        if (empty($data)) {
            return FALSE;
        }
        
        // Add custom validation logic here
        
        return TRUE;
    }
    
    /**
     * Format data
     * 
     * @param mixed $data
     * @param string $format
     * @return mixed
     */
    public function format($data, $format = 'array')
    {
        switch ($format) {
            case 'json':
                return json_encode($data);
                
            case 'xml':
                return $this->_array_to_xml($data);
                
            case 'csv':
                return $this->_array_to_csv($data);
                
            case 'array':
            default:
                return $data;
        }
    }
    
    /**
     * Cache data
     * 
     * @param string $key
     * @param mixed $data
     * @param int $ttl
     * @return bool
     */
    public function cache_set($key, $data, $ttl = null)
    {
        if (!$this->config['cache_enabled']) {
            return FALSE;
        }
        
        $ttl = $ttl ?: $this->config['cache_ttl'];
        
        // Use CodeIgniter cache driver
        $this->CI->load->driver('cache');
        return $this->CI->cache->save($key, $data, $ttl);
    }
    
    /**
     * Get cached data
     * 
     * @param string $key
     * @return mixed
     */
    public function cache_get($key)
    {
        if (!$this->config['cache_enabled']) {
            return FALSE;
        }
        
        $this->CI->load->driver('cache');
        return $this->CI->cache->get($key);
    }
    
    /**
     * Delete cached data
     * 
     * @param string $key
     * @return bool
     */
    public function cache_delete($key)
    {
        if (!$this->config['cache_enabled']) {
            return FALSE;
        }
        
        $this->CI->load->driver('cache');
        return $this->CI->cache->delete($key);
    }
    
    /**
     * Process data (private method)
     * 
     * @param mixed $data
     * @param array $options
     * @return mixed
     */
    private function _process_data($data, $options)
    {
        // Add your data processing logic here
        
        // Example: Simple data transformation
        if (is_array($data)) {
            return array_map('trim', $data);
        }
        
        return trim($data);
    }
    
    /**
     * Convert array to XML
     * 
     * @param array $data
     * @param string $root_element
     * @return string
     */
    private function _array_to_xml($data, $root_element = 'root')
    {
        $xml = new SimpleXMLElement("<{$root_element}/>");
        
        $this->_array_to_xml_recursive($data, $xml);
        
        return $xml->asXML();
    }
    
    /**
     * Recursive array to XML conversion
     * 
     * @param array $data
     * @param SimpleXMLElement $xml
     * @return void
     */
    private function _array_to_xml_recursive($data, &$xml)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subnode = $xml->addChild($key);
                $this->_array_to_xml_recursive($value, $subnode);
            } else {
                $xml->addChild($key, htmlspecialchars($value));
            }
        }
    }
    
    /**
     * Convert array to CSV
     * 
     * @param array $data
     * @return string
     */
    private function _array_to_csv($data)
    {
        if (empty($data)) {
            return '';
        }
        
        $output = fopen('php://temp', 'r+');
        
        // Add headers if associative array
        if (is_array(reset($data))) {
            fputcsv($output, array_keys(reset($data)));
        }
        
        // Add data rows
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
    
    /**
     * Get library version
     * 
     * @return string
     */
    public function version()
    {
        return '1.0.0';
    }
    
    /**
     * Get library info
     * 
     * @return array
     */
    public function info()
    {
        return array(
            'name' => '{{LIBRARY_NAME}}',
            'version' => $this->version(),
            'author' => '{{AUTHOR}}',
            'created' => '{{DATE}}',
            'config' => $this->config
        );
    }
}