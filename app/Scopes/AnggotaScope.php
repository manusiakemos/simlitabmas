<?php

namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AnggotaScope implements Scope
{

    public $is_anggota;

    public function __construct($is_anggota)
    {
        $this->is_anggota = $is_anggota;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if(auth()->check()){
            $builder->where('is_anggota', $this->is_anggota);
        }
    }
}