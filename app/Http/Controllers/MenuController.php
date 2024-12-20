<?php

namespace App\Http\Controllers;

use App\Models\MenuModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function menuCreate(Request $request)
    {

        $validatedData = $request->validate([
            'menu_name' => 'required|string|max:255',
            'menu_link' => 'required|string|max:255',
            'menu_icon' => 'required|string|max:255',
        ]);

        try {
            // Create a new menu item
            MenuModel::create([
                'menu_name' => $validatedData['menu_name'],
                'menu_link' => $validatedData['menu_link'],
                'menu_icon' => $validatedData['menu_icon'],
            ]);

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Menu added successfully!',
            ]);
        } catch (\Exception $e) {
            // Log the exception message for debugging
            Log::error('Error adding menu: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the menu.',
            ], 500);
        }

    }

    public function menuDelete(Request $request, $id){

        $menu = MenuModel::find($id);
        $menu->delete();
    
        return redirect()->back()->with('success', 'Menu Berhasil dihapus');
    }

    public function showMenu($id){
        
        $data = MenuModel::find($id);
        return view('it.menu.show', compact('data'));

    }
}
