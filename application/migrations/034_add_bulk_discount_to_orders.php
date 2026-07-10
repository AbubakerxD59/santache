<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_bulk_discount_to_orders extends CI_Migration
{

    public function up()
    {
        $fields = array(
            'bulk_discount' => array(
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'default' => 0.00,
                'null' => FALSE,
                'after' => 'promo_discount'
            )
        );
        $this->dbforge->add_column('orders', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('orders', 'bulk_discount');
    }
}
