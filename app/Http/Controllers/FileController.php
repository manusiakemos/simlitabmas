<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileModelResource;
use App\Models\FileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FileController extends Controller
{
    public function show($id)
    {
        $data = FileModel::findByPenelitianId($id)->get();

        return FileModelResource::collection($data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
           'file_title' => [
               'required',
               Rule::unique('files', 'file_title')
                   ->ignore($id, 'file_id')
           ],
        ]);
        $db = FileModel::find($id);
        $db->file_title = $request->file_title;
        $db->save();
        return responseJson('File behasil diupdate');
    }

    public function destroy($id)
    {
        $db = FileModel::find($id);
        $path = "/uploads/penelitian/{$db->file_name}.{$db->file_ext}";
        if(Storage::disk('local')->delete($path)){
            $db->delete();
            return responseJson("File berhasil dihapus");
        }else{
            return responseJson("File gagal dihapus", "", false, "error", 500);
        }
    }

    public function download($id)
    {
        $db = FileModel::find($id);
        $pathToFile = public_path('uploads/penelitian/'.$db->file_name.'.'. $db->file_ext);
        $name = $db->file_title . '.' . $db->file_ext;
        return response()->download($pathToFile, $name);
    }
}
