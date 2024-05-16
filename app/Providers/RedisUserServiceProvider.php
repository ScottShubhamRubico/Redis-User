<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;

class RedisUserServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $redis = Redis::connection();
        $usersFromRedis = json_decode($redis->get('users'));
        $usersFromDB = User::all();
        $usersFromRedisArray = collect($usersFromRedis)->toArray();
        $usersFromDBArray = $usersFromDB->toArray();
        if ($usersFromRedisArray != $usersFromDBArray) {
            $redis->set('users', json_encode($usersFromDBArray));
        }
    }
}
