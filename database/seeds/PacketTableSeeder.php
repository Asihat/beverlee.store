<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class PacketTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = <<<SQL
TRUNCATE TABLE packet;
SQL;

        DB::statement($sql);

        \App\Packet::create([
            'name' => 'Magic Pack',
            'description' => 'Some description',
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        \App\Packet::create([
            'name' => 'Кальций',
            'description' => 'Some description',
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        \App\Packet::create([
            'name' => 'Beauty Pack',
            'description' => 'Some description',
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        \App\Packet::create([
            'name' => 'Super Omega',
            'description' => 'Some description',
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        echo "Successfully added Packets\n";
    }
}
