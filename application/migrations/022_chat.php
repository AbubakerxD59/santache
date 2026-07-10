<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_chat extends CI_Migration
{
    public function up()
    {
        /* adding new table chat_media */
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE,
                'NULL'           => FALSE
            ],
            'message_id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'NULL'           => FALSE
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'NULL'           => TRUE
            ],
            'original_file_name' => [
                'type'           => 'TEXT',
                'NULL'           => FALSE
            ],
            'file_name' => [
                'type'           => 'TEXT',
                'NULL'           => FALSE
            ],
            'file_extension' => [
                'type'           => 'VARCHAR',
                'constraint'     => '64',
                'NULL'           => FALSE
            ],
            'file_size' => [
                'type'           => 'VARCHAR',
                'constraint'     => '256',
                'NULL'           => FALSE
            ],
            'date_created TIMESTAMP default CURRENT_TIMESTAMP',

        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('chat_media');


        /* adding new table messages */
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE,
                'NULL'           => FALSE
            ],
            'from_id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'NULL'           => FALSE
            ],
            'to_id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'NULL'           => FALSE
            ],
            'is_read' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'NULL'           => FALSE,
                'default'        => '1',
            ],
            'message' => [
                'type'           => 'TEXT',
                'NULL'           => FALSE
            ],
            'type' => [
                'type'           => 'VARCHAR',
                'constraint'     => '128',
                'NULL'           => FALSE
            ],
            'media' => [
                'type'           => 'VARCHAR',
                'constraint'     => '256',
                'NULL'           => FALSE
            ],
            'date_created TIMESTAMP default CURRENT_TIMESTAMP',

        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('messages');

        /* adding new fields in users table */
        $fields = array(
            'last_online' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => TRUE,
                'after' => 'last_login'
            ),
            'web_fcm' => array(
                'type' => 'VARCHAR',
                'constraint' => '1024',
                'null' => TRUE,
                'after' => 'last_login'
            ),
        );
        $this->dbforge->add_column('users', $fields);

        $data = array(
            array(
                'variable' => 'vap_id_Key',
                'value' => '',
            ),
        );
        $this->db->insert_batch('settings', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('chat_media');
        $this->dbforge->drop_table('messages');
        $this->dbforge->drop_column('users', 'last_online');
        $this->dbforge->drop_column('users', 'web_fcm');
    }
}
