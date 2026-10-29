<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * TFA4: gives every existing user a hashed password.
 *
 *   php spark db:seed UserPasswordSeeder
 *
 * Only users that have no password yet are touched, so running it twice
 * never overwrites a password somebody has already changed.
 *
 * Every seeded account gets the shared DEMO password below so the grader
 * can sign in. Each user still gets its own hash (password_hash() adds a
 * random salt every time). Change these passwords on any real deployment.
 */
class UserPasswordSeeder extends Seeder
{
    public const DEMO_PASSWORD = 'MorrisPOS@2026';

    public function run()
    {
        $users = $this->db->table('users')
            ->select('id')
            ->groupStart()
                ->where('password', null)
                ->orWhere('password', '')
            ->groupEnd()
            ->get()
            ->getResultArray();

        foreach ($users as $user) {
            $this->db->table('users')
                ->where('id', $user['id'])
                ->update(['password' => password_hash(self::DEMO_PASSWORD, PASSWORD_DEFAULT)]);
        }

        echo count($users) . " user(s) received a hashed demo password.\n";
    }
}
