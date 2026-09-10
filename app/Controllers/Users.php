<?php

namespace App\Controllers;

class Users extends BaseController
{
    public function index()
    {
        $data['users'] = [
            [
                'name'  => 'Admin User',
                'email' => 'admin@example.com',
                'role'  => 'Administrator',
            ],
            [
                'name'  => 'Juan Dela Cruz',
                'email' => 'juan@example.com',
                'role'  => 'Cashier',
            ],
            [
                'name'  => 'Maria Santos',
                'email' => 'maria@example.com',
                'role'  => 'Manager',
            ],
        ];

        return view('users/index', $data);
    }
}