<?php

namespace App\Controllers;

class Tool extends BaseController
{
    public function index()
    {
        return view('tool/index');
    }

    public function token_count()
    {
        return view('tool/token_count');
    }

    public function token_search()
    {
        return view('tool/token_search');
    }
}
