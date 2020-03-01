<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PenelitianResource extends JsonResource
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
            'data' => parent::toArray($request),
            'files' => FileModelResource::collection($this->files),
            'mod' => [
                'anggaran' => rupiah($this->penelitian_anggaran),
                'tanggal' => tanggal_indo($this->penelitian_tanggal, true, false),
            ],
            'links' => generate_links('penelitian', $this->penelitian_id)
        ];
    }
}
