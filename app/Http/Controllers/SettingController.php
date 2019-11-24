<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettingResource;
use App\Http\Resources\SettingResourceGroup;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function order(Request $request, $id)
    {
        $db = Settings::find($id);
        $db->setting_group = $request->parent;
        $db->save();
        return responseJson("Setting berhasil diupdate");
    }

    public function index()
    {
        $data = Settings::all();
        return SettingResource::collection($data);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'setting_input' => 'required',
            'setting_value' => 'required',
            'setting_name' => 'required',
            'setting_group' => 'required',
        ]);
        $db = new Settings;
        $db->setting_input = $request->setting_input;
        $db->setting_icon = $request->setting_icon;
        $db->setting_name = $request->setting_name;
        if($request->setting_input == 'html'){
            $db->setting_value = html_entity_decode($request->setting_value);
        }else{
            $db->setting_value = $request->setting_value;
        }
        $db->setting_group = $request->setting_group;
        $db->save();
        return responseJson("Setting berhasil ditambahkan");
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $db = Settings::find($id);
        $db->setting_input = $request->setting_input;
        $db->setting_icon = $request->setting_icon;
        $db->setting_name = $request->setting_name;
        $db->setting_value = $request->setting_value;
        $db->save();
        return responseJson("Setting berhasil diupdate");
    }

    public function destroy($id)
    {
        $id = Settings::find($id);
        $id->delete();
        return responseJson("Setting berhasil dihapus");

    }
}
