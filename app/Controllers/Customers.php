<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class Customers extends BaseController
{
    protected CustomerModel $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        return view('customers/index', [
            'customers' => $this->customerModel
                ->orderBy('customer_id', 'DESC')
                ->findAll(),
        ]);
    }

    public function new()
    {
        return view('customers/form', [
            'title'    => 'Add New Customer',
            'customer' => [],
        ]);
    }

    public function create()
    {
        $data = [
            'name'         => trim((string) $this->request->getPost('name')),
            'email'        => trim((string) $this->request->getPost('email')),
            'phone_number' => trim((string) $this->request->getPost('phone_number')),
            'date_added'   => date('Y-m-d H:i:s'),
        ];

        $rules = [
            'name'  => 'required|min_length[2]|max_length[150]',
            'email' => 'required|valid_email|max_length[150]',
            'phone_number' => 'permit_empty|max_length[30]',
        ];

        if (! $this->validateData($data, $rules)) {
            return view('customers/form', [
                'title'      => 'Add New Customer',
                'customer'   => $data,
                'validation' => $this->validator,
            ]);
        }

        $this->customerModel->insert($data);

        return redirect()
            ->to('/customers')
            ->with('success', 'Customer added successfully.');
    }

    public function edit($id)
    {
        $customer = $this->customerModel->find($id);

        if (! $customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Customer not found.'
            );
        }

        return view('customers/form', [
            'title'    => 'Edit Customer',
            'customer' => $customer,
        ]);
    }

    public function update($id)
    {
        $customer = $this->customerModel->find($id);

        if (! $customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Customer not found.'
            );
        }

        $data = [
            'name'         => trim((string) $this->request->getPost('name')),
            'email'        => trim((string) $this->request->getPost('email')),
            'phone_number' => trim((string) $this->request->getPost('phone_number')),
        ];

        $rules = [
            'name'  => 'required|min_length[2]|max_length[150]',
            'email' => 'required|valid_email|max_length[150]',
            'phone_number' => 'permit_empty|max_length[30]',
        ];

        if (! $this->validateData($data, $rules)) {
            $data['customer_id'] = $id;

            return view('customers/form', [
                'title'      => 'Edit Customer',
                'customer'   => $data,
                'validation' => $this->validator,
            ]);
        }

        $this->customerModel->update($id, $data);

        return redirect()
            ->to('/customers')
            ->with('success', 'Customer updated successfully.');
    }
}