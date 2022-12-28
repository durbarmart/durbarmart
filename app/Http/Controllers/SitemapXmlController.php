<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;


class SitemapXmlController extends Controller
{
    public function index() {
        // $categories = Category::all();with('subCategoriesWithSubSub')->
        $categories = Category::get(['id','name','slug']);
        $products = Product::get(['name','slug']);
        // dd($products);
        return response()->view('sitemap', [
            'categories' => $categories,
            'products' => $products,

        ])->header('Content-Type', 'text/xml');
      }
}
