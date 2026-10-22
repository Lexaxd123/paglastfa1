<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        return view('users/index', [
            'users' => $this->userModel
                ->orderBy('user_id', 'DESC')
                ->findAll(),
        ]);
    }

    public function new()
    {
        return view('users/form', [
            'title' => 'Add New User',
            'user'  => [],
        ]);
    }

    public function create()
    {
        $data = [
            'username' => trim((string) $this->request->getPost('username')),
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'password' => (string) $this->request->getPost('password'),
            'role'     => trim((string) $this->request->getPost('role')),
            'status'   => trim((string) $this->request->getPost('status')),
        ];

        $rules = [
            'username' => 'required|min_length[3]|max_length[100]',
            'full_name' => 'required|min_length[2]|max_length[150]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|max_length[50]',
            'status'   => 'required|max_length[30]',
        ];

        if (! $this->validateData($data, $rules)) {
            unset($data['password']);

            return view('users/form', [
                'title'      => 'Add New User',
                'user'       => $data,
                'validation' => $this->validator,
            ]);
        }

        $duplicate = $this->userModel
            ->where('username', $data['username'])
            ->first();

        if ($duplicate) {
            return view('users/form', [
                'title'  => 'Add New User',
                'user'   => $data,
                'errors' => ['username' => 'Username already exists.'],
            ]);
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $this->userModel->insert($data);

        return redirect()
            ->to('/users')
            ->with('success', 'User added successfully.');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);

        if (! $user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'User not found.'
            );
        }

        return view('users/form', [
            'title' => 'Edit User',
            'user'  => $user,
        ]);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);

        if (! $user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'User not found.'
            );
        }

        $data = [
            'username'  => trim((string) $this->request->getPost('username')),
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'role'      => trim((string) $this->request->getPost('role')),
            'status'    => trim((string) $this->request->getPost('status')),
        ];

        $rules = [
            'username'  => 'required|min_length[3]|max_length[100]',
            'full_name' => 'required|min_length[2]|max_length[150]',
            'role'      => 'required|max_length[50]',
            'status'    => 'required|max_length[30]',
        ];

        if (! $this->validateData($data, $rules)) {
            $data = array_merge($user, $data);

            return view('users/form', [
                'title'      => 'Edit User',
                'user'       => $data,
                'validation' => $this->validator,
            ]);
        }

        $duplicate = $this->userModel
            ->where('username', $data['username'])
            ->where('user_id !=', $id)
            ->first();

        if ($duplicate) {
            return view('users/form', [
                'title'  => 'Edit User',
                'user'   => array_merge($user, $data),
                'errors' => ['username' => 'Username already exists.'],
            ]);
        }

        $password = (string) $this->request->getPost('password');

        if ($password !== '') {
            if (strlen($password) < 6) {
                return view('users/form', [
                    'title'  => 'Edit User',
                    'user'   => array_merge($user, $data),
                    'errors' => ['password' => 'Password must contain at least 6 characters.'],
                ]);
            }

            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $avatar = $this->request->getFile('avatar');

        if ($avatar && $avatar->getError() !== UPLOAD_ERR_NO_FILE) {
            if (! $avatar->isValid()) {
                return view('users/form', [
                    'title'  => 'Edit User',
                    'user'   => array_merge($user, $data),
                    'errors' => ['avatar' => 'The uploaded file is invalid.'],
                ]);
            }

            if ($avatar->getSize() > 2 * 1024 * 1024) {
                return view('users/form', [
                    'title'  => 'Edit User',
                    'user'   => array_merge($user, $data),
                    'errors' => ['avatar' => 'The image must not exceed 2MB.'],
                ]);
            }

            $extension = strtolower($avatar->getClientExtension());

            if (! in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
                return view('users/form', [
                    'title'  => 'Edit User',
                    'user'   => array_merge($user, $data),
                    'errors' => ['avatar' => 'Only JPG and PNG images are allowed.'],
                ]);
            }

            $uploadPath = FCPATH . 'uploads/avatars/';

            if (! is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $filename = $avatar->getRandomName();
            $destination = $uploadPath . $filename;

            service('image')
                ->withFile($avatar->getTempName())
                ->reorient()
                ->fit(300, 300)
                ->save($destination);

            $data['avatar'] = $filename;

            if (! empty($user['avatar'])) {
                $oldAvatar = $uploadPath . $user['avatar'];

                if (is_file($oldAvatar)) {
                    unlink($oldAvatar);
                }
            }
        }

        $this->userModel->update($id, $data);

        return redirect()
            ->to('/users')
            ->with('success', 'User updated successfully.');
    }
}