<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class Customers extends BaseController
{
    public function index()
    {
        $customerModel = new CustomerModel();

        // Query Builder: SELECT * FROM customers ORDER BY id ASC
        $data['customers'] = $customerModel->orderBy('id', 'ASC')->findAll();

        return view('customers/index', $data);
    }
}
