<?php

namespace Mugiwaras\Framework\Controllers;

use Mugiwaras\Framework\Core\BaseController;

class Controller extends BaseController
{
    public function index($msg = [])
    {
        print_r($msg);
        echo "from Controller";
        // $this->view('home');
    }
}
