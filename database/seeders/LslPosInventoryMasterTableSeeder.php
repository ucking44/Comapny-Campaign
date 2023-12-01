<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LslPosInventoryMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lsl_pos_inventory_master')
            ->insert([
                [
                    'Item_code' => 'AC',
                    'Item_name' => 'Air Condition',
                    'Item_price' => 300000,
                    'Item_vat' => 210,
                    'Create_User_id' => 1,
                    'Update_User_id' => 1
                ],
                [
                    'Item_code' => 'PH',
                    'Item_name' => 'Samsung',
                    'Item_price' => 250000,
                    'Item_vat' => 190,
                    'Create_User_id' => 1,
                    'Update_User_id' => 1
                ],
                [
                    'Item_code' => 'LA',
                    'Item_name' => 'Dell Laptop',
                    'Item_price' => 170000,
                    'Item_vat' => 110,
                    'Create_User_id' => 1,
                    'Update_User_id' => 1
                ],
                [
                    'Item_code' => 'BA',
                    'Item_name' => 'School Bag',
                    'Item_price' => 7500,
                    'Item_vat' => 40,
                    'Create_User_id' => 1,
                    'Update_User_id' => 1
                ],
                [
                    'Item_code' => 'CA',
                    'Item_name' => 'BENZ',
                    'Item_price' => 75000000,
                    'Item_vat' => 850,
                    'Create_User_id' => 1,
                    'Update_User_id' => 1
                ],
                [
                    'Item_code' => 'SP',
                    'Item_name' => 'Spoon',
                    'Item_price' => 1800,
                    'Item_vat' => 20,
                    'Create_User_id' => 1,
                    'Update_User_id' => 1
                ],
                [
                    'Item_code' => 'FU',
                    'Item_name' => 'TV Console',
                    'Item_price' => 75000,
                    'Item_vat' => 85,
                    'Create_User_id' => 1,
                    'Update_User_id' => 1
                ]
            ]);
    }
}
