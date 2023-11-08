<?php

namespace App;

use App\BaseController;

class Controller extends BaseController
{
    public function index($msg = [])
    {
        echo "$msg from Controller";
        // $this->view('home');
    }
}