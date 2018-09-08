<?php

namespace App\Http\Controllers;

use App\Models\Product;

class IndexController extends Controller
{

    public function index()
    {
        $products = Product::orderByDesc('created_at')->paginate(12);
        return view('index', compact('products'));
    }

}
