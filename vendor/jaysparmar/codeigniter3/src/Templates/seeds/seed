<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * {{SEED_NAME}} Seeder
 * 
 * @package    CodeIgniter
 * @subpackage Seeds
 * @category   Seeder
 * @author     {{AUTHOR}}
 * @created    {{DATE}}
 */
class {{SEED_NAME}} extends CI_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        
        // Only allow CLI access
        if (!$this->input->is_cli_request()) {
            show_error('This script can only be accessed via the command line.', 403);
        }
    }
    
    /**
     * Run the seeder
     * 
     * @return void
     */
    public function run()
    {
        echo "🌱 Running {{SEED_NAME}} seeder...\n";
        
        try {
            // Clear existing data (optional)
            // $this->db->truncate('{{TABLE_NAME}}');
            
            // Seed data
            $this->seed_{{TABLE_NAME}}();
            
            echo "✅ {{SEED_NAME}} seeder completed successfully!\n";
            
        } catch (Exception $e) {
            echo "❌ Error running {{SEED_NAME}} seeder: " . $e->getMessage() . "\n";
        }
    }
    
    /**
     * Seed {{TABLE_NAME}} table
     * 
     * @return void
     */
    private function seed_{{TABLE_NAME}}()
    {
        $data = array(
            array(
                'name' => 'Sample Record 1',
                'description' => 'This is a sample record for testing',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ),
            array(
                'name' => 'Sample Record 2',
                'description' => 'This is another sample record for testing',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ),
            array(
                'name' => 'Sample Record 3',
                'description' => 'This is a third sample record for testing',
                'status' => 'inactive',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            )
        );
        
        // Insert data in batches for better performance
        if ($this->db->insert_batch('{{TABLE_NAME}}', $data)) {
            echo "   ✓ Inserted " . count($data) . " records into {{TABLE_NAME}} table\n";
        } else {
            throw new Exception("Failed to insert data into {{TABLE_NAME}} table");
        }
    }
    
    /**
     * Generate fake data (optional helper method)
     * 
     * @param int $count
     * @return array
     */
    private function generate_fake_data($count = 10)
    {
        $data = array();
        
        for ($i = 1; $i <= $count; $i++) {
            $data[] = array(
                'name' => 'Generated Record ' . $i,
                'description' => 'This is a generated record #' . $i . ' for testing purposes',
                'status' => ($i % 2 == 0) ? 'active' : 'inactive',
                'created_at' => date('Y-m-d H:i:s', strtotime("-{$i} days")),
                'updated_at' => date('Y-m-d H:i:s')
            );
        }
        
        return $data;
    }
    
    /**
     * Truncate table (helper method)
     * 
     * @param string $table
     * @return void
     */
    private function truncate_table($table)
    {
        if ($this->db->table_exists($table)) {
            $this->db->truncate($table);
            echo "   ✓ Truncated {$table} table\n";
        } else {
            echo "   ⚠ Table {$table} does not exist\n";
        }
    }
}