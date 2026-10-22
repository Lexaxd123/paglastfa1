<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToUsers extends Migration
{
    public function up()
    {
        $fields = [];

        if (! $this->db->fieldExists('full_name', 'users')) {
            $fields['full_name'] = [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
                'after'      => 'username',
            ];
        }

        if (! $this->db->fieldExists('avatar', 'users')) {
            $fields['avatar'] = [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'status',
            ];
        }

        if (! empty($fields)) {
            $this->forge->addColumn('users', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('avatar', 'users')) {
            $this->forge->dropColumn('users', 'avatar');
        }

        if ($this->db->fieldExists('full_name', 'users')) {
            $this->forge->dropColumn('users', 'full_name');
        }
    }
}