<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ProductCollection;
use App\LaundryPrice;
use App\LaundryType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * @return ProductCollection
     */
    public function index()
    {
        $products = LaundryPrice::with(['type', 'user'])->orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $products = $products->where('name', 'LIKE', '%' . request()->q . '%');
        }
        $products = $products->paginate(10);

        return new ProductCollection($products);
    }

    /**
     * @return JsonResponse
     */
    public function getLaundryType()
    {
        $type = LaundryType::orderBy('name', 'ASC')->get();

        return response()->json([
            'status' => 'success',
            'data' => $type
        ]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws ValidationException
     */
    public function storeLaundryType(Request $request)
    {
        $this->validate($request, [
            'name_laundry_type' => 'required|unique:laundry_types,name'
        ], [
            'name_laundry_type.required' => 'Field tidak boleh kosong',
            'name_laundry_type.unique' => 'Nama sudah terdaftar!'
        ]);

        LaundryType::create([
            'name' => $request->name_laundry_type
        ]);

        return response()->json([
            'status' => 'success'
        ], 200);
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
            'name' => 'required|string|max:100',
            'unit_types' => 'required',
            'price' => 'required|integer',
            'laundry_type' => 'required',
        ], [
            'name.required' => 'Field tidak boleh kosong',
            'name.max' => 'Tidak boleh melebihi 100 karakter',
            'price.required' => 'Field tidak boleh kosong',
            'laundry_type.required' => 'Field tidak boleh kosong',
            'unit_types.required' => 'Field tidak boleh kosong'
        ]);

        try {
            LaundryPrice::create([
                'name' => $request->name,
                'unit_types' => $request->unit_types,
                'laundry_type_id' => $request->laundry_type,
                'price' => $request->price,
                'user_id' => auth()->user()->id,
            ]);
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'data' => $e->getMessage()
            ]);
        }
    }
}
