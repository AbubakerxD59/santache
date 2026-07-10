<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * {{MODEL_NAME}} Model
 * 
 * @package    CodeIgniter
 * @subpackage Models
 * @category   Model
 * @author     {{AUTHOR}}
 * @created    {{DATE}}
 */
class {{MODEL_NAME}} extends CI_Model
{
    /**
     * Table name
     * 
     * @var string
     */
    protected $table = '{{TABLE_NAME}}';
    
    /**
     * Primary key
     * 
     * @var string
     */
    protected $primary_key = 'id';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Get all records
     * 
     * @param array $where
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function get_all($where = array(), $limit = null, $offset = null)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get($this->table);
        return $query->result_array();
    }
    
    /**
     * Get single record by ID
     * 
     * @param int $id
     * @return array|null
     */
    public function get_by_id($id)
    {
        $query = $this->db->get_where($this->table, array($this->primary_key => $id));
        return $query->row_array();
    }
    
    /**
     * Get single record by conditions
     * 
     * @param array $where
     * @return array|null
     */
    public function get_by($where = array())
    {
        $query = $this->db->get_where($this->table, $where);
        return $query->row_array();
    }
    
    /**
     * Insert new record
     * 
     * @param array $data
     * @return int|bool
     */
    public function insert($data)
    {
        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        return false;
    }
    
    /**
     * Update record
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Delete record
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $this->db->where($this->primary_key, $id);
        return $this->db->delete($this->table);
    }
    
    /**
     * Count records
     * 
     * @param array $where
     * @return int
     */
    public function count_all($where = array())
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        
        return $this->db->count_all_results($this->table);
    }
    
    /**
     * Check if record exists
     * 
     * @param array $where
     * @return bool
     */
    public function exists($where = array())
    {
        return $this->count_all($where) > 0;
    }
}