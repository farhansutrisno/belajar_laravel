<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\CategoryExport;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class categoryController extends Controller
{
    public function index(Request $request)
    {
        //medapatkan semua data category
        $category = Category::all();
        return view('kategori.index',['dataKategori' => $category]);
    }


    public function store(Request $request)
    {
        //kita gunakan laravel laravel eloquent untuk update dan create agar lebih mudah
        //jadi jika request ada id maka akan melakukan update
        Category::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'description' => $request->description
            ]
        );

        return response()->json(['success' => 'Category saved successfully.']);
    }


    public function edit($id)
    {
        //mengambil data sesuai id
        $category = Category::find($id);
        return response()->json($category);
    }


    public function destroy($id)
    {
        //delete sow
        Category::find($id)->delete();
        return response()->json(['success'=>'Category deleted successfully.']);
    }

    public function cetak()
    {
        $dataKategori = Category::all();
        $qrCode = QrCode::size(80)->generate('https://example.com');
        // var_dump($dataKategori);
        //die($qrCode);

        $pdf = \PDF::loadview('kategori.cetak', ['dataKategori' => $dataKategori,'qrcode' => $qrCode]);
        return $pdf->download('laporan-kategori.pdf');
    }

    public function export_excel()
	{
		return Excel::download(new CategoryExport, 'Category.xlsx');
	}
}
