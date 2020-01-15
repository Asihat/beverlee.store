<?php

namespace App\Http\Controllers;

use App\Goods;
use App\Packet;
use App\Payments;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $payments = Payments::all();

        return view('home', ['payments' => $payments]);
    }

    public function add() {
        $product = Product::all();

        return view('product', ['product' => $product]);
    }

    public function adds(Request $request) {
        $id = $request->input('product_id');
        $quantity = $request->input('quantity');

        $goods = Goods::where('product_id', $id)->first();
        $newAmount = $goods['total_amount'] + $quantity;

        Goods::where('product_id', $id)->update(['total_amount' => $newAmount]);

        return redirect()->back()->with(['status' => 'Товар добавлен']);
    }

    public function search(Request $request) {
        $status = $request->input('status', 0);
        $start = $request->input('start', 0);
        $end = $request->input('end', 0);
        if (!$status) {
            $status = array('1','2','3','4');
        }
        if (!$start) {
            $start = '2018-01-01';
        }
        if  (!$end) {
            $end = '2022-12-31';
        }
        $payments = DB::table('payments')->select('*')->where('status', '=', $status)
            ->where('updated_at', '>=', $start)->where('updated_at', '<=', $end)
            ->get();


        return view('home', ['payments' => $payments]);
    }
}
