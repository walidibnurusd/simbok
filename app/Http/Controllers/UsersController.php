<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profession;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    
    public function index()
    {
        $users = User::all();
        return view('content.users.index', ['users' => $users]);
    }
}
