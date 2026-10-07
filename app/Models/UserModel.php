<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Wraps the `users` table.
 * Used by the Users controller to read user account records.
 */
class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['username', 'full_name'];

    // The schema has created_at but no updated_at column.
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
