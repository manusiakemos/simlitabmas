<?php

namespace App\Http\Controllers;

use App\Notifications\Pemberitahuan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PemberitahuanController extends Controller
{
    public function unread(Request $request)
    {
        $user = $request->user();

        return $user->unreadNotifications;
    }

    public function readAll(Request $request)
    {
        $user = $request->user();
        $user->unreadNotifications->markAsRead();
        return responseJson('notifikasi berhasil dibaca');
    }

    public function readme(Request $request)
    {
        DB::table('notifications')->where('id', $request->id)->update([
            'read_at' => now()
        ]);
        return responseJson('notifikasi berhasil dibaca');
    }

    public function send()
    {
        $users = User::all();
        Notification::send($users, new Pemberitahuan('test'));
    }
}
