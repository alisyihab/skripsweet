<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UserCollection;
use App\User;
use App\Http\Controllers\Controller;

use Dotenv\Parser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use File;
use Spatie\Permission\Models\Permission;

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

    /**
     * @return UserCollection
     */
    public function userLists()
    {
        $user = User::where('role', '!=', 3)->get();
        return new UserCollection($user);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|string',
            'outlet_id' => 'required|exists:outlets,id',
            'photo' => 'required|image|max:5120'
        ], [
            'name.required' => 'Field tidak boleh Kosong!',
            'name.max' => 'Huruf tidak boleh lebih dari 150 karakter!',
            'email.required' => 'Field tidak boleh kosong!',
            'email.email' => 'Format email tidak valid. co : ali.syihab@mail.com',
            'email.unique' => 'Email sudah terdaftar!',
            'password.required' => 'Field tidak boleh kosong!',
            'password.min' => 'Password min 6 karakter',
            'outlet_id.required' => 'Field tidak boleh kosong!',
            'photo.required' => 'Field tidak boleh kosong',
            'photo.max' => 'Max upload tidak boleh lebih dari 5MB !'
        ]);

        DB::beginTransaction();
        try {
            $name = null;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $name = $request->email . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/couriers', $name);
            }

            $user = User::create([
               'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => $request->role,
                'photo' => $name,
                'outlet_id' => $request->outlet_id,
                'role' => 3,
            ]);
            $user->assignRole('courier');
            DB::commit();

            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
               'status' => 'error',
               'data' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     */
    public function edit($id)
    {
        $user = User::find($id);

        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
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
            'name' => 'required|string|max:150',
            'email' => 'required|email',
            'password' => 'nullable|min:6|string',
            'outlet_id' => 'required|exists:outlets,id',
            'photo' => 'nullable|image'
        ], [
            'name.required' => 'Field tidak boleh Kosong!',
            'name.max' => 'Huruf tidak boleh lebih dari 150 karakter!',
            'email.required' => 'Field tidak boleh kosong!',
            'email.email' => 'Format email tidak valid. co : ali.syihab@mail.com',
            'password.min' => 'Password minimal 6 karakter',
            'outlet_id.required' => 'Field tidak boleh kosong!',
            'photo.required' => 'Field tidak boleh kosong',
            'photo.max' => 'Max upload tidak boleh lebih dari 5MB !'
        ]);

        try {
            $user = User::find($id);
            $password = $request->password != '' ? bcrypt($request->password):$user->password;
            $filename = $user->photo;

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                File::delete(storage_path('app/public/couriers/' . $filename));
                $filename = $request->email . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/couriers', $filename);
            }

            $user->update([
                'name' => $request->name,
                'password' => $password,
                'photo' => $filename,
                'outlet_id' => $request->outlet_id
            ]);
            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'data' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $user =  User::find($id);
        File::delete(storage_path('app/public/couriers/' . $user->photo));
        $user->delete();

        return response()->json([
           'status' => 'success'
        ]);
    }

    /**
     * @return UserCollection
     */
    public function userList()
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
