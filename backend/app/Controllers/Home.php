<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $cards = [
            [
                'image' => 'camp1.jpg',
                'title' => 'Forest Retreat',
                'text'  => 'Experience the tranquility of the woods.',
            ],
            [
                'image' => 'camp2.jpg',
                'title' => 'Lakeside Escape',
                'text'  => 'Wake up to the sound of gentle waves.',
            ],
            [
                'image' => 'camp3.jpg',
                'title' => 'Mountain Base',
                'text'  => 'Adventure at the foot of the mountains.',
            ],
        ];
        return view('index', ['cards' => $cards]);
    }

    public function handlePost()
    {
        // Handle the POST request here
        $data = $this->request->getPost(); // Retrieve POST data
        return redirect()->to('/pages/home')->with('success', 'Post request handled successfully!');
    }
}
