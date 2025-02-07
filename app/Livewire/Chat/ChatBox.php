<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use App\Notifications\MessageSent;
use Livewire\Component;
use Illuminate\Support\Str;


class ChatBox extends Component
{

    public $selectedConversation;
    public $body;
    public $loadedMessages;


    protected $listeners = [
        'loadMore'
    ];




    public $paginate_var = 10;

    public function render()
    {
        return view('livewire.chat.chat-box');
    }

    public function sendMessage()
    {
        $this->validate([
            'body' => 'required|string|max:1000'
        ]);
        $body = Str::squish($this->body);
        $createdMessage = Message::create([
           'conversation_id' => $this->selectedConversation->id,
           'sender_id' => auth()->id(),
           'receiver_id' => $this->selectedConversation->getReceiver()->id,
           'body' => $body,
        ]);
        $this->reset(['body']);
        $this->resetValidation('body');

        // scroll to bottom
        $this->dispatch('scroll-bottom');

        // push the message
        $this->loadedMessages->push($createdMessage);

//        // send to others
//        broadcast(new Message($body))->toOthers();



        // update conversation model
        $this->selectedConversation->updated_at = now();
        $this->selectedConversation->save();

        // refresh chatList
        $this->dispatch('chat.chat-list','refresh');
    }

    public function loadedMessages()
    {
        $count = Message::where('conversation_id',$this->selectedConversation->id)->count();
        $this->loadedMessages = Message::where('conversation_id',$this->selectedConversation->id)
            ->skip($count-$this->paginate_var)
            ->take($this->paginate_var)
            ->get();
        return $this->loadedMessages;
    }

    public function loadMore() : void
    {
        // increment
        $this->paginate_var += 10;

        // call loadMessages
        $this->loadedMessages();

        // update the chat height
        $this->dispatch('update-chat-height');
    }

    public function mount()
    {
        $this->loadedMessages();
    }

}
