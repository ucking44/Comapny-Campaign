<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LslTransactionTypeMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lsl_transaction_type_master')->insert([
            [
                'Transaction_type_name' => 'Transaction With Loyalty',
            ],
            [
                'Transaction_type_name' => 'Transaction Without Loyalty',
            ],
        ]);
    }
}
