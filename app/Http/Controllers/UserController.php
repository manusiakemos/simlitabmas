<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index()
    {
        return DataTables::of(User::query())
            ->addColumn('action', function (User $value) {
                return view('components.action',[
                    'name' => 'user',
                    'value' => $value
                ]);
            })
            ->toJson();
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
            "username" => [
                "required", Rule::unique('users', 'username')
            ],
            "role" => "required",
            "password" => "required|confirmed",
        ]);
        $db = new User;
        $db->username = $request->username;
        $db->name = $request->name;
        $db->role = $request->role;
        $db->password = bcrypt($request->password);
        $db->api_token = Str::random(60);
        $db->jabatan = $request->jabatan;
        $db->golongan = $request->golongan;
        $db->nip = $request->nip;
        $db->email = $request->email;
        $db->phone = $request->phone;
        $db->alamat = $request->alamat;

        return $db->save()
            ? response()->json([
                "status" => true,
                "text" => "success",
                "message" => "User Berhasil Ditambahkan",
            ])
            : response()->json([
                "status" => false,
                "text" => "error",
                "message" => "User Gagal Ditambahkan",
            ]);
    }


    /**
     * @param $id
     * @return UserResource
     */
    public function edit($id)
    {
        $data = User::find($id);
        return new UserResource($data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "name" => "required",
            "username" => [
                "required", Rule::unique('users', 'username')->ignore($id, 'id')
            ],
            "role" => "required",
        ]);
        $db = User::find($id);
        $db->username = $request->username;
        $db->name = $request->name;
        $db->role = $request->role;
        if ($request->has('password') && $request->password) {
            $db->password = bcrypt($request->password);
        }
        $db->nip = $request->nip;
        $db->jabatan = $request->jabatan;
        $db->golongan = $request->golongan;
        $db->email = $request->email;
        $db->phone = $request->phone;
        $db->alamat = $request->alamat;

        return $db->save()
            ? response()->json([
                "status" => true,
                "text" => "success",
                "message" => "User Berhasil Diupdate",
            ])
            : response()->json([
                "status" => false,
                "text" => "error",
                "message" => "User Gagal Diupdate",
            ]);
    }


    public function destroy($id)
    {
        User::destroy($id);
        return response()->json([
            "status" => true,
            "text" => "success",
            "message" => "User berhasil dihapus"
        ]);
    }
}
