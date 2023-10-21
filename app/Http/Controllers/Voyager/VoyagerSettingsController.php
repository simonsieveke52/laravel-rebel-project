<?php

namespace App\Http\Controllers\Voyager;

use App\Page;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use App\Listeners\ClearCacheListener;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class VoyagerSettingsController extends VoyagerBaseController
{
    /**
     * @return redirect Response
     */
	public function clearCache()
    {
        (new ClearCacheListener)->handle();

        return redirect()->back()->with([
            'message'    => 'Cache cleared',
            'alert-type' => 'success',
        ]);
    }
}