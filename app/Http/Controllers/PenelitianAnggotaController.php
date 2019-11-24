<?php

namespace App\Http\Controllers;

use App\Http\Resources\PenelitianAnggotaResource;
use App\Models\Anggota;
use App\Models\PenelitianAnggota;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class PenelitianAnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DataTables::of(Anggota::query())
            ->addColumn('action', function ($value) {
                return view('components.action', [
                    'name' => 'penelitiananggota',
                    'value' => $value
                ]);
            })
            ->rawColumns(['action', 'alamat'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "anggota_id" => [
                'required',
                Rule::unique('penelitian_anggota','anggota_id')
                ->where('penelitian_id', $request->penelitian_id)
            ],
            "penelitian_id" => "required"
        ]);
        $db = new PenelitianAnggota;
        $db->anggota_id = $request->anggota_id;
        $db->penelitian_id = $request->penelitian_id;
        $db->save();
        return responseJson('Anggota berhasil dipilih');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = PenelitianAnggota::with('anggota')
            ->with('penelitian')
            ->where('penelitian_id', $id)
            ->get();
        return PenelitianAnggotaResource::collection($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PenelitianAnggota::destroy($id);
        return responseJson('Anggota berhasil dihapus');
    }
}
