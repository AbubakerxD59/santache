<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_promo_code_column_in_orders extends CI_Migration {

    public function up()
    {
        $fields = array(
            'promo_code' => array(
                'name'       => 'promo_code',
                'type'       => 'TEXT',
                'null'       => TRUE,
                'default'    => NULL,
                'collation'  => 'utf8mb4_unicode_ci'
            )
        );

        // Modify the column
        $this->dbforge->modify_column('orders', $fields);
        $fields = array(
            'return_reason' => array(
                'name'       => 'return_reason',
                'type'       => 'TEXT',
                'null'       => TRUE,
                'default'    => NULL,
                'collation'  => 'utf8mb4_unicode_ci'
            )
        );

        // Modify the column
        $this->dbforge->modify_column('return_reasons', $fields);
    }

    public function down()
    {
        // Revert back if needed (change TEXT to previous type — adjust as per your old structure)
        $fields = array(
            'promo_code' => array(
                'name'       => 'promo_code',
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => TRUE,
                'default'    => NULL,
                'collation'  => 'utf8mb4_unicode_ci'
            )
        );

        $this->dbforge->modify_column('orders', $fields);
        $fields = array(
            'return_reason' => array(
                'name'       => 'return_reason',
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => TRUE,
                'default'    => NULL,
                'collation'  => 'utf8mb4_unicode_ci'
            )
        );

        $this->dbforge->modify_column('return_reasons', $fields);
    }
}
