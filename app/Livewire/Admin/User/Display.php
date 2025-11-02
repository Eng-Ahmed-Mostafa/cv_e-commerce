<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Display extends Component
{
    use WithPagination;

    public function render()
    {
        $users = User::with('orders')->paginate(10);
        return view('livewire.admin.user.display',compact('users'))->layout('layouts.admin');
    }
}
