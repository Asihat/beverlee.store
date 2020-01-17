<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class PacketProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = <<<SQL
TRUNCATE TABLE product;
SQL;

        DB::statement($sql);

        \App\PacketProduct::create([
            'packet_id' => 1,
            'product_id' => 1,
            'amount' => 6,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);


        \App\PacketProduct::create([
            'packet_id' => 1,
            'product_id' => 2,
            'amount' => 3,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        \App\PacketProduct::create([
            'packet_id' => 2,
            'product_id' => 1,
            'amount' => 12,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        \App\PacketProduct::create([
            'packet_id' => 3,
            'product_id' => 3,
            'amount' => 4,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        echo "Successfully added Products\n";
    }
}
