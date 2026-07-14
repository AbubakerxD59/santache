<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_usps_pickup extends CI_Migration
{
    public function up()
    {
        if ($this->db->field_exists('usps_pickup_confirmation', 'orders')) {
            return;
        }

        $fields = [
            'usps_pickup_confirmation' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => TRUE,
                'default' => NULL,
            ],
            'usps_pickup_date' => [
                'type' => 'DATE',
                'null' => TRUE,
                'default' => NULL,
            ],
            'usps_pickup_status' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => TRUE,
                'default' => NULL,
            ],
        ];

        if ($this->db->field_exists('usps_tracking_updated_at', 'orders')) {
            $fields['usps_pickup_confirmation']['after'] = 'usps_tracking_updated_at';
            $fields['usps_pickup_date']['after'] = 'usps_pickup_confirmation';
            $fields['usps_pickup_status']['after'] = 'usps_pickup_date';
        }

        $this->dbforge->add_column('orders', $fields);
    }

    public function down()
    {
        if ($this->db->field_exists('usps_pickup_status', 'orders')) {
            $this->dbforge->drop_column('orders', 'usps_pickup_status');
        }
        if ($this->db->field_exists('usps_pickup_date', 'orders')) {
            $this->dbforge->drop_column('orders', 'usps_pickup_date');
        }
        if ($this->db->field_exists('usps_pickup_confirmation', 'orders')) {
            $this->dbforge->drop_column('orders', 'usps_pickup_confirmation');
        }
    }
}
