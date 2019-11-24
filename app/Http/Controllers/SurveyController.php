<?php

namespace App\Http\Controllers;

use App\Http\Resources\SurveyResource;
use App\Models\Survey;
use App\Models\SurveyFile;
use App\Models\SurveyStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SurveyController extends Controller
{

    public function index()
    {
        $data = Survey::with('status');
        return DataTables::of($data)
            ->addColumn('action', function ($value){
                return view('components.action',[
                    'name' => 'survey',
                    'value' => $value
                ]);
            })
            ->editColumn('status.ss_value', function($value){
                if($value->status->ss_level == 0){
                    $class = 'badge badge-pill text-white p-2 badge-danger';
                }else if($value->status->ss_level == 1){
                    $class = 'badge badge-pill text-white p-2 badge-warning';
                } else if($value->status->ss_level == 2){
                    $class = 'badge badge-pill text-white p-2 badge-success';
                }else if($value->status->ss_level >= 3){
                    $class = 'badge badge-pill text-white p-2 badge-info';
                }
                return '<span class="'.$class.'">'.$value->status->ss_value.'</span>';
            })
            ->rawColumns(['action', 'status.ss_value'])
            ->toJson();
    }


    public function store(Request $request)
    {
        $rules = [
            'survey_nama_lokasi' => 'required',
            'survey_nama_kegiatan' => 'required',
        ];
        $this->validate($request, $rules);
        $ss = SurveyStatus::findByLevel(1)->first();
        $db = new Survey;
        $db->user_id = auth()->id();
        $db->survey_nama_lokasi = $request->survey_nama_lokasi;
        $db->survey_nama_kegiatan = $request->survey_nama_kegiatan;
        $db->ss_id = $ss->ss_id;
        return $db->save()
            ? responseJson('Survey berhasil diupdate')
            : responseJson('Survey gagal diupdate', '', false, 'error', '500');
    }

    public function show($id)
    {
        return new SurveyResource(Survey::with('status')
            ->find($id));
    }

    public function edit($id)
    {
        return new SurveyResource(Survey::with('status')->find($id));
    }

    public function update(Request $request, $id)
    {
        $db = Survey::find($id);
        $ss = SurveyStatus::find($db->ss_id);
        switch ($ss->ss_level){
            //status level 1 baru upload
            case 1:
                //jika disetujui
                if($request->checked_helper){
                    $rules = [
                        'survey_nama_lokasi' => 'required',
                        'survey_nama_kegiatan' => 'required',
                        'survey_tanggal' => 'required'
                    ];
                }else{
                    $rules = [
                        'survey_nama_lokasi' => 'required',
                        'survey_nama_kegiatan' => 'required',
                    ];
                }
                break;

            default:
                $rules = [
                    'survey_nama_lokasi' => 'required',
                    'survey_nama_kegiatan' => 'required',
                ];
        }
        $this->validate($request, $rules);
        //dd($request->all());
        //handle request form
        //status level 1 baru upload
        switch ($ss->ss_level){
            case 1:
                //jika disetuji maka level = 2
                //jika tidak maka level = 0;
                $nextLevel = $request->checked_helper == true
                    ? $nextLevel = 2
                    : $nextLevel = 0;
                $nextLevelDb = SurveyStatus::findByLevel($nextLevel)->first();
                if(auth()->user()->role != 'user'){
                    $db->ss_id = $nextLevelDb->ss_id;
                }
                $db->survey_tanggal = $request->survey_tanggal;
                $db->checked_helper = $request->checked_helper;
                break;
            case 2:
                //jika disetuji maka level = 4
                //jika tidak maka level = 0;
                $nextLevel = $request->checked_helper2 == true
                    ? $nextLevel = 3
                    : $nextLevel = 2;
                $nextLevelDb = SurveyStatus::findByLevel($nextLevel)->first();
                $db->ss_id = $nextLevelDb->ss_id;
                $db->survey_tanggal = $request->survey_tanggal;
                $db->checked_helper2 = $request->checked_helper2;
                break;
        }
        $db->survey_nama_lokasi = $request->survey_nama_lokasi;
        $db->survey_nama_kegiatan = $request->survey_nama_kegiatan;
        return $db->save()
            ? responseJson('Survey berhasil diupdate')
            : responseJson('Survey gagal diupdate', '', false, 'error', '500');

    }

    //upload file
    public function upload(Request $request ,$id)
    {
        ini_set('max_file_uploads', '8M');
        ini_set('post_max_size', '8M');
        $survey = Survey::find($id);
        $status = $survey->status;

        if ($status->ss_level == 2){
            $this->validate($request,[
                'file' => 'required|mimes:xls,xlsx,csv'
            ]);
        }else if ($status->ss_level >= 3){
            $this->validate($request,[
                'file' => 'required|mimes:xls,xlsx,csv,pdf'
            ]);
        }

        $file = $request->file('file');
        $db = new SurveyFile();

        if ($file->isValid()) {
            $fileName = Str::random();
            $file->storeAs("/uploads/surveys", $fileName);
            $db->survey_id = $survey->survey_id;
            $db->file_name = $fileName;
            $db->file_ext = $file->getClientOriginalExtension();
            $explode = explode('.', $file->getClientOriginalName());
            $fileTitle = count($explode) > 0 ? $explode[0] : $file->getClientOriginalName();
            $db->file_title = $fileTitle;
            $db->save();
            return responseJson("File berhasil diupload", "", true, "success");
        }

    }

    public function destroy($id)
    {
        Survey::find($id)->delete();
        return responseJson('Survey berhasil dihapus');
    }
}
