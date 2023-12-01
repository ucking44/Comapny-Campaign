<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LslLoyaltyProgramMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lsl_loyalty_program_master')->insert([
            [
                'Loyalty_program_name' => 'Grand Square Ikeja',
                'Loyalty_program_logo' => 'The grand square at the state capital',
                'Company_id'           => 1
            ],
            [
                'Loyalty_program_name' => 'Grand Square Victoria',
                'Loyalty_program_logo' => 'The grand square at Victoria Island Lagos',
                'Company_id'           => 2
            ],
            [
                'Loyalty_program_name' => 'Grand Square Abuja',
                'Loyalty_program_logo' => 'The grand square at the Federal capital Abuja',
                'Company_id'           => 1
            ],
        ]);
    }
}
