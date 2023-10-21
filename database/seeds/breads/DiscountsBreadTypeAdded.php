<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\MenuItem;

class DiscountsBreadTypeAdded extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     *
     * @throws Exception
     */
    public function run()
    {
        try {

            \DB::beginTransaction();

            $id = DB::table('data_types')->select('id')->where('name', 'discounts')->first()->id ?? 0;
            DB::table('data_rows')->where('data_type_id', $id)->delete();

            $dataType = DataType::where('name', 'discounts')->first();

            if (is_bread_translatable($dataType)) {
                $dataType->deleteAttributeTranslations($dataType->getTranslatableAttributes());
            }

            if ($dataType) {
                DataType::where('name', 'discounts')->delete();
            }

            \DB::table('data_types')->insert(array (
                'name' => 'discounts',
                'slug' => 'discounts',
                'display_name_singular' => 'Discount',
                'display_name_plural' => 'Discounts',
                'icon' => 'icon voyager-megaphone',
                'model_name' => 'App\\Discount',
                'policy_name' => NULL,
                'controller' => 'App\\Http\\Controllers\\Voyager\\DiscountController',
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 1,
                'details' => '{"order_column":"id","order_display_column":"name","order_direction":"desc","default_search_key":"name","scope":"siteDiscount"}',
                'created_at' => '2020-01-20 16:16:24',
                'updated_at' => '2020-02-04 18:44:44',
            ));

            Voyager::model('Permission')->generateFor('discounts');

            $menu = Menu::where('name', config('voyager.bread.default_menu'))->firstOrFail();

            $menuItem = MenuItem::firstOrNew([
                'menu_id' => $menu->id,
                'title' => 'Discounts',
                'url' => '',
                'route' => 'voyager.discounts.index',
            ]);

            $order = Voyager::model('MenuItem')->highestOrderMenuItem();

            if (!$menuItem->exists) {
                $menuItem->fill([
                    'target' => '_self',
                    'icon_class' => 'icon voyager-megaphone',
                    'color' => null,
                    'parent_id' => null,
                    'order' => $order,
                ])->save();
            }
        } catch(Exception $e) {
           throw new Exception('Exception occur ' . $e);

           \DB::rollBack();
        }

        \DB::commit();
    }
}
