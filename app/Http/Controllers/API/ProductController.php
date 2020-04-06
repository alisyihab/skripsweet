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
}
