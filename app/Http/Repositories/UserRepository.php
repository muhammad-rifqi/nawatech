<?php

namespace App\Http\Repositories;

use App\Http\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;
use App\Models\Users;

class UserRepository
{
    public function getUsers($limit = 1000)
    {
       return Cache::remember("users_limit_{$limit}", 60, function () use ($limit) {
            return Users::select('id','name','email')
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();
        });
    }
}