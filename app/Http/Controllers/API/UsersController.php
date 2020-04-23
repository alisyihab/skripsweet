<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UsersCollection;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:150',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6'
        ] , [
            'name.required' => 'Field tidak boleh kosong!',
            'email.required' => 'Field tidak boleh kosong!',
            'email.unique' => 'Email telah terdaftar di database!',
            'password.required' => 'Field tidak boleh kosong!',
            'password.min' => 'Password Min 6 karakter!'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 1
        ]);

        return response()->json(['status' => 'success'], 200);
    }
}
