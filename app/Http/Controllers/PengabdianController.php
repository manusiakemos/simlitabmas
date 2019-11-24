<?php

namespace App\Http\Controllers;

use App\Http\Resources\PenelitianResource;
use App\Http\Resources\PengabdianResource;
use App\Models\Pengabdian;
use App\Models\SurveyStatus;
use App\Notifications\Pemberitahuan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\DataTables;

class PengabdianController extends Controller
{

    public function index()
    {
        $data = Pengabdian::query()
            ->latest()
            ->with('status')
            ->with('files');
        return DataTables::of($data)
            ->addColumn('action', function ($value){
                return view('components.action',[
                    'name' => 'pengabdian',
                    'value' => $value,
                    'is_pengabdian' => true
                ]);
            })
            ->editColumn('penelitian_anggaran', function($value){
                return rupiah($value['penelitian_anggaran']);
            })
            ->editColumn('status.ss_value', function($value){
                if($value->status->ss_level == 0){
                    return'<span class="badge badge-danger p-2">'.$value->status->ss_value.'</span>';
                }else  if($value->status->ss_level == 1){
                    return'<span class="badge badge-primary p-2">'.$value->status->ss_value.'</span>';
                }else  if($value->status->ss_level > 1){
                    return'<span class="badge badge-success p-2">'.$value->status->ss_value.'</span>';
                }
            })
            ->rawColumns(['action','penelitian_ringkasan', 'status.ss_value'])
            ->toJson();
    }

    public function edit($id)
    {
        return new PengabdianResource(Pengabdian::with('status')->find($id));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'penelitian_judul' => 'required',
            'penelitian_anggaran' => 'required',
            'penelitian_ringkasan' => 'required',
            'pengabdian_tempat' => 'required',
        ]);

        $db = new Pengabdian;
        $save = $this->handleRequest($db, $request);
        if ($save) {
            $role = auth()->user()->role;
            if($role == 'user'){
                $users = User::where('role', '=', 'admin')->get();
                Notification::send($users, new Pemberitahuan('Pengabdian baru '. $db->penelitian_judul, 'pengabdian'));
            }
            return responseJson('Pengabdian berhasil ditambahkan');
        }
    }

    public function update(Request $request, $id)
    {
        if(!$request->has('ss_level')){
            $this->validate($request,[
                'penelitian_judul' => 'required',
                'penelitian_anggaran' => 'required',
                'penelitian_ringkasan' => 'required',
                'pengabdian_tempat' => 'required',
            ]);
        }

        $db = Pengabdian::find($id);
        $save = $this->handleRequest($db, $request);
        if ($save) {
            return responseJson('Pengabdian berhasil diupdate');
        }
    }

    private function handleRequest($db, $request)
    {
        $role = auth()->user()->role;
        if($request->has('ss_level') && $request->ss_level !== null){
            $status = SurveyStatus::findByLevel($request->ss_level)->first();
            $db->ss_id = $status->ss_id;

            if($role == 'admin'){
                $user = User::find($db->user_id);
                $user->notify(new Pemberitahuan($db->penelitian_judul .' ' . $status->ss_value,'pengabdian'));
            }else{
                $users = User::where('role', '=', 'admin')->get();
                Notification::send($users, new Pemberitahuan('Update pengabdian ' . $db->penelitan_judul, 'pengabdian'));
            }
        }else{
            if($role == 'user'){
                $db->user_id = auth()->id();
            }
            $status = SurveyStatus::findByLevel(1)->first();
            $db->is_pengabdian = true;
            $db->ss_id = $status->ss_id;
            $db->penelitian_anggaran = $request->penelitian_anggaran;
            $db->pengabdian_tempat = $request->pengabdian_tempat;
            $db->penelitian_judul = $request->penelitian_judul;
            $db->penelitian_ringkasan = $request->penelitian_ringkasan;
            $db->penelitian_tahun_pelaksanaan = $request->penelitian_tahun_pelaksanaan;
        }
        return $db->save();
    }

    public function destroy($id)
    {
        Penelitian::destroy($id);
        return responseJson('Penelitian berhasil dihapus');
    }

}
