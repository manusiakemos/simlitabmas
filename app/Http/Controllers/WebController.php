<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index()
    {
        return view('web.index',[
            'tentang' => Settings::where('setting_name', 'tentang')->first(),
            'features' => Settings::sortBySettingGroup('features')->get()
        ]);
    }

    public function admin()
    {
        return view('layouts.admin');
    }

    public function halaman($halaman_url)
    {
        return view('web.halaman');
    }
}
