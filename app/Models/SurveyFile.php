<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyFile extends Model
{
    //
    protected $table = "survey_files";
    protected $primaryKey = "file_id";

    public function scopeFindBySurveyId($query,$id)
    {
        return $query->where('survey_id', $id);
    }

    public function scopeFindBySurveyIdAndVariant($query, $variant, $id)
    {
        if($variant == 'excel'){
            $ext = [
                'xlsx', 'xls', 'csv'
            ];
        }else{
            $ext = [
                'pdf'
            ];
        }
        return $query
            ->whereIn('file_ext',$ext)
            ->where('survey_id', $id);
    }
}
