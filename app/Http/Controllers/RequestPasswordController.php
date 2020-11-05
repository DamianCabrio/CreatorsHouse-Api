<?php

namespace App\Http\Controllers;

use App\Traits\SendsPasswordResetEmails;

class RequestPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->broker = 'users';
    }
}
