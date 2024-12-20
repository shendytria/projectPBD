<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuUserModel extends Model
{
    use HasFactory;

    protected $table = 'setting_menu_user';

    protected $guarded = [];

    public function menu()
    {
        return $this->belongsTo(MenuModel::class, 'menu_id');
    }

    public function jenisUser()
    {
        return $this->belongsTo(JenisUserModel::class, 'jenisUser_id');
    }
}
