<?php

namespace App\Http\Services;
use App\Http\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUsers()
    {
        $users = $this->userRepository->getUsers(1000);

        return $users;
    }
}