<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OutletCollection;
use App\Outlet;
use Illuminate\Validation\ValidationException;

class OutletController extends Controller
{
    public function index()
    {
        $outlets = Outlet::orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $outlets = $outlets->where('name', 'LIKE', '%' . request()->q . '%');
        }

        return new OutletCollection($outlets->paginate(10));
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
            'code' => 'required|unique:outlets,code',
            'name' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'required|max:13'
        ], [
            'code.required' => 'Field tidak boleh kosong!',
            'code.unique' => 'Code sudah terdaftar',
            'name.required' => 'Field tidak boleh kosong',
            'name.max' => 'Max karakter tidak boleh lebih dari 100 karakter',
            'address.required' => 'Field tidak boleh kosong',
            'phone.required' => 'Field tidak boleh kosong',
            'phone.max' => 'No telp tidak boleh lebih dari 13 karakter'
        ]);

        Outlet::create($request->all());
        return response()->json(['status' => 'success'], 200);
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     */
    public function edit($id)
    {
        $outlet = Outlet::whereCode($id)->first();
        return response()->json(['status' => 'success', 'data' => $outlet], 200);
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
            'code' => 'required|exists:outlets,code',
            'name' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'required|max:13'
        ], [
            'code.required' => 'Field tidak boleh kosong!',
            'code.unique' => 'Code sudah terdaftar',
            'name.required' => 'Field tidak boleh kosong',
            'name.max' => 'Max karakter tidak boleh lebih dari 100 karakter',
            'address.required' => 'Field tidak boleh kosong',
            'phone.required' => 'Field tidak boleh kosong',
            'phone.max' => 'No telp tidak boleh lebih dari 13 karakter'
        ]);

        $outlet = Outlet::whereCode($id)->first();
        $outlet->update($request->except('code'));

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $outlet = Outlet::find($id);
        $outlet->delete();

        return response()->json(['status' => 'success'], 200);
    }
}
