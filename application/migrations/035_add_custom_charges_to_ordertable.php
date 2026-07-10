<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_custom_charges_to_ordertable  extends CI_Migration
{
  public function up()
    {
        $fields = [
            'custom_charges' => [
                'type'       => 'TEXT',
                'null'       => TRUE,
                'after' => 'platform_fees',
            ]
        ];
        $this->dbforge->add_column('orders', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('orders', 'custom_charges');
    }
}
