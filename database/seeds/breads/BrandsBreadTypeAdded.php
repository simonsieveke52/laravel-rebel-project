<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\MenuItem;

class BrandsBreadTypeAdded extends Seeder
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

            $dataType = DataType::where('name', 'brands')->first();

            if (is_bread_translatable($dataType)) {
                $dataType->deleteAttributeTranslations($dataType->getTranslatableAttributes());
            }

            if ($dataType) {
                DataType::where('name', 'brands')->delete();
            }

            \DB::table('data_types')->insert(array (
                'id' => 7,
                'name' => 'brands',
                'slug' => 'brands',
                'display_name_singular' => 'Brand',
                'display_name_plural' => 'Brands',
                'icon' => NULL,
                'model_name' => 'App\\Brand',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => 'Brands linked to products',
                'generate_permissions' => 1,
                'server_side' => 1,
                'details' => '{"order_column":"name","order_display_column":null,"order_direction":"asc","default_search_key":"name","scope":null}',
                'created_at' => '2020-01-18 22:36:38',
                'updated_at' => '2020-01-18 22:41:25',
            ));

            Voyager::model('Permission')->generateFor('brands');

            $menu = Menu::where('name', config('voyager.bread.default_menu'))->firstOrFail();

            $menuItem = MenuItem::firstOrNew([
                'menu_id' => $menu->id,
                'title' => 'Brands',
                'url' => '',
                'route' => 'voyager.brands.index',
            ]);

            $order = Voyager::model('MenuItem')->highestOrderMenuItem();

            if (!$menuItem->exists) {
                $menuItem->fill([
                    'target' => '_self',
                    'icon_class' => '',
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
