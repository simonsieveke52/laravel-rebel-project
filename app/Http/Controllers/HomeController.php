<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderOrigin;
use App\OrderProduct;
use Illuminate\Http\Request;
use App\Events\OrderCreateEvent;
use App\Jobs\ReportOrderFacebook;

class HomeController
{
    /**
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories = \App\Category::whereNotNull('homepage_order')
            ->orderBy('categories.homepage_order')
            ->get();

        $visitedAlready = session('visitedAlready');

        $origins = [];

        if (!session()->has('visitedAlready')) {
            session(['visitedAlready' => true]);
            $origins = OrderOrigin::all();
        }
        
        return view('front.home', compact('categories', 'visitedAlready', 'origins'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function privacy()
    {
        return view('front.pages.privacy-policy');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function terms()
    {
        return view('front.pages.terms-and-conditions');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function aboutUs()
    {
        return view('front.pages.about-us');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function faq()
    {
        return view('front.pages.faq');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('front.pages.contact-us');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function shippingReturnPolicty()
    {
        return view('front.pages.shipping-return-policy');
    }
}
