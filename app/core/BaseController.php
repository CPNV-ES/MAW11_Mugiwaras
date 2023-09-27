<?php

namespace App\Core;

class BaseController
{
    public function view($view)
    {
        $view = str_replace('.', '/', $view);
        require_once "../app/views/$view.php";
    }
}
