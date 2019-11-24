<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Berita extends Model
{
    use SoftDeletes;
    protected $table = "berita";
    protected $primaryKey = "berita_id";

    public function kategori()
    {
        return $this->belongsTo(BeritaKategori::class, 'bk_id', 'bk_id')->withTrashed();
    }

    public function getBeritaAktifAttribute($value)
    {
        return $value == 1 ? true : false;
    }

    public function getCustomUrlAttribute($value)
    {
        return $value == 1 ? true : false;
    }
}
