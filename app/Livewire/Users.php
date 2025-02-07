<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\User;
use Livewire\Component;

class Users extends Component
{


    public function render()
    {
        return view('livewire.users' ,
            ['users' => User::with('user_info:id,user_id,full_name')
                ->where('id','!=',auth()->id())->get(['id'])]);
    }

    public function message($userId)
    {
        $authenticatedUserId = auth()->id();
        $existingConversation = Conversation::where(function ($query) use($authenticatedUserId,$userId){
            $query->where('sender_id',$authenticatedUserId)
                ->where('receiver_id',$userId);
        })->orWhere(function ($query) use($authenticatedUserId,$userId){
            $query->where('sender_id',$userId)
                ->where('receiver_id',$authenticatedUserId);
        })->first();
        if ($existingConversation){
            return to_route('chat',['query' => $existingConversation->id]);
        }
        $createdConversation = Conversation::create([
           'sender_id' => $authenticatedUserId,
           'receiver_id' => $userId
        ]);
        return to_route('chat',['query' => $createdConversation->id]);
    }
}
