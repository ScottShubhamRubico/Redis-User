<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RedisUserServiceProvider;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Redis;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    protected User $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $redis = Redis::connection();
        return $redis->get('users');
    }
}
