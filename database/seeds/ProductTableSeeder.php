<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class ProductTableSeeder extends Seeder
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

        \App\Product::create([
            'name' => 'БАД Коралловый кальций',
            'description' => 'Some description',
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        \App\Product::create([
            'name' => 'БАД Коэнзим QH',
            'description' => 'Some description',
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        \App\Product::create([
            'name' => 'все в одном Double Luxury',
            'description' => 'Some description',
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        \App\Product::create([
            'name' => 'Биологические активные добавки к пище: Super Omega',
            'description' => 'Some description',
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        echo "Successfully added Products\n";
    }
}
