<?php
/**
 * Created by PhpStorm.
 * User: manusiakemos
 * Date: 16/11/19
 * Time: 16.29
 */

namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PenelitianScope implements Scope
{


    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // TODO: Implement apply() method.
        $builder->where('is_pengabdian', 0);
        if(auth()->user()->role == 'user'){
            $builder->where('user_id', auth()->id());
        }
    }
}