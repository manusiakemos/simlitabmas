<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->avatar
            ? $avatar = asset("/img/avatars/$this->avatar")
            : $avatar = asset('https://bootdey.com/img/Content/avatar/avatar1.png');

        return array(
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => '',
            'password_confirmation' => '',
            'avatar' => $avatar,
            'api_token' => $this->api_token,
            'role' => $this->role,
            'links' =>[
                'update' => route('profile.update'),
                'edit' => route('profile.edit'),
                'avatar' => route('profile.avatar'),
            ]
        );
    }
}
