<?php

namespace App\Http\Controllers\API;

use App\Customer;
use App\Http\Resources\CustomerCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with(['courier'])->orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $customers = $customers->where('name', 'LIKE', '%' . request()->q . '%');
        }

        return new CustomerCollection($customers->paginate(10));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nik' => 'required|string|unique:customers,nik',
            'name' => 'required|string|max:150',
            'address' => 'required|string',
            'phone' => 'required|string|max:13'
        ], [
            'nik.required' => 'Field tidak boleh kosong!',
            'nik.unique' => 'Nik Sudah digunakan!',
            'name.required' => 'Field tidak boleh kosong!',
            'name.max' => 'Nama tidak boleh lebih dari 150 karakter',
            'address.required' => 'Field tidak boleh kosong!',
            'phone.required' => 'Field tidak boleh kosong!',
            'phone.max' => 'No HP tidak boleh lebih dari 13 karakter'
        ]);

        $user = $request->user();
        $request->request->add([
            'point' => 0,
            'deposit' => 0
        ]);
        if ($user->role == 3) {
            $request->request->add(['courier_id' => $user->id]);
        }
        $customer = Customer::create($request->all());

        return response()->json(['status' => 'success', 'data' => $customer]);
    }

    public function edit($id)
    {
        $customer = Customer::find($id);

        return response()->json([
           'status' => 'success',
           'data' => $customer
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:150',
            'address' => 'required|string',
            'phone' => 'required|string|max:13'
        ], [
            'name.required' => 'Field tidak boleh kosong!',
            'name.max' => 'Nama tidak boleh lebih dari 150 karakter',
            'address.required' => 'Field tidak boleh kosong!',
            'phone.required' => 'Field tidak boleh kosong!',
            'phone.max' => 'No HP tidak boleh lebih dari 13 karakter'
        ]);

        $customer = Customer::find($id);
        $customer->update([$request->all()]);

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        $customer->delete();

        return response()->json(['status' => 'success']);
    }
}
