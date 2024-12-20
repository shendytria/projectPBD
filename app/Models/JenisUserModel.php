<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisUserModel extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'jenis_user';

    public function user(){
        return $this->hasMany(User::class);
    }
}
