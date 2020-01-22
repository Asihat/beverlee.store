<?php

namespace App\Http\Controllers;

use App\Packet;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $products = Product::all();

        return view('pages.products', ['products' => $products]);
    }

    public function addProduct() {
        return view('pages.add_product');
    }

    public function addProducts(Request $request) {
        $newProduct = new Product();

        $request -> validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $newProduct -> name = $request -> input('name');
        $newProduct -> description = $request -> input('description');

        $newProduct -> save();

        return redirect('/products');
    }
}
