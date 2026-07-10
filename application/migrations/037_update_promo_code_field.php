<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_update_promo_code_field extends CI_Migration {

    public function up()
    {
        // Change column type to TEXT (DBForge style)
        $fields = array(
            'promo_code' => array(
                'name'       => 'promo_code',
                'type'       => 'TEXT',
                'null'       => FALSE,
            )
        );
        $this->dbforge->modify_column('promo_codes', $fields);

        // Apply charset + collation (DBForge cannot do this part)
        $this->db->query("
            ALTER TABLE `promo_codes` 
            MODIFY `promo_code` 
            TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
        ");
    }

    public function down()
    {
        // Revert to original definition (assuming VARCHAR 255)
        $fields = array(
            'promo_code' => array(
                'name'       => 'promo_code',
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => FALSE,
            )
        );
        $this->dbforge->modify_column('promo_codes', $fields);
    }
}
