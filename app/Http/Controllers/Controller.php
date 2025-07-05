<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

abstract class Controller
{
    //
}

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::all();
        return response()->json([
            'message' => 'Welcome to Admin Dashboard',
            'users' => $users
        ]);
    }
}
