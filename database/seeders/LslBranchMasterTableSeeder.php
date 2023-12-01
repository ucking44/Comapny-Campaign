<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LslBranchMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lsl_branch_master')->insert([
            [
                'branch_name' => 'Grand Square Victoria',
                'branch_code' => 'GSV',
                'Company_id'  => 1,
                'Create_User_id' => 1,
                'Partner_id'  => 1
            ],
            [
                'branch_name' => 'Grand Square Ikeja',
                'branch_code' => 'GSI',
                'Company_id'  => 1,
                'Create_User_id' => 1,
                'Partner_id'  => 1
            ],
            [
                'branch_name' => 'Grand Square Abuja',
                'branch_code' => 'GSA',
                'Company_id'  => 1,
                'Create_User_id' => 1,
                'Partner_id'  => 1
            ],
            [
                'branch_name' => 'Grand Square Kano',
                'branch_code' => 'GSK',
                'Company_id'  => 1,
                'Create_User_id' => 1,
                'Partner_id'  => 1
            ],
            [
                'branch_name' => 'Samsung Ikeja',
                'branch_code' => 'SI',
                'Company_id'  => 1,
                'Create_User_id' => 1,
                'Partner_id'  => 1
            ],
        ]);
    }
}
