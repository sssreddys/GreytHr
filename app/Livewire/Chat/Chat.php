<?php

namespace App\Livewire\Chat;

use Livewire\Component;

class Chat extends Component
{
    protected $listeners = ['userSelected'];

    public function userSelected($empId)
    {
        dd('hello world');
    }

    public function render()
    {
        return view('livewire.chat.chat');
    }
}
