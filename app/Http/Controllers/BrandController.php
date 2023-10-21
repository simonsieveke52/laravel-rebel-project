<?php

namespace App\Http\Controllers;

use App\Brand;
use Illuminate\Http\Request;
use App\Repositories\BrandRepository;

class BrandController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        $products = $brand->products()->with('images')->paginate();

        return view('front.brands.list', [
            'brand' => $brand,
            'products' => $products,
        ]);
    }
}
