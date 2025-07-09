<?php

namespace App\Imports\Controlesc;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TrasladosImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $row)
        {
            //dd('1: '. $row[0]);
        }
        
    }
}
