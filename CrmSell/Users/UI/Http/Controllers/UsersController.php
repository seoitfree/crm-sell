<?php

namespace CrmSell\Users\UI\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UsersController
{
    private function logUsers()
    {
        Auth::guard('web')->logoutOtherDevices('');
    }
}
