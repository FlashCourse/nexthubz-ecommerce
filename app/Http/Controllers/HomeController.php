<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::take(12)->get();
        $products = Product::take(8)->get();
        return view('index', compact('products', 'categories'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function privacyPolicy()
    {
        return view('privacy-policy');
    }
    public function categories()
    {
        $categories = Category::paginate(10);

        return view('categories', compact('categories'));
    }
}
