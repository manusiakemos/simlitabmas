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

    private $type;

    public function index(Request $request)
    {
        $data = Pengabdian::query()
            ->latest()
            ->with('status')
            ->with('files');
        if($request->has('filter_tahun_cetak') && $request->filter_tahun_cetak){
            $ss = SurveyStatus::findByLevel(2)->first();
            $data = $data->where('penelitian_tahun_pelaksanaan', $request->filter_tahun_cetak)
                ->where('ss_id', $ss->ss_id)
                ->get();
            return $data;
        }
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
        $this->type = 'store';
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
        $this->type = 'update';
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
            if($this->type == 'update' && $request->ss_level == 1){
                $status = SurveyStatus::findByLevel(0)->first();
            }else{
                $status = SurveyStatus::findByLevel($request->ss_level)->first();
            }
            $db->ss_id = $status->ss_id;
            if($role == 'admin'){
                $user = User::find($db->user_id);
                $user->notify(new Pemberitahuan($db->penelitian_judul .' ' . $status->ss_value,'pengabdian'));
            }else{
                $users = User::where('role', '=', 'admin')->get();
                Notification::send($users, new Pemberitahuan('Update pengabdian ' . $db->penelitan_judul, 'pengabdian'));
            }
            return $db->save();
        }else{
            if($role == 'user'){
                if ($this->type == 'store') {
                    $status = SurveyStatus::findByLevel(1)->first();
                }
                if (isset($status)) {
                    $db->ss_id = $status->ss_id;
                }
                $db->user_id = auth()->id();
                $db->is_pengabdian = true;
                $db->penelitian_anggaran = $request->penelitian_anggaran;
                $db->pengabdian_tempat = $request->pengabdian_tempat;
                $db->penelitian_judul = $request->penelitian_judul;
                $db->penelitian_ringkasan = $request->penelitian_ringkasan;
                $db->penelitian_tahun_pelaksanaan = $request->penelitian_tahun_pelaksanaan;
            }else{
                $status = SurveyStatus::findByLevel(0)->first();
                if (isset($status)) {
                    $db->ss_id = $status->ss_id;
                }
            }
        }
        return $db->save();
    }

    public function destroy($id)
    {
        Penelitian::destroy($id);
        return responseJson('Penelitian berhasil dihapus');
    }

}
