<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LslPaymentTypeMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lsl_payment_type_master')->insert([
            [
                'payment_type_name' => 'Cash'
            ],
            [
                'payment_type_name' => 'Cheque'
            ],
            [
                'payment_type_name' => 'Credit Card'
            ],
            [
                'payment_type_name' => 'Debit Card'
            ],
            [
                'payment_type_name' => 'Coupon'
            ],
            [
                'payment_type_name' => 'Voucher'
            ],
            [
                'payment_type_name' => 'Point Redemption'
            ],
            [
                'payment_type_name' => 'Cash and Points'
            ],
            [
                'payment_type_name' => 'Wallet'
            ],
            [
                'payment_type_name' => 'Wallet Cash and Points'
            ],
        ]);
    }
}
