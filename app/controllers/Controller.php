<?php

namespace App;

use App\BaseController;

class Controller extends BaseController
{
    public function index()
    {
        echo "Hello from Controller";
        // $this->view('home');
    }

    public function login()
    {
        $this->view('auth.login');
    }
}