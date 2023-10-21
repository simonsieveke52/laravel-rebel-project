<?php

namespace App\Http\Controllers\Voyager;

use App\Product;
use App\Scopes\EnabledScope;
use Illuminate\Http\Request;
use App\Exports\OutputOrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;

class ProductHandlerController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $skus = Product::withoutGlobalScope(EnabledScope::class)
            ->where('status', false)
            ->get('sku');

        return view('vendor.voyager.product-handler.browse', [
            'skus' => $skus->pluck('sku')->implode(PHP_EOL)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'skus' => 'required',
        ]);
        
        collect(explode(PHP_EOL, $request->input('skus')))
            ->map(function($sku) {
                return trim($sku);
            })
            ->tap(function($collection) {

                Product::withoutGlobalScope(EnabledScope::class)
                    ->whereIn('sku', $collection->toArray())
                    ->update(['status' => false]);

                Product::withoutGlobalScope(EnabledScope::class)
                    ->whereNotIn('sku', $collection->toArray())
                    ->update(['status' => true]);
            });

        return redirect()->back()->with([
                'message'    => 'Products updated',
                'alert-type' => 'success',
            ]);
    }

    /**
     * @return view
     */
    public function exportProducts()
    {
        return view('vendor.voyager.product-handler.export');
    }

    /**
     * @return csv
     */
    public function export(Request $request)
    {
        $request->validate([
            'ids' => 'required',
        ]);

        $ids = collect(explode(PHP_EOL, $request->input('ids')))
            ->map(function($sku) {
                return trim($sku);
            });

        $date = date('Y-m-d-H-is');

        return Excel::download(new OutputOrdersExport($ids->toArray()), "orders-{$date}.csv");
    }
}
