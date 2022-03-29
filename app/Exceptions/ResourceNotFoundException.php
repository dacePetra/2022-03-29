<?php

namespace App\Controllers;

class ResourceNotFoundException extends \Exception
{

    /**
     * @param string $string
     */
    public function __construct(string $string)
    {
    }
}