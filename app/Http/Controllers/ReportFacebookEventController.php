<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ReportFacebookEventJob;

class ReportFacebookEventController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            ReportFacebookEventJob::dispatchNow([
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'url' => $request->input('url'),
            ], 'ViewContent');

            return response()->json(true);

        } catch (\Exception $e) {
            logger($e->getMessage());
        }

        return response()->json(false);
    }
}
