<?php

namespace App\Http\Controllers;

use App\Http\Resources\HalamanResource;
use App\Models\Halaman;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class HalamanController extends Controller
{

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return DataTables::of(Halaman::query())
            ->addColumn('action', function (Halaman $value) {
                return view('components.action',[
                    'name' => 'halaman',
                    'value' => $value
                ]);
            })
            ->editColumn('hal_aktif', function (Halaman $value) {
                return $value['hal_aktif'] ? 'aktif' : 'nonaktif';
            })
            ->editColumn('hal_custom', function (Halaman $value) {
                return $value['hal_custom'] ? 'custom' : 'umum';
            })
            ->toJson();
    }

    public function edit($id)
    {
        return new HalamanResource(Halaman::find($id));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'hal_judul' => 'required',
        ]);
        if($request->hal_custom == false){
            $url = Str::slug($request->hal_judul);
        }else{
            $url = $request->hal_url;
        }
        $db = new Halaman;
        $db->hal_url = $url;
        $db->hal_judul = $request->hal_judul;
        $db->hal_isi = $request->hal_isi;
        $db->hal_aktif = $request->hal_aktif;
        $db->hal_custom = $request->hal_custom;
        $db->save();
        return responseJson('Halaman berhasil diupdate');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'hal_judul' => 'required',
        ]);
        if($request->hal_custom == false){
            $url = Str::slug($request->hal_judul);
        }else{
            $url = $request->hal_url;
        }
        $db = Halaman::find($id);
        $db->hal_url = $url;
        $db->hal_judul = $request->hal_judul;
        $db->hal_isi = $request->hal_isi;
        $db->hal_aktif = $request->hal_aktif;
        $db->hal_custom = $request->hal_custom;
        $db->save();
        return responseJson('Halaman berhasil diupdate');
    }

    /**
     * @param Halaman $halaman
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Halaman $halaman)
    {
        $halaman->delete();
        return responseJson('Halaman Berhasil Dihapus');
    }
}
