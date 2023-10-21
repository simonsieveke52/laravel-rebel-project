<?php

namespace App\Http\Controllers;

use App\{Product, UserList};
use Illuminate\Http\Request;
use DB;
use Mail;

class QuickorderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         //
         
         $listQuick = UserList::where('id', 1)->first();

        //  $productsQuick = $listQuick->products()->first();
        //  dd($productsQuick->pivot->quantity);
         $productsQuick = $listQuick->products()->getResults();
         $listLater = UserList::where('id', 2)->first();
         $productsLater = $listLater->products()->getResults();
        //  dd($products);
         $viewType = $request->get('view_type') ?? 'grid';
 
         $pidQ = array();
         foreach ($productsQuick as $product) {
            $pidQ[$product->id] = $product->pivot->quantity;
         }
        //  dd($pidQ);
         return view('front.pages.quick-order', 
             compact(
                 'productsQuick',
                 'pidQ',
                 'productsLater',
                 'viewType',
                 'listQuick',
                 'listLater',
 
             )
         );
    }

    /**
     * Process the request a quote form
     *
     * @return \Illuminate\Http\Response
     */
    // public function quoteRequest(Request $request)
    // {
    //     $listQuick = UserList::where('id', 1)->first();
    //     $productsQuick = $listQuick->products()->getResults();
    //     $pidQ = array();
    //     foreach ($productsQuick as $product) {
    //         $s = $product->id . ':' . $product->pivot->quantity;
    //         array_push($pidQ, $s);
    //     }
    //     dd($request->description);
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd($request->quantity);
        $product = Product::where('sku', $request->keyword)->orWhere('name', $request->keyword)->first();
        $listQuick = UserList::where('id', 1)->first();

        if($product){
            if(!$listQuick->products()->where('product_id', $product->id)->exists()){
                $listQuick->products()->attach($product);
                $listQuick->products()->where('product_id', $product->id)->update(['product_user_list.quantity' => $request->quantity]);
            }
        }
        
        $testList = $listQuick->products()->get();

        $productsQuick = $listQuick->products()->getResults();
        
        $listLater = UserList::where('id', 2)->first();
        $productsLater = $listLater->products()->getResults();
        $products = $listQuick->products()->getResults();
        
        $viewType = $request->get('view_type') ?? 'grid';
        $pidQ = array();
        foreach ($productsQuick as $product) {
           $pidQ[$product->id] = $product->pivot->quantity;
        }

        return view('front.pages.quick-order', 
            compact(
                'pidQ',
                'productsQuick',
                'productsLater',
                'viewType',
                'listQuick',
                'listLater',

            )
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($package, Request $request)
    {
        $packageLocal = explode(",", $package);
        $id = $packageLocal[0];
        $listNumber = $packageLocal[1];
        
        if($listNumber == 1){
            DB::delete('delete from product_user_list where product_id = ? and user_list_id = 1',[$id]);
        }elseif($listNumber == 2){
            DB::delete('delete from product_user_list where product_id = ? and user_list_id = 2',[$id]);
        }
        $listQuick = UserList::where('id', 1)->first();
        $productsQuick = $listQuick->products()->getResults();
        $listLater = UserList::where('id', 2)->first();
        $productsLater = $listLater->products()->getResults();
       //  dd($products);
        $viewType = $request->get('view_type') ?? 'grid';
        $pidQ = array();
        foreach ($productsQuick as $product) {
           $pidQ[$product->id] = $product->pivot->quantity;
        }

        return view('front.pages.quick-order', 
            compact(
                'pidQ',
                'productsQuick',
                'productsLater',
                'viewType',
                'listQuick',
                'listLater',

            )
        );
    }


    public function move($package, Request $request)
    {
        //
        $listQuick = UserList::where('id', 1)->first();
        $listLater = UserList::where('id', 2)->first();
        
        $packageLocal = explode(",", $package);
        $id = $packageLocal[0];
        $listNumber = $packageLocal[1];
        $product = Product::where('id', $id)->orWhere('name', $request->keyword)->first();

            if($listNumber == 1 && !$listLater->products()->where('product_id', $id)->exists()){
                DB::delete('delete from product_user_list where product_id = ? and user_list_id = 1',[$id]);
                $listLater->products()->attach($product);
            }elseif($listNumber == 2 && !$listQuick->products()->where('product_id', $id)->exists()){
                DB::delete('delete from product_user_list where product_id = ? and user_list_id = 2',[$id]);
                $listQuick->products()->attach($product);
            }

        $productsQuick = $listQuick->products()->getResults();
        $productsLater = $listLater->products()->getResults();

        $viewType = $request->get('view_type') ?? 'grid';
        $pidQ = array();
        foreach ($productsQuick as $product) {
           $pidQ[$product->id] = $product->pivot->quantity;
        }

        return view('front.pages.quick-order', 
            compact(
                'pidQ',
                'productsQuick',
                'productsLater',
                'viewType',
                'listQuick',
                'listLater',

            )
        );
    }
}
