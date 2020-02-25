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
        $token = $request->token;
        $packets = $request->packets;
        $result['success'] = false;

        if (!$token) {
            $result['error'] = "Token error. Token required";
            return response()->json($result);
        }

        if (!$packets) {
            $result['error'] = "Packet Ids required";
            return response()->json($result);
        }

        $item_ids = "";
        // 1=>1,2=>1,3=>3,3=>12
        $packets = $this->packets_string_to_array($packets);

        $items = $this->items_string_to_array($item_ids);

        $order_id = $request->order_id;

        $goods = Goods::all();
        $total_products = [];
        $newToken = md5($packets . $order_id);

        if ($newToken == $token) {
            $payment = Payments::where('order_id', $order_id)->get();

            if (count($payment) > 0) {
                $result['error'] = "You have some error with orderID";

                return response()->json($result);
            } else {
                info($packets); # TODO remove

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
                        $result['error'] = "Packet not found\n" . "This Packed Id not found: " . $packet_id;
                        return response()->json($result);
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
                                $result['error'] = "not enough goods at Store";

                                return response()->json($result);
                            }
                        }
                    }
                }

                foreach ($goods as $item) {
                    Goods::where('id', $item->id)->update([
                        'total_amount' => $item->total_amount,
                    ]);
                }

                $description = $this->description($packets);
                info($description); # TODO remove

                Payments::create([
                    'created_at' => Carbon::now(),
                    'data' => $packets,
                    'description' => $this->subArraysToString($description, ', '),
                    'order_id' => $order_id,
                    'status' => 1,
                    'updated_at' => Carbon::now(),
                ]);

                $result['success'] = true;
                return response()->json($result);
            }
        } else {
            $result['error'] = "Incorrect token";

            return response()->json($result);
        }


    }

    public function change(Request $request) {

        $order_id = $request->order_id;
        $result['success'] = false;

        if ($order_id) {
            $payment = Payments::where('order_id', $order_id)->get();
            info($payment); #TODO remove
            if (count($payment) > 0 and count($payment) < 2) { //
                Payments::where('order_id', '=', $order_id)
                    ->update(['status' => 2]);

                $result['error'] = "There is no error";
                $result['success'] = true;
                return response()->json($result);
            } else {
                $result['error'] = "There is some error with counts payment";
                return response()->json($result);
            }
        } else {
            $result['error'] = "No order_id";
            return response()->json($result);
        }
    }

    public function test() {
        $sql = <<<SQL
    SELECT * FROM `payments` WHERE TIMESTAMPDIFF(MINUTE,created_at,NOW()) > 20 AND status = 1
SQL;
        $goods = Goods::all();

        $notActivePayments = DB::select($sql);

        if (count($notActivePayments) == 0) {
            return "There is no not active payments";
        }

        $packet = "";
        foreach ($notActivePayments as $payment) {
            $packet = $payment->data;
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

                    $good->total_amount = $good->total_amount + $product->amount;

                    foreach ($goods as $item) {
                        Goods::where('id', $item->id)->update(['total_amount' => $item->total_amount]);
                    }

                }
            }
        }

        // Update unactive payments
        $sql = <<<SQL
    UPDATE `payments` SET status = 4 WHERE TIMESTAMPDIFF(MINUTE,created_at,NOW()) > 20 AND status = 1;
SQL;
        info($sql); # TODO remove;
        DB::update($sql);
        return "Successfully updated not active payments\n";
    }

    public function packets_string_to_array($packets) {
        $packet = [];

        $packet_key = "";
        $number = "";

        $key_or_amount = "key";

        for ($i = 0; $i < strlen($packets); $i++) {
            if (is_numeric($packets[$i]) AND $i == strlen($packets) - 1) {
                $number = $number . $packets[$i];

                $packet[$packet_key] = $number;
                break;

            } else if (is_numeric($packets[$i]) AND $key_or_amount == "key") {
                $packet_key = $packet_key . $packets[$i];

            } else if ($packets[$i] == "=" || $packets[$i] == ">") {
                $key_or_amount = "number";

            } else if (is_numeric($packets[$i]) AND $key_or_amount == "number") {
                $number = $number . $packets[$i];

            } else if ($packets[$i] == ",") {

                $key_or_amount = "key";
                $packet[$packet_key] = $number;
                $packet_key = "";
                $number = "";
            } else {
                return "You have some error with ordering packets";
            }
        }

        return $packet;
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
        info($items); #TODO remove;
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



