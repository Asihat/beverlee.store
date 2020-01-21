<?php

namespace App\Http\Controllers;

use App\Packet;
use App\PacketProduct;
use App\Product;
use Illuminate\Http\Request;

class PacketController extends Controller
{
    public function index() {
        $packets = Packet::all();

        return view('pages.packets', ['packets' => $packets]);
    }

    public function addpacket() {

        $products = Product::all();

        return view('pages.add_packet', ['products' => $products]);
    }
    public function addpackets(Request $request) {

        $newPacket = new Packet();

        $newPacket -> name = $request -> input('name');
        $newPacket -> description = $request -> input('description');

        $newPacket -> save();

        $packet_products = PacketProduct::all();

        for($i=0;$i<count($packet_products);$i++) {
            if($request -> input('packet' . $i)) {
                $newPacketProduct = new PacketProduct();

                $newPacketProduct -> packet_id = $newPacket ->id;
                $newPacketProduct -> product_id = $i;
                $newPacketProduct -> amount = $request-> input('packet' . $i);

                $newPacketProduct -> save();
            }
        }

        return redirect('/packets');
    }
}
