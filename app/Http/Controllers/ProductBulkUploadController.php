<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\SubCategory;
use App\SubSubCategory;
use App\Brand;
use App\User;
use Auth;
use App\ProductsImport;
use App\ProductsExport;
use App\CategoriesImport;
use App\Seller;
use App\SellersImport;

use PDF;
use Excel;
use Illuminate\Http\Response;

class ProductBulkUploadController extends Controller
{
    public function index()
    {
        if (Auth::user()->user_type == 'seller') {
            return view('frontend.seller.product_bulk_upload.index');
        }
        elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('bulk_upload.index');
        }
    }

    public function export(){
        $filePath = public_path("download/Example-Product.csv");
        return \Response::download($filePath);
        // return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function pdf_download_category()
    {
        $categories = Category::all();
        $pdf = PDF::setOptions([
                        'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                        'logOutputFile' => storage_path('logs/log.htm'),
                        'tempDir' => storage_path('logs/')
                    ])->loadView('downloads.category', compact('categories'));

        return $pdf->download('category.pdf');
    }

    public function pdf_download_sub_category()
    {
        $sub_categories = Subcategory::all();
        $pdf = PDF::setOptions([
                        'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                        'logOutputFile' => storage_path('logs/log.htm'),
                        'tempDir' => storage_path('logs/')
                    ])->loadView('downloads.sub_category', compact('sub_categories'));

        return $pdf->download('sub_category.pdf');
    }

    public function pdf_download_sub_sub_category()
    {
        $sub_sub_categories = SubSubCategory::all();
        $pdf = PDF::setOptions([
                        'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                        'logOutputFile' => storage_path('logs/log.htm'),
                        'tempDir' => storage_path('logs/')
                    ])->loadView('downloads.sub_sub_category', compact('sub_sub_categories'));

        return $pdf->download('sub_sub_category.pdf');
    }

    public function pdf_download_brand()
    {
        $brands = Brand::all();
        $pdf = PDF::setOptions([
                        'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                        'logOutputFile' => storage_path('logs/log.htm'),
                        'tempDir' => storage_path('logs/')
                    ])->loadView('downloads.brand', compact('brands'));
        return $pdf->download('brands.pdf');
    }

    public function pdf_download_seller()
    {
        $users = User::where('user_type','seller')->get();
        $pdf = PDF::setOptions([
                        'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                        'logOutputFile' => storage_path('logs/log.htm'),
                        'tempDir' => storage_path('logs/')
                    ])->loadView('downloads.user', compact('users'));

        return $pdf->download('user.pdf');

    }

    public function bulk_category_upload(Request $request)
    {
        if($request->hasFile('bulk_file')){
            Excel::import(new CategoriesImport, request()->file('bulk_file'));
        }
        flash('Categories exported successfully')->success();
        return back();
    }

    public function bulk_seller_upload(Request $request)
    {
        if($request->hasFile('bulk_file')){
            Excel::import(new SellersImport, request()->file('bulk_file'));
        }
        flash('Sellers exported successfully')->success();
        return back();
    }


    // public function bulk_upload(Request $request)
    // {
    //     dd(request()->file('bulk_file'));
    //     if($request->hasFile('bulk_file')){
    //         $a = Excel::import(new ProductsImport, request()->file('bulk_file'));
    //     }
    //     flash('Products exported successfully')->success();
    //     return back();
    // }
    public function bulk_upload(Request $request)
    {
        // dd($request->file());
    //    $files = Scanner::scanner_getFile();
    $all_files = $_FILES['bulk_file'];
    // $temp_name = $all_files["name"];
    // $file_name = pathinfo($temp_name,PATHINFO_FILENAME);
    // dd($request->all());
        if($request->hasFile('bulk_file')){
            foreach($request->file() as $files){
                foreach($files as $file){
                    $a = Excel::import(new ProductsImport, $file);
                }
                
            }
        }
        flash('Products exported successfully')->success();
        return back();
    }

}
