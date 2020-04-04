<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UserCollection;
use App\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * @return UserCollection
     */
    public function index()
    {
        $users = User::with(['outlet'])->orderBy('created_at', 'DESC')->courier();
        if (request()->q != '') {
            $users = $users->where('name', 'LIKE', '%' . request()->q . '%');
        }
        $users = $users->paginate(10);
        return new UserCollection($users);
    }

}
