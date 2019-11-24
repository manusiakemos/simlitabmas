<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function store(Request $request)
    {
        $db = new Chat;
        $db->chat = $request->chat;
        $db->user_id = $request->user_id;
        $db->survey_id = $request->id;
        $db->save();
        broadcast(new ChatEvent($db->survey_id, $db->chat_id));
        return responseJson('Chat berhasil dikirim');
    }

    public function show($id)
    {
        $db = Chat::with('user')->where('survey_id', $id)
            ->latest()
            ->paginate();
        return ChatResource::collection($db);
    }

    public function edit($id)
    {
        $db = Chat::with('user')->find($id);
        return new ChatResource($db);
    }


    public function destroy($id)
    {
        //
    }
}
