<?php

namespace App\Http\Controllers;

use App\Packet;
use App\PacketProduct;
use Illuminate\Http\Request;

class PacketController extends Controller
{
    public function index() {
        $packets = Packet::all();

        return view('pages.packets', ['packets' => $packets]);
    }

    public function addpacket() {
        return view('pages.add_packet');
    }
    public function addpackets(Request $request) {

        $newPacket = new Packet();

        $newPacket -> name = $request -> input('name');
        $newPacket -> description = $request -> input('description');

        $newPacket -> save();

        $packet_ids = $request -> input('product_ids');
        $myArray = explode(',', $packet_ids);

        foreach ($myArray as $item) {
            $new_packet_product = new PacketProduct();
            $new_packet_product -> packet_id = $newPacket -> id;
            $new_packet_product -> product_id = $item;
            $new_packet_product -> amount = 3;

            $new_packet_product -> save();
        }

        return redirect('/packets');
    }
}
