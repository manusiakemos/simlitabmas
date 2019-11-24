<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parameters extends Model
{
    protected $table = "parameters";

    protected $primaryKey = "id";
    //
    public function scopeSortByType($query,$string)
    {
        return $query->where('type', $string);
    }
}
