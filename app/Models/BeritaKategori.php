<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BeritaKategori extends Model
{
    use SoftDeletes;
    protected $table = "berita_kategori";
    protected $primaryKey = "bk_id";
}
