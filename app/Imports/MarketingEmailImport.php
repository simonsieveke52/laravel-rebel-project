<?php

namespace App\Imports;

use App\MarketingEmail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class MarketingEmailImport implements ToCollection
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

    		$name = explode(' ', $row[1]);
    		$firstName = array_shift($name);
    		$lastName = implode(' ', $name);

    		MarketingEmail::create([
    			'code' => $row[0],
    			'email' => $row[2], 
    			'first_name' => $firstName, 
    			'last_name' => $lastName, 
    			'name' => $row[1], 
    			'phone' => preg_replace('/[^0-9]/', '', $row[3])
    		]);
    	});
    }
}