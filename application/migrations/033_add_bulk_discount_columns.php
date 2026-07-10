<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_bulk_discount_columns extends CI_Migration
{

    public function up()
    {
        $fields = array(
            'bulk_discount_min_qty' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => FALSE,
                'after' => 'quantity_step_size'
            ),
            'bulk_discount_amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'default' => 0.00,
                'null' => FALSE,
                'after' => 'bulk_discount_min_qty'
            )
        );
        $this->dbforge->add_column('products', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('products', 'bulk_discount_min_qty');
        $this->dbforge->drop_column('products', 'bulk_discount_amount');
    }
}
