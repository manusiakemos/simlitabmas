<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SurveyScope implements Scope {

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  Builder $builder
     * @param  Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // TODO: Implement apply() method.
        if(auth()->check() && auth()->user()->role == 'user'){
            $builder->where('user_id', auth()->id());
        }
    }
}
