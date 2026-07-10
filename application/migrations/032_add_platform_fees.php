<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_platform_fees extends CI_Migration
{
    public function up()
    {
        $fields = [
            'platform_fees' => [
                'type'       => 'FLOAT',
                'null'       => FALSE,
                'default'    => 0,
            ]
        ];

        $this->dbforge->add_column('orders', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('orders', 'platform_fees');
    }
}
