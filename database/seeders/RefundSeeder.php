<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RefundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Refund::factory(10)->create();
    }
}
