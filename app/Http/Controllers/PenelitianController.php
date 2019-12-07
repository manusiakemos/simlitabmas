<?php

namespace App\Http\Controllers;

use App\Http\Resources\PenelitianResource;
use App\Models\FileModel;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\SurveyStatus;
use App\Notifications\Pemberitahuan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PenelitianController extends Controller
{

    public function index(Request $request)
    {
//        $ss = SurveyStatus::all();
        $data = Penelitian::query()
            ->latest()
            ->with('status')
            ->with('files');
        if ($request->has('filter_tahun_cetak') && $request->filter_tahun_cetak) {
            $ss = SurveyStatus::findByLevel(2)->first();
            $data = $data->where('penelitian_tahun_pelaksanaan', $request->filter_tahun_cetak)
                ->where('ss_id', $ss->ss_id)
                ->get();
            return $data;
        }
        return DataTables::of($data)
            ->addColumn('action', function ($value) {
                return view('components.action', [
                    'name' => 'penelitian',
                    'value' => $value,
//                    'status' => $ss
                ]);
            })
            ->editColumn('penelitian_anggaran', function ($value) {
                return rupiah($value['penelitian_anggaran']);
            })
            ->editColumn('status.ss_value', function ($value) {
                if ($value->status->ss_level == 0) {
                    return '<span class="badge badge-danger p-2">' . $value->status->ss_value . '</span>';
                } else if ($value->status->ss_level == 1) {
                    return '<span class="badge badge-primary p-2">' . $value->status->ss_value . '</span>';
                } else if ($value->status->ss_level > 1) {
                    return '<span class="badge badge-success p-2">' . $value->status->ss_value . '</span>';
                }
            })
            ->rawColumns(['action', 'penelitian_ringkasan', 'status.ss_value'])
            ->toJson();
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'penelitian_judul' => 'required',
            'penelitian_anggaran' => 'required',
            'penelitian_ringkasan' => 'required',
        ]);

        $db = new Penelitian;
        $save = $this->handleRequest($db, $request, 'store');
        if ($save) {
            $role = auth()->user()->role;
            if ($role == 'user') {
                $users = User::where('role', '=', 'admin')->get();
                Notification::send($users, new Pemberitahuan('Penelitian baru ' . $db->penelitian_judul));
            }
            return responseJson('Penelitian berhasil ditambahkan');
        }
    }


    public function show($id)
    {
        abort('404');
    }


    public function edit($id)
    {
        $data = Penelitian::with('status')->find($id);
        return new PenelitianResource($data);
    }

    public function update(Request $request, $id)
    {
        if (!$request->has('ss_level')) {
            $this->validate($request, [
                'penelitian_judul' => 'required',
                'penelitian_anggaran' => 'required',
                'penelitian_ringkasan' => 'required',
            ]);
        }

        $db = Penelitian::find($id);
        $save = $this->handleRequest($db, $request, 'update');
        if ($save) {
            return responseJson('Penelitian berhasil diupdate');
        }
    }

    public function destroy($id)
    {
        Penelitian::destroy($id);
        return responseJson('Penelitian berhasil dihapus');
    }

    private function handleRequest($db, $request, $type)
    {
        $role = auth()->user()->role;
        if ($request->has('ss_level') && $request->ss_level != null) {
            if($type == 'update' && $request->ss_level == 1){
                $status = SurveyStatus::findByLevel(0)->first();
            }else{
                $status = SurveyStatus::findByLevel($request->ss_level)->first();
            }
            $db->ss_id = $status->ss_id;
            $db->penelitian_alasan_ditolak = $request->penelitian_alasan_ditolak;
            if (isset($status)) {
                $db->ss_id = $status->ss_id;
            }

            if ($role == 'admin') {
                $user = User::find($db->user_id);
                $user->notify(new Pemberitahuan($db->penelitian_judul . ' ' . $status->ss_value));
            } else {
                $users = User::where('role', '=', 'admin')->get();
                Notification::send($users, new Pemberitahuan('Update penelitian ' . $db->penelitan_judul));
            }
            return $db->save();
        } else {
            if ($role == 'user') {
                $db->user_id = auth()->id();
                if ($type == 'store') {
                    $status = SurveyStatus::findByLevel(1)->first();
                }
                if (isset($status)) {
                    $db->ss_id = $status->ss_id;
                }
                $db->penelitian_anggaran = $request->penelitian_anggaran;
                $db->penelitian_judul = $request->penelitian_judul;
                $db->penelitian_ringkasan = $request->penelitian_ringkasan;
                $db->penelitian_tahun_pelaksanaan = $request->penelitian_tahun_pelaksanaan;
            }else{
                $db->penelitian_alasan_ditolak = $request->penelitian_alasan_ditolak;
                $status = SurveyStatus::findByLevel(0)->first();
                if (isset($status)) {
                    $db->ss_id = $status->ss_id;
                }
            }
        }
        return $db->save();
    }

    public function upload(Request $request, $id)
    {
        $type = 'penelitian';
        $penelitian = Penelitian::find($id);
        if (!$penelitian) {
            $type = 'pengabdian';
            $penelitian = Pengabdian::find($id);
        }

        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx,csv,pdf'
        ]);

        $file = $request->file('file');
        $db = FileModel::findByPenelitianId($penelitian->penelitian_id)->first();
        if (!$db) {
            $db = new FileModel();
        } else {
            $path = "/uploads/penelitian/{$db->file_name}.{$db->file_ext}";
            Storage::disk('local')->delete($path);
        }

        if ($file->isValid()) {
            $fileName = Str::random();
            $file->storeAs("/uploads/penelitian", $fileName . '.' . $file->getClientOriginalExtension());
            $db->penelitian_id = $penelitian->penelitian_id;
            $db->file_name = $fileName;
            $db->file_ext = $file->getClientOriginalExtension();

            $explode = explode('.', $file->getClientOriginalName());
            $fileTitle = count($explode) > 0 ? $explode[0] : $file->getClientOriginalName();

            $db->file_title = $fileTitle;

            $db->save();
            $role = auth()->user()->role;
            if ($role == 'admin') {
                $user = User::find($penelitian->user_id);
                $user->notify(new Pemberitahuan('Update file', "$type"));
            } else {
                $users = User::where('role', '=', 'admin')->get();
                Notification::send($users, new Pemberitahuan('Update file ' . "$type" . ' ' . $penelitian->penelitian_judul, "$type"));
            }
            return responseJson("File berhasil diupload", "", true, "success");
        }

    }

}
