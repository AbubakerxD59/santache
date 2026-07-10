<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_City_wise_delivery_charges extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_column('cities', [
            'minimum_free_delivery_order_amount' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => TRUE,
                'default' => NULL,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'delivery_charges' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => TRUE,
                'default' => NULL,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]
        ]);

       
    }

    public function down()
    {
        // // Drop the newly added columns
        $this->dbforge->drop_column('cities', 'delivery_charges');
        $this->dbforge->drop_column('cities', 'minimum_free_delivery_order_amount');
    }
}
