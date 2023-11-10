<?php

namespace App\Controllers;

use App\Core\BaseController;

class Controller extends BaseController
{
    public function index($msg = [])
    {
        echo "$msg from Controller";
        // $this->view('home');
    }
}
