<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * TFA4: adds the password column to the users table.
 *
 * The column holds a password_hash() value (bcrypt, 60 characters today;
 * 255 leaves room for stronger algorithms later), NEVER a plain password.
 * It is nullable so existing rows survive the upgrade; run the
 * UserPasswordSeeder afterwards to give every existing user a hash.
 * A user whose password is NULL cannot log in.
 *
 * Equivalent SQL: database/tfa4_add_password.sql
 */
class AddPasswordToUsers extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('password', 'users')) {
            $this->forge->addColumn('users', [
                'password' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'avatar',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('password', 'users')) {
            $this->forge->dropColumn('users', 'password');
        }
    }
}
