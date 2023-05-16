<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ValidacionCarteraImport implements ToCollection
{

    public function collection(Collection $collection)
    {
       // if($collection[0]->size)
       // echo $currentRowNumber .' '.$collection[0] . ' ' . $collection[1];


        dd($collection[0][0]);
    }
}
