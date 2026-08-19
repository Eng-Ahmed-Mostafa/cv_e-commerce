<?php

namespace App\Interface\Http\Users;

use App\Models\User;

interface UserInterface
{
    public function getAllPaginated(int $perPage = 10);
    
    public function updateProfile(array $data): void;
}
