<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Goods;
use App\Logging_goods;
use App\Payments;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;



class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $status;
    protected $start;
    protected $end;

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $payments = Payments::orderBy('updated_at','desc')->paginate(20);
        return view('home', ['payments' => $payments]);
    }

    public function add() {
        $product = Product::all();

        return view('product', ['product' => $product]);
    }

    public function adds(Request $request) {

        $request -> validate([
            'product_id' => 'required',
            'quantity' => 'required',
        ]);

        $id = $request->input('product_id');
        $quantity = $request->input('quantity');

        $goods = Goods::where('product_id', $id)->first();
        if ($goods) {
            $newAmount = $goods['total_amount'] + $quantity;

            Goods::where('product_id', $id)->update(['total_amount' => $newAmount]);

            $logging_good = new Logging_goods();

            $logging_good -> product_id = $id;
            $logging_good -> added_goods = $quantity;
            $logging_good -> description = "added goods";

            $logging_good -> save();

            return redirect()->back()->with(['status' => 'Товар добавлен']);
        } else {
            $good = Product::find($id);

            $newGood = new Goods;
            $newGood -> product_id = $id;
            $newGood -> total_amount = $quantity;
            $newGood -> save();

            $logging_good = new Logging_goods();

            $logging_good -> product_id = $id;
            $logging_good -> added_goods = $quantity;
            $logging_good -> description = "added goods";

            $logging_good -> save();
            return redirect()->back()->with(['status' => 'Товар добавлен']);

        }

    }

    public function search(Request $request) {
        $status = $request->input('status', 0);
        $start = $request->input('start', 0);
        $end = $request->input('end', 0);
        $search = $request->input('search');
        if ($search) {
            if (!$status && !$start && !$end) {
                return redirect()->back()->with(['search' => 'Выберите хотя бы один параметр']);
            }
        }

        if (!$start) {
            $start = '2018-01-01';
        }
        if (!$end) {
            $end = '2022-12-31';
        }
        if (!$status) {
            $payments = DB::table('payments')->select('*')
                ->whereBetween('updated_at', [$start,$end])
                ->paginate(20);
            $request->session()->put('status', null);
            $request->session()->put('start', $start);
            $request->session()->put('end', $end);
            return view('home', ['payments' => $payments])->with(['export'=>'export']);
        }

        $request->session()->put('status', $status);
        $request->session()->put('start', $start);
        $request->session()->put('end', $end);
        $payments = DB::table('payments')->select('*')
            ->where('status', '=', $status)
            ->where('updated_at', '>=', $start)
            ->where('updated_at', '<=', $end)
            ->paginate(20);

        return view('home', ['payments' => $payments])->with(['export'=>'export']);
    }

    public function report() {
        return view('report');
    }

    public function export(Request $request) {

        $status =  $request->session()->pull('status', 'default');
        $start =  $request->session()->pull('start', 'default');
        $end =  $request->session()->pull('end', 'default');

        return Excel::download(new ReportExport($status, $start, $end), 'payments.xlsx');
    }

    public function mark(Request $request) {
        $check = $request->input('check');
        if ($check != null) {
                $checked = DB::table('payments')->whereIn('id',$check)->update(['status'=>4]);
                                if ($checked != null) {
                    return redirect()->back()->with(['status' => 'Отмечен как отправлено']);
                }
        }
        $payments = Payments::paginate(10);
        return view('home',['payments' => $payments]);
    }
    public function newDesign() {
        return view('newDesign');
    }

    public function allProducts() {

        $goods = Goods::all();
        foreach ($goods as $good) {
            $product = Product::find($good -> product_id);
            $product_name = $product -> name;
            $good -> name = $product_name;
        }

        return view('pages.all_products', ['goods' => $goods]);
    }
}
