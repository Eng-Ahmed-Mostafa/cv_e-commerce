<?php

namespace App\Livewire\Admin\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Setting extends Component
{
    public $user;
    public $name;
    public $phone;
    public $email;
    public $current_password = '';
    public $new_password;
    public $confirm_password;

    public function mount()
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->phone = $this->user->phone;
        $this->email = $this->user->email;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email',
            'current_password' => 'required',
        ];

        if ($this->new_password) {
            $rules = array_merge($rules, [
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|same:new_password',
            ]);
        }

        // dd($this->phone);
        $this->validate($rules);

        if (!Hash::check($this->current_password, $this->user->password)) {
            $this->addError('current_password', 'Password not correct');
            return;
        }

        $this->user->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'password' => $this->new_password
                ? Hash::make($this->new_password)
                : $this->user->password,
        ]);

        session()->flash('success', 'Edit infromation is successifly');
        $this->reset(['current_password', 'new_password', 'confirm_password']);
    }

    public function render()
    {
        return view('livewire.admin.user.setting')->layout('layouts.admin');
    }
}

