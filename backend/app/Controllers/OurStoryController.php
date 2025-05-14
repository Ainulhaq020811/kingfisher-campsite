<?php

namespace App\Controllers;

class OurStoryController extends BaseController
{
    public function index()
    {
        return view('our_story/index');
    }

    public function milestones()
    {
        return view('our_story/milestones');
    }

    public function gallery()
    {
        return view('our_story/gallery');
    }

    public function faq()
    {
        return view('our_story/faq');
    }
}

