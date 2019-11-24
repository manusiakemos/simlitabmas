<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = "setting";
    protected $primaryKey = "setting_id";

    public function scopeSortBySettingGroup($query, $value)
    {
        return $query->where('setting_group', $value);
    }

}
