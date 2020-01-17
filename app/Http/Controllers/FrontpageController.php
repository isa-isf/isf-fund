<?php

namespace App\Http\Controllers;

class FrontpageController
{
    public function __invoke()
    {
        return view('saisenbako');
    }
}
