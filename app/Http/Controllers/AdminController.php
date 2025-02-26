<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard'); // Precisa de um arquivo "admin/dashboard.blade.php"
    }

    public function listUsers()
    {
        $users = User::where('role', '!=', 'admin')->get(); // Lista apenas usuários comuns
        return view('admin.users', compact('users'));
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->back()->with('message', 'Usuário excluído com sucesso!');
    }

}
