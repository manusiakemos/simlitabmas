<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\BeritaKategori;
use App\Models\Golongan;
use App\Models\Halaman;
use App\Models\Jabatan;
use App\Models\Parameters;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SelectOptionsController extends Controller
{
    public function index(Request $request, $type)
    {
        switch ($type) {
            case 'anggota':
                return Anggota::all();
               /* if($request->has('anggota') && count($request->anggota) > 0){
                    $except = [];
                    foreach($request->anggota as $value){
                        if($value){
                            $except[] = $value;
                        }
                    }
                    return Anggota::whereNotIn('id', $except)->get();
                }*/
                break;
            case 'group':
                return Parameters::sortByType('group')->get();
                break;
            case 'input':
                return Parameters::sortByType('input')->get();
                break;
            case 'menu':
                return Parameters::sortByType('menu')->get();
                break;
            case 'halaman':
                return Halaman::all();
                break;
            case 'beritakategori':
                return BeritaKategori::all();
                break;
            case 'golongan':
                return Golongan::all();
                break;
            case 'jabatan':
                return Jabatan::all();
                break;

            default:
                abort('404');
                break;
        }
    }
}
