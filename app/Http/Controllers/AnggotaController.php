<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnggotaResource;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class AnggotaController extends Controller
{

    public function index()
    {
        return DataTables::of(Anggota::query())
            ->addColumn('action', function ($value){
                return view('components.action',[
                    'name' => 'anggota',
                    'value' => $value
                ]);
            })
            ->rawColumns(['action', 'alamat'])
            ->toJson();
    }

    public function store(Request $request)
    {
        $this->validate($request,[
           'name' => 'required',
           'phone' => 'required',
           'alamat' => 'required',
        ]);
        $db = new Anggota;
        $db->is_anggota = 1;
        $db->name = $request->name;
        $db->phone = $request->phone;
        $db->alamat = $request->alamat;
        $db->username = Str::snake($request->name);
        $db->api_token = Str::random().'anggota';
        return $db->save()
            ? responseJson('Anggota berhasil ditambahkan')
            : responseJson('Anggota gagal ditambahkan', '', 500);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        return new AnggotaResource(Anggota::find($id));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required',
            'phone' => 'required',
            'alamat' => 'required',
        ]);
        $db = Anggota::find($id);
        $db->name = $request->name;
        $db->phone = $request->phone;
        $db->alamat = $request->alamat;
        $db->is_anggota = 1;
        return $db->save()
            ? responseJson('Anggota berhasil diupdate')
            : responseJson('Anggota gagal diupdate', '', 500);
    }

    public function destroy($id)
    {
        $db = Anggota::find($id)->delete();
        return $db
            ? responseJson('Anggota berhasil dihapus')
            : responseJson('Anggota gagal dihapus', '', 500);
    }
}
