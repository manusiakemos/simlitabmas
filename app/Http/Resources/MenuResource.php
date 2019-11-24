<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $urlTo = $this->type == 'halaman' ? url('halaman/' . $this->hal_url) : url($this->url);
        return [
            'data' => [
                "type" => $this->type,
                "url" => $this->url,
                "hal_url" => $this->hal_url,
                "custom_url" => $this->custom_url,
                "name" => $this->name,
            ],
            'children' => MenuResource::collection($this->children),
            'mod' => [
                'url_to' => $urlTo,
            ],
            'links' => generate_links('menu', $this->id)
        ];
    }
}
