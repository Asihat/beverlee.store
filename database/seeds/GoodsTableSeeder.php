<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class GoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = <<<SQL
TRUNCATE TABLE goods;
SQL;

        DB::statement($sql);

        \App\Goods::create([
            'product_id' => 1,
            'total_amount' => 250,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        \App\Goods::create([
            'product_id' => 2,
            'total_amount' => 250,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        \App\Goods::create([
            'product_id' => 3,
            'total_amount' => 250,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        \App\Goods::create([
            'product_id' => 4,
            'total_amount' => 250,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        echo "Successfully added goods\n";
    }
}
