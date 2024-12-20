<?php

namespace App\Http\Controllers;

use App\Models\JenisUserModel;
use Illuminate\Http\Request;

class JenisUserController extends Controller
{
     public function create()
     {
          return view('it.role.create');
     }
     public function store(Request $request)
     {

          JenisUserModel::create([
               'jenis_user' => $request->jenis_user
          ]);

          return redirect()->route('admin.user.index')->with('success', 'data berhsail ditambahkan');

     }
     public function edit($id)
     {

          $data = JenisUserModel::find($id);
          return view('it.role.update', compact('data'));

     }
     public function update(Request $request, $id)
     {

          $data = JenisUserModel::find($id);
          $data->jenis_user = $request->jenis_user;

          return redirect()->route('admin.user.index')->with('success', 'Data berhasil di update');

     }
     public function delete($id)
     {

          $data = JenisUserModel::find($id);
          $data->delete();

          return redirect()->back()->with('success', 'data berhsail dihapuswe');

     }
}
