<?php

namespace App\Http\Controllers;

use App\Http\Resources\BeritaResource;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class BeritaController extends Controller
{
    public function upload(Request $request, $id)
    {
        $db = Berita::find($id);
        if ($request->hasFile("berita_gambar")) {
            $file = $request->file('berita_gambar');
            $fileName = Str::random() . "." . $file->getClientOriginalExtension();
            $file->storeAs("/uploads/berita", $fileName);
            $db->berita_gambar = $fileName;
        }
        $db->save();
        return responseJson('Berita berhasil ditambahkan');
    }

    public function index()
    {
        return DataTables::of(Berita::with('kategori'))
            ->addColumn('action', function ($value) {
                return view('components.action', [
                    'name' => 'berita',
                    'value' => $value
                ]);
            })
            ->toJson();
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'berita_judul' => 'required',
            'berita_url' => [
                Rule::unique('berita', 'berita_url')
                    ->whereNull('deleted_at')
            ]
        ]);
        $db = new Berita;
        $db->bk_id = $request->bk_id;
        $db->user_id = auth()->id();
        $db->berita_judul = $request->berita_judul;
        $db->berita_isi = $request->berita_isi;
        $db->berita_aktif = $request->berita_aktif;
        $db->berita_hit = 0;
        $db->berita_url = $request->berita_url;
        if ($request->has("berita_gambar")) {
            $image = $request->input("berita_gambar");
            if(is_array($image)){
                $filename = Str::random() . ".png";
                $file = $image["dataUrl"];

                if ($file) {
                    base64_to_image($file, "/uploads/berita/$filename");
                    $db->berita_gambar = $filename;
                }
            }
        }

        $db->save();
        return responseJson('Berita berhasil ditambahkan');

    }

    public function edit($id)
    {
        return new BeritaResource(Berita::find($id));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'berita_judul' => 'required',
            'berita_url' => [
                Rule::unique('berita', 'berita_url')
                    ->whereNull('deleted_at')
                    ->ignore($id, 'berita_id')
            ]
        ]);
        $db = Berita::find($id);
        $db->bk_id = $request->bk_id;
        $db->user_id = auth()->id();
        $db->berita_judul = $request->berita_judul;
        $db->berita_isi = $request->berita_isi;
        $db->berita_aktif = $request->berita_aktif;
        $db->berita_hit = 0;
        $db->berita_url = $request->berita_url;
        if ($request->has("berita_gambar")) {
            $image = $request->input("berita_gambar");
            if(is_array($image)){
                $filename = Str::random() . ".png";
                $file = $image["dataUrl"];

                if ($file) {
                    base64_to_image($file, "/uploads/berita/$filename");
                    $db->berita_gambar = $filename;
                }
            }

        }

        $db->save();
        return responseJson('Berita berhasil diupdate');
    }

    public function destroy($id)
    {
        Berita::find($id)->delete();
        return responseJson('Berita berhasil dihapus');
    }
}
