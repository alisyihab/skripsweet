<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use File;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = User::orderBy('created_at', 'DESC')->customer();

        if (request()->q != '') {
            $customer = $customer->where('name', 'LIKE', '%' . request()->q . '%');
        }

        $customer = $customer->paginate(10);

        return new UserCollection($customer);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nik' => 'required|unique:users,nik',
            'name' => 'required|string|max:150',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|min:6|string',
            'address' => 'required|string',
            'phone' => 'required|string|max:13',
            'photo' => 'required|image|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $name = null;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $name = $request->name . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/users', $name);
            }

            $customer = User::create([
                'nik' => $request->nik,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'photo' => $name,
                'address' => $request->address,
                'phone' => $request->phone,
                'point' => 0,
                'deposit' => 0,
                'role' => 3,
            ]);

            DB::commit();

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $customer = User::find($id);

        return response()->json(['status' => 'success', 'data' => $customer]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:150',
            'password' => 'nullable|min:6|string',
            'address' => 'required|string',
            'phone' => 'required|string|max:13',
            'photo' => 'nullable|image|max:5120'
        ]);

        try {
            $customer = User::find($id);
            $password = $request->password != '' ? bcrypt($request->password):$customer->password;
            $fileName = $customer->photo;

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');

                File::delete(storage_path('app/public/users/' . $fileName));
                $fileName = $request->name . '-' . time() . '.' .$file->getClientOriginalExtension();
                $file->storeAs('public/users', $fileName);
            }

            $customer->update([
                'nik' => $request->nik,
                'name' => $request->name,
                'password' => $password,
                'address' => $request->address,
                'phone' => $request->phone,
                'photo' => $fileName
            ]);

            return response()->json(['status' => 'success'], 200);
            } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $customer = User::find($id);
        File::delete(storage_path('app/public/couriers/' . $customer->photo));
        $customer->delete();

        return response()->json(['status' => 'success']);
    }
}
