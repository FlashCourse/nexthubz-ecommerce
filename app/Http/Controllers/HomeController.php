<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slide;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::take(12)->get();
        $products = Product::take(8)->get();

        $slides = Slide::where('active', true)->get();
        return view('index', compact('products', 'categories', 'slides'));
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
        // Fetch all categories
        $categories = Category::with('children')->get();

        return view('categories', compact('categories'));
    }

    public function selectCheckout()
    {
        return view('select-checkout');
    }
}
