<?php

use Illuminate\Database\Seeder;

class VoyagerDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DiscountsBreadTypeAdded::class,
            DiscountsBreadRowAdded::class,
            PermissionRoleTableSeeder::class
        ]);
    }
}
