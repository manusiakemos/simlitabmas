<?php

namespace App\Http\Controllers;

use App\Http\Resources\BeritaKategoriResource;
use App\Models\BeritaKategori;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BeritaKategoriController extends Controller
{
    public function index()
    {
        return DataTables::of(BeritaKategori::query())
            ->addColumn('action', function (BeritaKategori $value) {
                return view('components.action',[
                    'name' => 'bk',
                    'value' => $value
                ]);
            })
            ->toJson();
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'bk_nama' => 'required'
        ]);

        $db = new BeritaKategori;
        $db->bk_nama = $request->bk_nama;
        $db->save();
        return responseJson('Kategori berita berhasil ditambahkan');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        return new BeritaKategoriResource(BeritaKategori::find($id));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'bk_nama' => 'required'
        ]);

        $db = BeritaKategori::find($id);
        $db->bk_nama = $request->bk_nama;
        $db->save();
        return responseJson('Kategori berita berhasil diupdate');
    }

    public function destroy($id)
    {
        $db = BeritaKategori::find($id);
        $db->delete();
        $db->save();
        return responseJson('Kategori berita berhasil dihapus');
    }
}
