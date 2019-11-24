<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
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
            'mod' => [
                'url' => asset('/uploads/sliders/'.$this->slide_gambar),
                'name' => $this->slide_gambar,
            ],
            'links' => [
                'store' => route('slider.store'),
                'edit' => route('slider.edit', $this->slide_id),
                'show' => route('slider.show', $this->slide_id),
                'update' => route('slider.update', $this->slide_id),
                'upload' => route('slider.upload', $this->slide_id),
                'destroy' => route('slider.destroy', $this->slide_id),
            ]
        ];
    }
}
