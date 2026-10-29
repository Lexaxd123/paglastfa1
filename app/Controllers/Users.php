<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Images\Exceptions\ImageException;

class Users extends BaseController
{
    /** Public folder that holds the prepared avatars (served at /uploads/avatars/...). */
    private const AVATAR_DIR = FCPATH . 'uploads/avatars/';

    /** Size of the prepared, display-ready avatar (square, in pixels). */
    private const AVATAR_SIZE = 200;

    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    /**
     * GET /users
     */
    public function index()
    {
        return view('users/index', [
            'users' => $this->userModel->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    /**
     * GET /users/new
     */
    public function new()
    {
        return view('users/form', [
            'title'  => 'Add New User',
            'user'   => [],
            'errors' => [],
        ]);
    }

    /**
     * POST /users/create
     */
    public function create()
    {
        $data = $this->postedUser();

        // TFA4: a new account needs a password, otherwise nobody could log in as it.
        if (! $this->validateData($data + $this->postedPasswords(), $this->rules(null, true))) {
            return view('users/form', [
                'title'  => 'Add New User',
                'user'   => $data, // passwords are never sent back to the form
                'errors' => $this->validator->getErrors(),
            ]);
        }

        // Only the hash is stored.
        $data['password'] = password_hash($this->postedPasswords()['password'], PASSWORD_DEFAULT);

        $this->userModel->insert($data);

        return redirect()->to('/users')
            ->with('success', 'User "' . $data['username'] . '" was added.');
    }

    /**
     * GET /users/edit/{id}
     */
    public function edit(int $id)
    {
        return view('users/form', [
            'title'  => 'Edit User',
            'user'   => $this->findOr404($id),
            'errors' => [],
        ]);
    }

    /**
     * POST /users/update/{id}
     */
    public function update(int $id)
    {
        $user = $this->findOr404($id);
        $data = $this->postedUser();

        // Text fields: is_unique ignores this user's own row.
        // TFA4: the password is optional on edit (empty = keep the current one).
        $passwords = $this->postedPasswords();
        $changePwd = $passwords['password'] !== '';
        $rules     = $this->rules($id, $changePwd);

        // Avatar is optional: only validate it if a file was actually chosen.
        $avatar = $this->request->getFile('avatar');

        if ($avatar !== null && $avatar->getError() !== UPLOAD_ERR_NO_FILE) {
            $rules['avatar'] = [
                'label'  => 'Profile Picture',
                'rules'  => 'uploaded[avatar]'
                    . '|is_image[avatar]'
                    . '|mime_in[avatar,image/jpeg,image/png]'
                    . '|ext_in[avatar,jpg,jpeg,png]'
                    . '|max_size[avatar,2048]',
                'errors' => [
                    'uploaded' => 'The {field} could not be uploaded. Make sure it is a JPG or PNG of 2MB or smaller.',
                    'is_image' => 'The {field} must be a real JPG or PNG image.',
                    'mime_in'  => 'The {field} must be a JPG or PNG image.',
                    'ext_in'   => 'The {field} must have a .jpg, .jpeg, or .png extension.',
                    'max_size' => 'The {field} must be 2MB or smaller.',
                ],
            ];
        }

        if (! $this->validateData($data + $passwords, $rules)) {
            return view('users/form', [
                // Keep the current avatar visible while showing the typed values.
                'title'  => 'Edit User',
                'user'   => ['id' => $id, 'avatar' => $user['avatar']] + $data,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        if (isset($rules['avatar'])) {
            try {
                $data['avatar'] = $this->prepareAvatar($avatar);
            } catch (ImageException $e) {
                log_message('error', 'Avatar processing failed: ' . $e->getMessage());

                return view('users/form', [
                    'title'  => 'Edit User',
                    'user'   => ['id' => $id, 'avatar' => $user['avatar']] + $data,
                    'errors' => ['avatar' => 'The image could not be processed. Please try a different file.'],
                ]);
            }

            $this->deleteAvatarFile($user['avatar']);
        }

        if ($changePwd) {
            $data['password'] = password_hash($passwords['password'], PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/users')
            ->with('success', 'User "' . $data['username'] . '" was updated.');
    }

    /**
     * Validation rules for the text fields.
     * On edit, pass the user's id so is_unique skips their own row.
     */
    private function rules(?int $ignoreId = null, bool $withPassword = false): array
    {
        $unique = $ignoreId === null
            ? 'is_unique[users.username]'
            : 'is_unique[users.username,id,' . $ignoreId . ']';

        $rules = [
            'username' => [
                'label'  => 'Username',
                'rules'  => 'required|min_length[3]|max_length[50]|regex_match[/^[A-Za-z0-9._-]+$/]|' . $unique,
                'errors' => [
                    'regex_match' => 'The {field} may only contain letters, numbers, dots, dashes, and underscores.',
                    'is_unique'   => 'That {field} is already taken. Please choose another one.',
                ],
            ],
            'full_name' => [
                'label' => 'Full Name',
                'rules' => 'required|min_length[2]|max_length[100]',
            ],
        ];

        if ($withPassword) {
            // 72 = bcrypt only uses the first 72 bytes, so longer input would be silently cut.
            $rules['password'] = [
                'label'  => 'Password',
                'rules'  => 'required|min_length[8]|max_length[72]',
                'errors' => ['min_length' => 'The {field} must be at least 8 characters long.'],
            ];
            $rules['password_confirm'] = [
                'label'  => 'Confirm Password',
                'rules'  => 'required|matches[password]',
                'errors' => ['matches' => 'The two passwords do not match.'],
            ];
        }

        return $rules;
    }

    /**
     * TFA4: the raw password fields. Kept apart from postedUser() so they can
     * never end up in the array that is saved or shown again in the form.
     */
    private function postedPasswords(): array
    {
        return [
            'password'         => (string) $this->request->getPost('password'),
            'password_confirm' => (string) $this->request->getPost('password_confirm'),
        ];
    }

    /**
     * Reads only the expected text fields from the submitted form.
     */
    private function postedUser(): array
    {
        return [
            'username'  => trim((string) $this->request->getPost('username')),
            'full_name' => trim((string) $this->request->getPost('full_name')),
        ];
    }

    /**
     * Turns the uploaded file into a display-ready square thumbnail and
     * returns ONLY its new filename (that is what gets saved in the database).
     *
     * The original upload is never stored. The Image service re-draws the
     * picture into a brand-new file, which also strips hidden metadata
     * (such as phone GPS data) and anything that is not actual image data.
     */
    private function prepareAvatar(UploadedFile $file): string
    {
        if (! is_dir(self::AVATAR_DIR)) {
            mkdir(self::AVATAR_DIR, 0755, true);
        }

        // Random name + extension guessed from the real file content
        // (not the name the user gave it).
        $extension = $file->guessExtension() === 'png' ? 'png' : 'jpg';
        $filename  = bin2hex(random_bytes(16)) . '.' . $extension;

        service('image')
            ->withFile($file->getTempName())
            ->reorient(true)
            ->fit(self::AVATAR_SIZE, self::AVATAR_SIZE, 'center')
            ->save(self::AVATAR_DIR . $filename, 85);

        return $filename;
    }

    /**
     * Removes a previously prepared avatar when it is replaced.
     */
    private function deleteAvatarFile(?string $filename): void
    {
        if ($filename === null || $filename === '') {
            return;
        }

        // basename() makes sure we can only ever delete inside AVATAR_DIR.
        $path = self::AVATAR_DIR . basename($filename);

        if (is_file($path)) {
            unlink($path);
        }
    }

    private function findOr404(int $id): array
    {
        $user = $this->userModel->find($id);

        if ($user === null) {
            throw PageNotFoundException::forPageNotFound('User not found.');
        }

        return $user;
    }
}
