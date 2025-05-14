<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\Hash;

class Auth extends Controller 
{
        public function __construct()
        {
            helper(['url','form']);
        }

        public function index()
        {
            return view('auth/login');
        }

        public function base()
        {
            return view('index');
        }

        public function register()
        {
                return view('auth/register');
        }

        public function save()
        {
            // Let's validate requests
            // $validation = $this->validate([
            //     'name' => 'required',
            //     'email' => 'required|valid_email|is_unique[users.email]',
            //     'password' => 'required|min_length[8]|max_length[255]',
            //     'cpassword' => 'required|min_length[8]|max_length[255]|matches[password]'
            // ]);

            $validation = $this->validate([
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Your full name is required'
                    ]
                ],
                'email' => [
                    'rules' => 'required|valid_email|is_unique[customer.email]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'You must enter a valid email',
                        'is_unique' => 'Email already taken'
                    ]
                ],
                'no_phone' => [
                    'rules' => 'required|numeric|min_length[10]|max_length[15]',
                    'errors' => [
                        'required' => 'Phone number is required',
                        'numeric' => 'Phone number must be numeric',
                        'min_length' => 'Phone number must have at least 10 digits',
                        'max_length' => 'Phone number must have at most 15 digits'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[8]|max_length[255]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must have at least 8 characters'
                    ]
                ],
                'cpassword' => [
                    'rules' => 'required|min_length[8]|max_length[255]|matches[password]',
                    'errors' => [
                        'required' => 'Confirm Password is required',
                        'min_length' => 'Confirm Password must have at least 8 characters',
                        'matches' => 'Password does not match'
                    ]
                ]
            ]);


            if(!$validation)
            {
                return view('auth/register', [
                    'validation' => $this->validator
                ]);
            }
            else
            {
                // Let's save the data
                $name = $this->request->getPost('name');
                $email = $this->request->getPost('email');
                $no_phone = $this->request->getPost('no_phone');
                $password = $this->request->getPost('password');

                $values = [
                    'name' => $name,
                    'email' => $email,
                    'no_phone' => $no_phone,
                    'password' => Hash::make($password),
                ];

                $userModel = new \App\Models\UsersModel();
                $query = $userModel->insert($values);
                if(!$query)
                {
                    return redirect()->back()->with('fail', 'Somethine went wrong');
                }
                else
                {
                    // return redirect()->to('/auth/register')->with('success', 'You have successfully registered');

                    $last_id = $userModel->insertID();//This is the last inserted id
                    session()->set('loggedUser', $user_id);
                    return redirect()->to('/');
                }



            }

        }

        function check()
        {
            // Let's start validation
            $validation = $this->validate([
                'email' => [
                    'rules' => 'required|valid_email|is_not_unique[customer.email]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'You must enter a valid email',
                        'is_not_unique' => 'Email does not exist'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[8]|max_length[255]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must have at least 8 characters'
                    ]
                ]
            ]);

            if(!$validation)
            {
                return view('auth/login', [
                    'validation' => $this->validator
                ]);
            }
            else
            {
                // Let's check the user
                // $session = session();
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                $usersModel = new \App\Models\UsersModel();
                $user_info = $usersModel->where('email', $email)->first();
                $check_password = Hash::check($password, $user_info['password']);

                if(!$check_password)
                {
                    session()->setFlashdata('fail', 'Incorrect password');
                    return redirect()->to('/auth')->withInput();
                }
                else
                {
                    $user_id = $user_info['id'];
                    session()->set('loggedUser', $user_id);
                    return redirect()->to('/pages/home');
                }
               
            }
        }

        function logout()
        {
            if(session()->has('loggedUser'))
            {
                session()->remove('loggedUser');
                return redirect()->to('/auth?access=out')->with('fail', 'You are logged out');
            }
        }
}