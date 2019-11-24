<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class BeritaResource extends JsonResource
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
            'mod' => [
                'image_src' => asset('uploads/berita/'.$this->berita_gambar),
                'url_berita' => route('berita.detail', $this->berita_url),
                'short_text' => Str::limit($this->berita_isi,135),
                'created_at' => tanggal_indo($this->created_at, true, false)
            ],
            'links' => generate_links('berita', $this->berita_id)
        ];
    }
}
