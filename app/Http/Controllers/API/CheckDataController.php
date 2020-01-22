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


        $token = $request -> token;
//        dd($token);
        $packet_ids = $request -> packet_ids;

        $item_ids = "";

        $packets = $this->packets_string_to_array($packet_ids);

        $items = $this->items_string_to_array($item_ids);

        $order_id = $request->order_id;

        $goods = Goods::all();
        $total_products = [];

        $newToken = DB::table("tokens")->where('token','=',$token)->first();
//        dd($newToken->token);
        if ($newToken) {
            $payment = Payments::where('order_id', $order_id)->get();

            if (count($payment) > 0) {
                return 'You have some error with orderID';
            } else {
                foreach ($packets as $packet_id => $amount) {
                    if (Packet::find($packet_id)) {
                        for ($i = 0; $i < $amount; $i++) {
                            $products = DB::table('packet')
                                ->join('packet_product', 'packet.id', '=', 'packet_product.packet_id')
                                ->join('product', 'packet_product.product_id', '=', 'product.id')
                                ->where('packet.id', '=', $packet_id)
                                ->get();

                            foreach ($products as $product) {
                                array_push($total_products, $product);
                            }
                        }
                    } else {
                        return "Packet not found\n" . Packet::find($packet_id);
                    }
                }

                foreach ($items as $item_id => $amount) {
                    $product = Product::find($item_id);
                    for ($i = 0; $i < $amount; $i++) {
                        array_push($total_products, $product);
                    }
                }

                foreach ($total_products as $product) {
                    foreach ($goods as $good) {

                        if ($product->id == $good->product_id) {
                            if (!($product->amount > 0) AND $good->total_amount > 0) { /// item used
                                $good->total_amount--;
                            } else if ($good->total_amount >= $product->amount) {
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


                $newPayment = new Payments();

                $newPayment->data = $packet_ids;

                $newPayment->status = 1; // Status wait 15 min
                $description = $this->description($packet_ids);
                $newPayment->description = $this->subArraysToString($description, ', ');

                $newPayment->created_at = Carbon::now();
                $newPayment->updated_at = Carbon::now();

                $newPayment->order_id = $order_id;

                $newPayment->save();

                return 'success';
            }
        } else {
            return $newToken;
        }


    }

    public function change(Request $request) {

        $order_id = $request->order_id;
        $customer_id = $request->customer_id;
        if ($order_id) {
            $payment = Payments::where('order_id', $order_id)->get();
            if (count($payment) > 0 and count($payment) < 2) { //
                Payments::where('order_id', '=', $order_id)
                    ->update(['status' => 2]);
                return 'success';
            } else {
                return "There is some error with counts payment";
            }
        } else {
            return "No order_id";
        }
    }

    public function test() {
        $sql = <<<SQL
    SELECT * FROM `payments` WHERE TIMESTAMPDIFF(MINUTE,created_at,NOW()) > 20 AND status = 1
SQL;
        $goods = Goods::all();

        $notActivePayments = DB::select($sql);

        if(count($notActivePayments) == 0) {
            return "There is no not active payments";
        }

        $packet = "";
        foreach ($notActivePayments as $payment) {
            $packet = $payment -> data;
            $packets = $this->packets_string_to_array($packet);
            $total_products = [];
            foreach ($packets as $packet_id => $amount) {
                if (Packet::find($packet_id)) {
                    for ($i = 0; $i < $amount; $i++) {
                        $products = DB::table('packet')
                            ->join('packet_product', 'packet.id', '=', 'packet_product.packet_id')
                            ->join('product', 'packet_product.product_id', '=', 'product.id')
                            ->where('packet.id', '=', $packet_id)
                            ->get();

                        foreach ($products as $product) {
                            array_push($total_products, $product);
                        }
                    }
                } else {
                    return "Packet not found\n" . Packet::find($packet_id);
                }
            }
        }

        foreach ($total_products as $product) {
            foreach ($goods as $good) {

                if ($product->id == $good->product_id) {

                    $good -> total_amount = $good -> total_amount + $product -> amount;

                    foreach ($goods as $item) {
                        $good = Goods::find($item->id);

                        $good->total_amount = $item->total_amount;

                        $good->save();
                    }

                }
            }
        }

        // Update unactive payments
        $sql = <<<SQL
    UPDATE `payments` SET status = 4 WHERE TIMESTAMPDIFF(MINUTE,created_at,NOW()) > 20 AND status = 1
SQL;
        $notActivePayments = DB::update($sql);
        return "Successfully updated not active payments\n";
    }

    public function packets_string_to_array($packet_ids) {
        $packets = [];

        $packet_key = "";
        $number = "";

        $key_or_amount = "key";

        for ($i = 0; $i < strlen($packet_ids); $i++) {
            if (is_numeric($packet_ids[$i]) AND $i == strlen($packet_ids) - 1) {
                $number = $number . $packet_ids[$i];

                $packets[$packet_key] = $number;
                break;
            } else if (is_numeric($packet_ids[$i]) AND $key_or_amount == "key") {
                $packet_key = $packet_key . $packet_ids[$i];

            } else if ($packet_ids[$i] == "=" || $packet_ids[$i] == ">") {
                $key_or_amount = "number";
            } else if (is_numeric($packet_ids[$i]) AND $key_or_amount == "number") {
                $number = $number . $packet_ids[$i];
            } else if ($packet_ids[$i] == ",") {

                $key_or_amount = "key";
                $packets[$packet_key] = $number;
                $packet_key = "";
                $number = "";
            } else {
                return "You have some error with ordering packets";
            }
        }

        return $packets;
    }
    public function items_string_to_array($item_ids) {
        $items = [];

        $item_key = "";
        $length_of_key = 0;

        $number = "";
        $length_of_number = 0;

        $key_or_amount = "key";

        for ($i = 0; $i < strlen($item_ids); $i++) {
            if (is_numeric($item_ids[$i]) AND $i == strlen($item_ids) - 1) {
                $number = $number . $item_ids[$i];

                $items[$item_key] = $number;
                break;
            } else if (is_numeric($item_ids[$i]) AND $key_or_amount == "key") {
                $item_key = $item_key . $item_ids[$i];
                $length_of_key++;
            } else if ($item_ids[$i] == "=" || $item_ids == ">") {
                $key_or_amount = "number";
            } else if (is_numeric($item_ids[$i]) AND $key_or_amount == "number") {
                $number = $number . $item_ids[$i];
                $length_of_number++;
            } else if ($item_ids[$i] == ",") {

                $key_or_amount = "key";
                $items[$item_key] = $number;
                $item_key = "";
                $number = "";
                $length_of_number = 0;
                $length_of_key = 0;
            }
        }

        return $items;
    }

    public function subArraysToString($ar, $sep = ' ') {
        $str = '';
        foreach ($ar as $val) {
            $str .= implode($val);
            $str .= $sep; // add separator between sub-arrays
        }
        $str = rtrim($str, $sep); // remove last separator
        return $str;
    }
    public function description($packet_id_array) {
        $desc = explode(",", $packet_id_array);

        $explode = array();
        for ($i = 0; $i < count($desc); $i++) {
            array_push($explode, explode("=>", $desc[$i]));
        }

        $description = array();
        for ($j = 0; $j < count($explode); $j++) {
            $pro = Packet::find($explode[$j][0])->get();
            array_push($description, array($explode[$j][1], " штук ", $pro[$j]['name']));
        }

        return $description;
    }

}



