<?php

namespace App\Http\Controllers;

use App\Order;
use App\UserFile;
use App\OrderProduct;
use Illuminate\Http\Request;
use App\Exports\OutputOrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OutputFrozenOrdersExport;
use App\Exports\OutputRegularOrdersExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportOrdersController
{
	/**
	 * @return string
	 */
    public function index(Request $request)
    {
        if (! in_array($request->input('export-type'), ['regular', 'frozen', 'bulk'])) {
            abort(404);
        }

        $file = UserFile::where('name', $request->input('fileName'))
            ->where('file_type', 'orders-export')
            ->firstOrFail();

        return response()->download(
            storage_path("app/public/csv/{$file->name}"), $file->name
        );
    }
}
