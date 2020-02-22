<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anggota extends Model
{
    use SoftDeletes;

    protected $table = "anggota";
    protected $primaryKey = "id";

    protected $hidden = [
        'password', 'remember_token',
    ];
}
