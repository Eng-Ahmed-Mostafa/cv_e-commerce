<?php

namespace App\Interface\Http\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UserRepository implements UserInterface
{
    public function getAllPaginated(int $perPage = 10)
    {
        return User::paginate($perPage);
    }

    public function updateProfile(array $data): void
    {
        $user = Auth::user();

        $user->update([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => $data['new_password']
                ? Hash::make($data['new_password'])
                : $user->password,
        ]);
    }
}
