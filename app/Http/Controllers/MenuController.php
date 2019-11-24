<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function order(Request $request, $id)
    {
        $db = Menu::find($id);
        if($request->has('parent')){
            $parent = is_array($request->parent) ? null : $request->parent;
            $db->parent_id = $parent;
        }
        if($request->has('order')){
            $db->order = $request->order;
        }
        $db->save();
        return responseJson("Menu berhasil diupdate");
    }

    public function index()
    {
        $menu = Menu::orderWithChildren()->get();
//        $menu = Menu::all();
        return $menu;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $db = new Menu;
        $db->parent_id = null;
        $db->type = $request->type;
        $db->url = $request->custom_url && $request->type == 'link' ? $request->url : Str::slug($request->name);
        $db->name = $request->name;
        $db->custom_url = $request->custom_url;
        $db->hal_id = $request->hal_id;
        $db->save();
        return responseJson("Menu berhasil ditambahkan");
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
        $db = Menu::find($id);
        $db->type = $request->type;
        $db->url = $request->custom_url && $request->type == 'link' ? $request->url : Str::slug($request->name);
        $db->name = $request->name;
        $db->custom_url = $request->custom_url;
        $db->hal_id = $request->hal_id;
        $db->save();
        return responseJson("Menu berhasil diupdate");
    }

    public function destroy($id)
    {
        $db = Menu::find($id);
        $db->delete();
        return responseJson("Menu berhasil dihapus");
    }
}
