<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: {{MIGRATION_NAME}}
 * 
 * @package    CodeIgniter
 * @subpackage Migrations
 * @category   Migration
 * @author     {{AUTHOR}}
 * @created    {{DATE}}
 */
class Migration_{{MIGRATION_CLASS}} extends CI_Migration
{
    /**
     * Migration Up
     * 
     * @return void
     */
    public function up()
    {
        // Create table: {{TABLE_NAME}}
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => array('active', 'inactive'),
                'default' => 'active'
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            )
        ));
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('{{TABLE_NAME}}');
        
        // Add indexes
        $this->db->query('ALTER TABLE `{{TABLE_NAME}}` ADD INDEX `idx_status` (`status`)');
        $this->db->query('ALTER TABLE `{{TABLE_NAME}}` ADD INDEX `idx_created_at` (`created_at`)');
        
        // Insert sample data (optional)
        /*
        $data = array(
            array(
                'name' => 'Sample Record 1',
                'description' => 'This is a sample record',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ),
            array(
                'name' => 'Sample Record 2',
                'description' => 'This is another sample record',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            )
        );
        
        $this->db->insert_batch('{{TABLE_NAME}}', $data);
        */
    }
    
    /**
     * Migration Down
     * 
     * @return void
     */
    public function down()
    {
        // Drop table: {{TABLE_NAME}}
        $this->dbforge->drop_table('{{TABLE_NAME}}');
    }
}