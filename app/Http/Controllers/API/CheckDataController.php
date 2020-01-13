<?php

namespace App\Http\Controllers\API;

use App\Goods;
use App\Http\Controllers\Controller;
use App\Packet;
use Illuminate\Http\Request;

class CheckDataController extends Controller
{
    public function check() {
        $packet_id_array = [1];
        $goods = Goods::all();
        foreach ($packet_id_array as $packet_id){
            if(Packet::find($packet_id)){

                return "Packet found\n" . $goods;
            } else {
                return "Packet not found\n" . Packet::find($packet_id);
            }
        }

        return 'true';
    }
}
