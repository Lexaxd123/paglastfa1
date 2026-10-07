<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        // Query Builder: SELECT * FROM users ORDER BY id ASC
        $data['users'] = $userModel->orderBy('id', 'ASC')->findAll();

        return view('users/index', $data);
    }
}
