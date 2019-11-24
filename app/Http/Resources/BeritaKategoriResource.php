<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BeritaKategoriResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' =>  parent::toArray($request),
            'mod' => [],
            'links' => generate_links('beritakategori', $this->bk_id)
        ];
    }
}
