<?php

namespace App\Livewire\Admin\User;

use App\Enum\UserSetting;
use App\Interface\Http\Users\UserInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    /**
     * Properties
     */
    public ?User $user;
    public ?string $name;
    public ?string $phone;
    public ?string $email;
    public ?string $current_password = '';
    public ?string $new_password;
    public ?string $confirm_password;
    private UserInterface $userRepository;
    public UserSetting $mode = UserSetting::DISPLAY;

    public function boot(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function mount()
    {
        $route = request()->route();
        $this->mode = match ($route->getName()) {
            'admin.user' => UserSetting::DISPLAY,
            'admin.user.settings' => UserSetting::SETTINGS,
            default => UserSetting::DISPLAY,
        };

        if ($this->mode === UserSetting::SETTINGS) {
            $this->user = Auth::user();
            $this->name = $this->user->name;
            $this->phone = $this->user->phone;
            $this->email = $this->user->email;
        }
    }

    public function render()
    {
        $users = User::with('orders')->paginate(10);
        return view('livewire.admin.user.index',compact('users'))->layout('layouts.admin');
    }

    public function rules()
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

        return $rules;
    }

    public function updateProfile()
    {
        $this->validate();

        if (!Hash::check($this->current_password, $this->user->password)) {
            $this->addError('current_password', 'Password not correct');
            return;
        }

        $data = $this->userData();

        $this->userRepository->updateProfile($data);

        session()->flash('success', 'Edit infromation is successifly');
        $this->reset(['current_password', 'new_password', 'confirm_password']);
    }

    public function userData() {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'new_password' => $this->new_password,
        ];
    }

}
