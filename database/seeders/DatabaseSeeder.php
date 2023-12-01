<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(LslLoyaltyProgramMasterTableSeeder::class);
        $this->call(LslPosInventoryMasterTableSeeder::class);
        $this->call(LslBranchMasterTableSeeder::class);
        $this->call(LslPaymentTypeMasterTableSeeder::class);
        $this->call(LslTransactionTypeMasterTableSeeder::class);
    }
}
