<?php

namespace App\Controllers;

use App\Core\BaseController;

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