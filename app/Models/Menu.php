<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "menu";
    protected $primaryKey = "id";

    public function scopeOrderWithChildren($query)
    {
        return $query
//            ->select(['menu.*', 'hal_url'])
            ->where('parent_id', null)
            ->orderBy('order', 'asc')
            ->orderBy('menu.updated_at', 'desc')
            ->with('children');
    }

    public function child()
    {
        return $this
            ->hasMany(self::class, 'parent_id', 'id')
            ->leftJoin('halaman', 'halaman.hal_id', '=', 'menu.hal_id');
    }

    public function children()
    {
        return $this->child()->with('children');
    }
}
