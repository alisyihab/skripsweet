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
        ]);

        DB::beginTransaction();
        try {
            $name = null;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $name = $request->email . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/couriers', $name);
            }

            User::create([
               'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => $request->role,
                'photo' => $name,
                'outlet_id' => $request->outlet_id,
                'role' => 3,
            ]);
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
}
