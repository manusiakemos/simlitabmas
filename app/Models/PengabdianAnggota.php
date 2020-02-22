<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengabdianAnggota extends Model
{
    protected $table = "penelitian_anggota";
    protected $primaryKey = "pa_id";

    protected $fillable = ['anggota_id', 'penelitian_id', 'pengabdian_id', 'created_at', 'updated_at'];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class,'anggota_id', 'id');
    }

    public function penelitian()
    {
        return $this->belongsTo(Penelitian::class,'penelitian_id', 'penelitian_id');
    }
}
