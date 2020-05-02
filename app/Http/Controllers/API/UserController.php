<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UserCollection;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{

    /**
     * @return UserCollection
     */
    public function index()
    {
        $persons = User::where('role', '!=', '3')->orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $persons = $persons->where('name', 'LIKE', '%' . request()-> q . '%');
        }

        return new UserCollection($persons->paginate(10));
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
            'password' => 'required|min:6',
            'role' => 'required'
        ] , [
            'name.required' => 'Field tidak boleh kosong!',
            'email.required' => 'Field tidak boleh kosong!',
            'email.unique' => 'Email telah terdaftar di database!',
            'password.required' => 'Field tidak boleh kosong!',
            'password.min' => 'Password Min 6 karakter!',
            'role.required' => 'Field tidak boleh kosong'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     */
    public function edit($id)
    {
        $users = User::find($id);

        return response()->json(['status' => 'success', 'data' => $users]);
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     *
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:150',
            'email' => 'required',
            'password' => '->nullable|min:6',
            'role' => 'required'
        ], [
            'name.required' => 'Field tidak boleh kosong',
            'email.required' => 'Field tidak boleh kosong',
            'password.min' => 'Password Min 6 karakter',
            'role.required' => 'Field tidak boleh kosong'
        ]);

        $users = User::find($id);
        $password = $request->password != '' ? bcrypt($request->password):$users->password;
        $users->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'role' => $request->role
        ]);

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $users = User::find($id);
        $users->delete();

        return response()->json(['status' => 'success']);
    }

    /**
     * @return UserCollection
     */
    public function userLists()
    {
        $user = User::where('role', '!=', 3)->get();
        return new UserCollection($user);
    }

    /**
     * @return JsonResponse
     */
    public function getUserLogin()
    {
        $user = request()->user();
        $permissions = [];
        foreach (Permission::all() as $permission) {
            if (request()->user()->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }
        $user['permission'] = $permissions;

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
}
