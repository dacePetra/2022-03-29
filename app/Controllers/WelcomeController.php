<?php

namespace App\Controllers;

use App\Redirect;
use App\Views\View;

class WelcomeController
{
    public function opening(): View
    {
        return new View('opening');
    }

}