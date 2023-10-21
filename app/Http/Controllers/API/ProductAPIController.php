<?php

namespace App\Http\Controllers\API;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Symfony\Component\Console\Input\Input;

class ProductAPIController extends Controller
{
    public function show(Request $request)
    {
        $product = Product::where('sku', $request->sku)->first();
        if ($product) {
            return new ProductResource($product);
        }
        
        return response('Product not found', 404);
    }
}
