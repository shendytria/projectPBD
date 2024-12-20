<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuModel extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'menu';

    public function MenuUser()
    {
        return $this->hasMany(MenuUserModel::class, 'menu_id');
    }
}
