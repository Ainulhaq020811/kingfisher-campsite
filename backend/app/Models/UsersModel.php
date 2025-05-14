<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'no_phone', 'delete_at', 'created_at', 'updated_at'];

    public $tables = [
        'customer' => 'customers',
        // ...other tables
    ];

    private $conn;

    // Constructor to initialize the database connection
    // public function __construct($conn) {
    //     $this->conn = $conn;
    // }

    // Function to delete a user by ID
     public function deleteUser($id)
    {
        $this->where('id', $id)->delete();
    }

    // Reorder the IDs in the 'users' table
    public function reorderIds()
    {
        $customers = $this->orderBy('id', 'ASC')->findAll();

        // Reorder IDs sequentially
        $newId = 1;
        foreach ($customers as $customer) {
            $this->update($customer['id'], ['id' => $newId]);
            $newId++;
        }
    }

}


?>