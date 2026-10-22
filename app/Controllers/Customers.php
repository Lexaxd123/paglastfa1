<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Customers extends BaseController
{
    protected CustomerModel $customerModel;

    /**
     * Validation rules shared by the New and Edit forms.
     * Names match the <input name="..."> fields and the database columns.
     */
    protected array $rules = [
        'full_name' => [
            'label' => 'Full Name',
            'rules' => 'required|min_length[2]|max_length[100]',
        ],
        'email' => [
            'label' => 'Email Address',
            'rules' => 'required|valid_email|max_length[100]',
        ],
        'phone' => [
            'label'  => 'Phone Number',
            'rules'  => 'permit_empty|max_length[20]|regex_match[/^[0-9+()\-\s]+$/]',
            'errors' => [
                'regex_match' => 'The {field} may only contain numbers, spaces, and + - ( ).',
            ],
        ],
    ];

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        helper(['form', 'url']);
    }

    /**
     * GET /customers
     */
    public function index()
    {
        return view('customers/index', [
            'customers' => $this->customerModel->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    /**
     * GET /customers/new
     */
    public function new()
    {
        return view('customers/form', [
            'title'    => 'Add New Customer',
            'customer' => [],
            'errors'   => [],
        ]);
    }

    /**
     * POST /customers/create
     */
    public function create()
    {
        $data = $this->postedCustomer();

        if (! $this->validateData($data, $this->rules)) {
            // Redisplay the form with the errors AND what the user typed.
            return view('customers/form', [
                'title'    => 'Add New Customer',
                'customer' => $data,
                'errors'   => $this->validator->getErrors(),
            ]);
        }

        $this->customerModel->insert($data);

        return redirect()->to('/customers')
            ->with('success', 'Customer "' . $data['full_name'] . '" was added.');
    }

    /**
     * GET /customers/edit/{id}
     */
    public function edit(int $id)
    {
        return view('customers/form', [
            'title'    => 'Edit Customer',
            'customer' => $this->findOr404($id),
            'errors'   => [],
        ]);
    }

    /**
     * POST /customers/update/{id}
     */
    public function update(int $id)
    {
        $this->findOr404($id);

        $data = $this->postedCustomer();

        if (! $this->validateData($data, $this->rules)) {
            return view('customers/form', [
                'title'    => 'Edit Customer',
                'customer' => ['id' => $id] + $data,
                'errors'   => $this->validator->getErrors(),
            ]);
        }

        $this->customerModel->update($id, $data);

        return redirect()->to('/customers')
            ->with('success', 'Customer "' . $data['full_name'] . '" was updated.');
    }

    /**
     * Reads only the expected fields from the submitted form.
     */
    private function postedCustomer(): array
    {
        return [
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'email'     => trim((string) $this->request->getPost('email')),
            'phone'     => trim((string) $this->request->getPost('phone')),
        ];
    }

    private function findOr404(int $id): array
    {
        $customer = $this->customerModel->find($id);

        if ($customer === null) {
            throw PageNotFoundException::forPageNotFound('Customer not found.');
        }

        return $customer;
    }
}
