<?php

namespace App\Controllers;

use App\Core\BaseController;

class Controller extends BaseController
{
    public function index($msg = [])
    {
        print_r($msg);
        echo "from Controller";
        // $this->view('home');
    }
}
