<?php

namespace App\Http\Controllers;

use App\Packet;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $products = Product::all();

        return view('pages.products', ['products' => $products]);
    }
}
