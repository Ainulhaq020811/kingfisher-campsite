<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Booking extends BaseController
{
    public function index()
    {
        $isLoggedIn = session()->get('isLoggedIn') ?? false;
        return view('book_online', ['isLoggedIn' => $isLoggedIn]);
    }

    public function submit()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        // Validate and process booking here
        // Example: $this->request->getPost('checkin'), etc.

        // Save booking logic...

        return $this->response->setJSON(['success' => true]);
    }
}