<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Change_mobile_length_in_orders_table extends CI_Migration
{
    public function up()
    {
        $fields = [
            'mobile' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => FALSE,
            ]
        ];

        $this->dbforge->modify_column('orders', $fields);
    }

    public function down()
    {
        $fields = [
            'mobile' => [
                'type'       => 'VARCHAR',
                'constraint' => 12,
                'null'       => FALSE,
            ]
        ];

        $this->dbforge->modify_column('orders', $fields);
    }
}
