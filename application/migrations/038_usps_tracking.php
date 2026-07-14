<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_usps_tracking extends CI_Migration
{
    public function up()
    {
        $fields = [
            'usps_tracking_number' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => TRUE,
                'default' => NULL,
                'after' => 'active_status',
            ],
            'usps_tracking_status' => [
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => NULL,
                'after' => 'usps_tracking_number',
            ],
            'usps_label_url' => [
                'type' => 'VARCHAR',
                'constraint' => '512',
                'null' => TRUE,
                'default' => NULL,
                'after' => 'usps_tracking_status',
            ],
            'usps_tracking_updated_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
                'default' => NULL,
                'after' => 'usps_label_url',
            ],
        ];

        if (!$this->db->field_exists('usps_tracking_number', 'orders')) {
            $this->dbforge->add_column('orders', $fields);
        }
    }

    public function down()
    {
        if ($this->db->field_exists('usps_tracking_updated_at', 'orders')) {
            $this->dbforge->drop_column('orders', 'usps_tracking_updated_at');
        }
        if ($this->db->field_exists('usps_label_url', 'orders')) {
            $this->dbforge->drop_column('orders', 'usps_label_url');
        }
        if ($this->db->field_exists('usps_tracking_status', 'orders')) {
            $this->dbforge->drop_column('orders', 'usps_tracking_status');
        }
        if ($this->db->field_exists('usps_tracking_number', 'orders')) {
            $this->dbforge->drop_column('orders', 'usps_tracking_number');
        }
    }
}
