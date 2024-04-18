<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

    public function registerUser(UserDTO $userDTO): User
    {
        $data = [
            'name' => $userDTO->name,
            'email' => $userDTO->email,
            'password' => bcrypt($userDTO->password)
        ];

        return $this->userRepository->createUser($data);
    }
}
