<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileModel extends Model
{
    protected $primaryKey = "file_id";
    protected $table = "files";

    public function scopefindByPenelitianId($query, $id)
    {
        return $query->where('penelitian_id', $id);
    }

    public function scopefindByPengabdianId($query, $id)
    {
        return $query->where('penelitian_id', $id);
    }


    use SoftDeletes;
}
