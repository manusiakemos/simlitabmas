<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenelitianAnggota extends Model
{
    protected $table = "penelitian_anggota";
    protected $primaryKey = "pa_id";

    public function anggota()
    {
        return $this->belongsTo(Anggota::class,'anggota_id', 'id');
    }

    public function penelitian()
    {
        return $this->belongsTo(Penelitian::class,'penelitian_id', 'penelitian_id');
    }
}
