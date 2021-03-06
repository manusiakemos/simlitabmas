<?php

namespace App\Models;

use App\Scopes\PenelitianScope;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penelitian extends Model
{
    use SoftDeletes;
    protected $table = "penelitian";
    protected $primaryKey = "penelitian_id";

   /* protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::addGlobalScope(new PenelitianScope());
    }*/

    public function status()
    {
        return $this->belongsTo(SurveyStatus::class,'ss_id', 'ss_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function penelitian_anggota()
    {
        return $this->hasMany(PenelitianAnggota::class,'penelitian_id');
    }

    public function files()
    {
        return $this->hasMany(FileModel::class, $this->primaryKey);
    }
}
