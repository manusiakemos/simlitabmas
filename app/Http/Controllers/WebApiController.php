<?php

namespace App\Http\Controllers;

use App\Http\Resources\BeritaResource;
use App\Http\Resources\MenuResource;
use App\Http\Resources\SliderResource;
use App\Models\Berita;
use App\Models\Menu;
use App\Models\Settings;
use App\Models\Sliders;
use Illuminate\Http\Request;

class WebApiController extends Controller
{
    public function setting($group)
    {
        $data = Settings::sortBySettingGroup($group)->get();
        return $data;
    }

    public function settingName($name)
    {
        return Settings::where('setting_name', $name)->first();
    }

    public function menu()
    {
        $menu = MenuResource::collection(Menu::orderWithChildren()->get());
        return $menu;
    }

    public function slider()
    {
        $menu = SliderResource::collection(Sliders::where('slide_aktif', 1)->get());
        return $menu;
    }

    public function berita()
    {
        $berita = Berita::where('berita_aktif', 1)->paginate();
        return BeritaResource::collection($berita);
    }

    public function terbaru()
    {
        $berita = Berita::with('kategori')->where('berita_aktif', 1)
            ->orderBy('created_at','desc')
            ->take(6)
            ->get();
        return BeritaResource::collection($berita);
    }

    public function terpopuler()
    {
        $berita = Berita::with('kategori')->where('berita_aktif', 1)
            ->orderBy('berita_hit','desc')
            ->take(6)
            ->get();
        return BeritaResource::collection($berita);
    }

}
