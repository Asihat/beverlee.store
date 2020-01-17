<?php

namespace App\Http\Controllers\API;

use App\Goods;
use App\Http\Controllers\Controller;
use App\Packet;
use App\Payments;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckDataController extends Controller {
    public function check(Request $request) {
        $packet_id_array = $request->packet_id;
        $order_id = $request->input('order_id');

        $goods = Goods::all();
        $total_products = [];

        $payment = Payments::where('order_id', $order_id)->get();
        if (count($payment) > 0) {
            return 'false';
        } else {
            foreach ($packet_id_array as $packet_id) {
                if (Packet::find($packet_id)) {
                    $products = DB::table('packet')
                        ->join('packet_product', 'packet.id', '=', 'packet_product.packet_id')
                        ->join('product', 'packet_product.product_id', '=', 'product.id')
                        ->where('packet.id', '=', $packet_id)
                        ->get();

                    foreach ($products as $product) {
                        array_push($total_products, $product);
                    }
                } else {
                    return "Packet not found\n" . Packet::find($packet_id);
                }
            }

            foreach ($total_products as $product) {
                foreach ($goods as $good) {
                    if ($product->id == $good->id) {
                        if ($good->total_amount >= $product->amount) {
                            $good->total_amount = $good->total_amount - $product->amount;
                        } else {
                            return "not enough goods at Store";
                        }
                    }
                }
            }
            foreach ($goods as $item) {
                $good = Goods::find($item->id);

                $good->total_amount = $item->total_amount;

                $good->save();
            }

            $goodsAfter = Goods::all();

            $newPayment = new Payments();

            $newPayment->data = $packet_id_array;
            $newPayment->status = 1; // Status wait 15 min
            $description = description($packet_id_array);
            $newPayment->description = subArraysToString($description, " ");

            $newPayment->created_at = Carbon::now();
            $newPayment->updated_at = Carbon::now();
            $newPayment->order_id = $order_id;
            $newPayment->save();

            return 'success';
        }


//    }
    }

    function subArraysToString($ar, $sep = ' ') {
        $str = '';
        foreach ($ar as $val) {
            $str .= implode($sep, $val);
            $str .= $sep; // add separator between sub-arrays
        }
        $str = rtrim($str, $sep); // remove last separator
        return $str;
    }

    function description($packet_id_array) {
        $desc = explode(",", $packet_id_array);

        $explode = array();
        for ($i = 0; $i < count($desc); $i++) {
            array_push($explode, explode("=>", $desc[$i]));
        }

        $description = array();
        for ($j = 0; $j < count($explode); $j++) {
            $pro = Product::find($explode[$j][0])->get();
            array_push($description, array($explode[$j][1], $pro[$j]['name']));
        }

        return $description;
    }
