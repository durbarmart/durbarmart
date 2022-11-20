<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;


class SitemapXmlController extends Controller
{
    public function index() {
        // $categories = Category::all();
        $categories = Category::with('subCategoriesXml')->get(['id','name','slug']);
        $products = Product::get('name','slug');
        // dd($products);
        return response()->view('sitemap', [
            'categories' => $categories,
            'products' => $products,

        ])->header('Content-Type', 'text/xml');
      }
}
