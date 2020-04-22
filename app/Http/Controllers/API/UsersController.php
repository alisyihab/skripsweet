<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UsersCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UsersController extends Controller
{
    public function index()
    {
        $persons = User::orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $persons = $persons->where('name', 'LIKE', '%' . request()-> q . '%');
        }

        return new UsersCollection($persons->paginate(10));
    }
}
