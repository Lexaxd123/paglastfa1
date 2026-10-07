<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Wraps the `customers` table.
 * Used by the Customers controller to read customer records.
 */
class CustomerModel extends Model
{
    protected $table      = 'customers';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    // Only these columns may be written through insert()/update()
    // (prevents mass-assignment of id or other unexpected fields).
    protected $allowedFields = ['full_name', 'email', 'phone'];

    // The schema has created_at but no updated_at column.
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
