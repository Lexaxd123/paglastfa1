<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Wraps the `customers` table.
 * Used by the Customers controller to list, create, and update customers.
 */
class CustomerModel extends Model
{
    protected $table      = 'customers';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    // Only these columns may be written through insert()/update()
    // (blocks mass-assignment of id or any other unexpected field).
    protected $allowedFields = ['full_name', 'email', 'phone'];

    // created_at is filled automatically; the schema has no updated_at.
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
