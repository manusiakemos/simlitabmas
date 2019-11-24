<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => parent::toArray($request),
            'mod' => '',
            'links' => [
                'store' => route('setting.store'),
                'edit' => route('setting.edit', $this->setting_id),
                'show' => route('setting.show', $this->setting_id),
                'update' => route('setting.update', $this->setting_id),
                'destroy' => route('setting.destroy', $this->setting_id),
            ]
        ];
    }
}
