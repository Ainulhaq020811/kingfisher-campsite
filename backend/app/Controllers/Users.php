<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UsersModel;

class Users extends BaseController
{
    private $db;

    public function __construct()
    {
        // Connect to the database
        $this->db = \Config\Database::connect();
    }

    /**
     * Function to delete a user and reorder IDs
     *
     * @param int $id
     * @return ResponseInterface
     */
    public function delete_user($id)
    {
        $userModel = new UsersModel();

        // Delete the user
        if ($userModel->delete($id)) {
            // Reorder IDs after deletion
            $db = \Config\Database::connect();
            $db->query('CALL reorder_ids()');
        }

        return redirect()->to('/dashboard/read_users')->with('message', 'User deleted and IDs reordered successfully');
    }

    /**
     * Display the list of users
     */
    public function index()
    {
        $builder = $this->db->table('customer')->where('deleted_at', null)->get()->getResultArray();
        $data['customer'] = $builder;
        return view('dashboard/read_users', $data);
    }

    /**
     * Display the form to create a new user
     */
    public function new()
    {
        return view('dashboard/new_user');
    }

    /**
     * Create a new user
     */
    public function create()
    {
        $userModel = new UsersModel();
        $data = [
            'name' => $this->request->getVar('name'),
            'email' => $this->request->getVar('email'),
            'no_phone' => $this->request->getVar('phone'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            // 'age' => $this->request->getVar('age')
        ];
        $userModel->insert($data);
        return redirect()->to('/dashboard/read_users')->with('success', 'User created successfully.');
    }

    /**
     * Update user information
     */
    public function update($id = null)
    {
        $userModel = new UsersModel();
        $data = [
            'name' => $this->request->getVar('name'),
            'email' => $this->request->getVar('email'),
            'no_phone' => $this->request->getVar('phone'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
        ];
        $userModel->update($id, $data);
        return redirect()->to('/dashboard/read_users')->with('success', 'User updated successfully.');
    }

    /**
     * Display the update form for a specific user
     */
    public function update_form($id = null)
    {
        $userModel = new UsersModel();
        $data['customer'] = $userModel->find($id);
        return view('dashboard/update_form', $data);
    }

    /**
     * Go back to the previous page
     */
    public function goBack()
    {
        echo "<script>window.history.back();</script>";
    }
}
