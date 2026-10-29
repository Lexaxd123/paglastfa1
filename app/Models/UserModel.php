<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Wraps the `users` table.
 * Used by the Users controller to list, create, and update user accounts.
 */
class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    // avatar stores only the FILENAME of the prepared image, never the image.
    // password stores only a password_hash() value, never the real password.
    protected $allowedFields = ['username', 'full_name', 'avatar', 'password'];

    // created_at is filled automatically; the schema has no updated_at.
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
