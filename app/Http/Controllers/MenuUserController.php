<?php

namespace App\Http\Controllers;

use App\Models\JenisUserModel;
use App\Models\MenuModel;
use App\Models\MenuUserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuUserController extends Controller
{
    public function index()
    {

        $ppp = MenuUserModel::with('menu', 'jenisUser')->get();

        $groupedMenus = $ppp->groupBy('jenisUser.jenis_user');
        $allMenu = MenuModel::all();
        $jenis_user = JenisUserModel::all();

        return view('it.menu.index', compact('groupedMenus', 'groupedMenus', 'allMenu', 'jenis_user'));
    }

    // public function showLink($menu_name){

    //     $halaman = Menu::user

    // }

    public function store(Request $request)
    {

        $data = [
            'jenisUser_id' => $request->jenisUser_id,
            'menu_id' => $request->menu_id,
        ];

        MenuUserModel::create($data);

        return redirect()->back()->with('success', 'Berhasil Ditambahkan');

    }
    public function update(Request $request, $id)
    {
        $menu = MenuModel::find($id);
        $menu->menu_name = $request->menu_name;
        $menu->menu_link = $request->menu_link;
        $menu->menu_icon = $request->menu_icon;
        $menu->save();

        return response()->json(['status' => 'success']);
    }

    public function delete($id)
    {
        $data = MenuUserModel::find($id);
        $data->delete();

        return redirect()->back('success', 'Berhasil dihapus');
    }
}
