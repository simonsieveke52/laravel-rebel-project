<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Traits\Seedable;

class VoyagerDeploymentOrchestratorSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = 'database/breads/seeds/';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seed(BrandsBreadTypeAdded::class);
        $this->seed(BrandsBreadRowAdded::class);
        $this->seed(ProductsBreadTypeAdded::class);
        $this->seed(ProductsBreadRowAdded::class);
        $this->seed(OrdersBreadTypeAdded::class);
        $this->seed(OrdersBreadRowAdded::class);
        $this->seed(OrdersTableSeeder::class);
        $this->seed(CategoriesBreadTypeAdded::class);
        $this->seed(CategoriesBreadRowAdded::class);
        $this->seed(DiscountsBreadDeleted::class);
    }
}
