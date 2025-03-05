<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash; // Tambahkan untuk Hash::make()

use function Laravel\Prompts\password;

class UserController extends Controller
{
    public function index()
    {
        $user = UserModel::firstOrNew(
            [
                'username' => 'manager44', 
                'nama' => 'Manager Empat Empat',
                'password' => Hash::make('12345'),
                'level_id' => 2

            ]
        );
        $user->save();
        return view('user', ['data' => $user]);
    }
}
