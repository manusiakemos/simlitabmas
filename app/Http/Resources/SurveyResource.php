<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $name = 'survey';
        $id = $this->survey_id;
        return [
            'data' => parent::toArray($request),
            'mod' => [],
            'links' => [
                'store' => route($name.".store"),
                'edit' => route($name.'.edit', $id),
                'show' => route($name.'.show', $id),
                'update' => route($name.'.update', $id),
                'destroy' => route($name.'.destroy', $id),
                'upload' => route($name.'.upload', $id),
            ]
        ];
    }
}
