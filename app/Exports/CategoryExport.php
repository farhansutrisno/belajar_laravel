<?php

namespace App\Exports;

use App\Models\Category;
use Illuminate\Contracts\View\View;
//use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class CategoryExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $dataCategory = Category::all();
        return view('kategori.cetak', ['dataKategori' => $dataCategory]);
    }

    // public function collection()
    // {
    //     $dataCategory = Category::all();
    //     return $dataCategory;
    // }
}
