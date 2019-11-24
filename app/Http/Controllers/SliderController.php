<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Sliders;

use App\Http\Resources\SliderResource;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

class SliderController extends Controller
{

    public function index()
    {
        return SliderResource::collection(Sliders::all());
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'file' => 'required|image'
        ]);
        $file = $request->file('file');
        if ($file->isValid()) {
            $fileName = Str::random().".".$file->getClientOriginalExtension();
            $file->storeAs("/uploads/sliders", $fileName);

            $db = new Sliders;
            $db->slide_gambar = $fileName;
            $db->slide_aktif = true;
            $db->slide_nama = $file->getClientOriginalName();
            $db->save();
            return responseJson("Slider berhasil diupload", "", true, "success");
        }
    }

    public function edit($id)
    {
        return new SliderResource(Sliders::find($id));
    }

    public function upload(Request $request, $id)
    {
        if($request->hasFile('file')){
            $file = $request->file('file');
            if ($file->isValid()) {
                $fileName = Str::random().".".$file->getClientOriginalExtension();
                $file->storeAs("/uploads/sliders", $fileName);

                $db = Sliders::find($id);
                $path = "/uploads/sliders/{$db->slide_gambar}";
                if(Storage::disk('local')->delete($path)){
                    $db->delete();
                }
                $db->slide_gambar = $fileName;
                $db->slide_aktif = true;
                $db->slide_nama = $file->getClientOriginalName();
                $db->save();
                return responseJson("Slider berhasil diupload", "", true, "success");
            }
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'slide_nama' => 'required|string'
        ]);
        $db = Sliders::find($id);
        $db->slide_nama = $request->slide_nama;
        $db->save();
        return responseJson("Slider berhasil diupdate");
    }

    public function destroy($id)
    {
        $db = Sliders::find($id);
        $path = "/uploads/sliders/{$db->slide_gambar}";
        if(Storage::disk('local')->delete($path)){
            $db->delete();
            return responseJson("Slider berhasil dihapus");
        }else{
            return responseJson("Slider gagal dihapus", "", false, "error", 500);
        }
    }
}
