<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $name = 'file';
        $id = $this->file_id;
        return [
            'data' => parent::toArray($request),
            'mod' => [
                'asset' => asset('uploads/surveys/'.$this->file_name),
                'is_edit' => false
            ],
            'links' => [
                'store' => route($name.".store"),
                'edit' => route($name.'.edit', $id),
                'update' => route($name.'.update', $id),
                'destroy' => route($name.'.destroy', $id),
                'download' => url("api/file-download/{$id}?api_token=".auth()->user()->api_token),
            ]
        ];
    }
}
