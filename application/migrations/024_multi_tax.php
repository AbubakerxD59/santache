<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_multi_tax extends CI_Migration
{

    public function up()
    {
        // Add a new 'tax_id' column in 'order_items' table
        $this->dbforge->add_column('order_items', [
            'tax_id' => [
                'type' => 'VARCHAR',
                'constraint' => '1025',
                'null' => TRUE,
                'default' => NULL,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]
        ]);

        // Add a new 'notification_sended' column in 'cart' table
        $this->dbforge->add_column('cart', [
            'notification_sended' => [
                'type' => 'INT',
                'constraint' => '128',
                'null' => FALSE,
                'default' => '0',
                'comment' => '0:not send|1:sended',
            ]
        ]);

        // Modify the 'tax' column in 'products' table
        $this->dbforge->modify_column('products', [
            'tax' => [
                'type' => 'VARCHAR',
                'constraint' => '1025',
                'null' => TRUE,
                'default' => NULL,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]
        ]);
    }

    public function down()
    {
        // Drop the newly added columns
        $this->dbforge->drop_column('cart', 'notification_sended');
        $this->dbforge->drop_column('order_items', 'tax_id');

        // Optionally, you can add code to revert the change to 'products' table
        $this->dbforge->modify_column('products', [
            'tax' => [
                'type' => 'VARCHAR',
                'constraint' => '255', // Or whatever the original constraint was
                'null' => TRUE,
            ]
        ]);
    }
}
