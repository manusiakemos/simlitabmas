<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\Broadcasting\ChatChannel;
use App\Broadcasting\Pemberitahuan;

Broadcast::channel('chat.{survey_id}', ChatChannel::class);
Broadcast::channel('App.User.{id}', Pemberitahuan::class);
