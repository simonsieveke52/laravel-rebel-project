<?php

namespace App\Imports;

use App\Zipcode;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportZipcodeInventoryList implements ToCollection
{
	use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $collection->each(function($row, $index) {

    		if ($index == 0) {
    			return true;
    		}

    		try {
    			try {
	    			$zipcode = Zipcode::where('name', $row[0])->firstOrFail();
		    		$zipcode->update([
			    		'illinois' => is_int($row[1]) ? $row[1] : 0,
			            'maryland' => is_int($row[2]) ? $row[2] : 0,
			            'modesto' => is_int($row[3]) ? $row[3] : 0,
			            'oklahoma' => is_int($row[4]) ? $row[4] : 0,
			            'burley' => is_int($row[5]) ? $row[5] : 0,
			            'arizona' => is_int($row[6]) ? $row[6] : 0
		    		]);
	    		} catch (\Exception $e) {
	    			Zipcode::create([
	    				'name' => $row[0],
	    				'illinois' => is_int($row[1]) ? $row[1] : 0,
			            'maryland' => is_int($row[2]) ? $row[2] : 0,
			            'modesto' => is_int($row[3]) ? $row[3] : 0,
			            'oklahoma' => is_int($row[4]) ? $row[4] : 0,
			            'burley' => is_int($row[5]) ? $row[5] : 0,
			            'arizona' => is_int($row[6]) ? $row[6] : 0
	    			]);
	    		}	
    		} catch (\Exception $e) {
    			logger($e->getMessage());
    		}
    	});
    }
}
