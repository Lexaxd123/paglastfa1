<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * TFA3: adds the avatar column to the users table.
 *
 * Only the filename of the prepared (resized) avatar is stored here;
 * the image file itself lives in public/uploads/avatars/.
 *
 * Equivalent SQL: database/tfa3_add_avatar.sql
 */
class AddFieldsToUsers extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('avatar', 'users')) {
            $this->forge->addColumn('users', [
                'avatar' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'full_name',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('avatar', 'users')) {
            $this->forge->dropColumn('users', 'avatar');
        }
    }
}
