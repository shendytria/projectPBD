<?php

namespace App\Http\Controllers;

use App\Models\JenisUserModel;
use App\Models\MenuUserModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index()
    {

        $data = User::all();
        $role = JenisUserModel::all();

        return view('it.user.index', compact('data', 'role'));
    }

    public function edit($id){

        $data = User::find($id);
        $role = JenisUserModel::all();
        return view('it.user.update', compact('data', 'role'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);


        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->jenisUser_id = $request->role;

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.user.index')->with('success', 'User updated successfully.');
    }

    public function delete($id)
    {

        $data = User::find($id);
        $data->delete();

        return redirect()->route('admin.user.index')->with('success', 'data berhsail ditambahkan');

    }
}
