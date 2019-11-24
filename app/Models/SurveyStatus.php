<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyStatus extends Model
{
    protected $primaryKey = "ss_id";

    protected $table = "status";

    public function scopeFindByLevel($query,$nextLevel)
    {
        return $query->where('ss_level', $nextLevel);
    }
}
