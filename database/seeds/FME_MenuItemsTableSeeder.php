<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;

class FMEMenuItemsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('name', 'admin')->firstOrFail();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Products',
            'url'     => '',
            'route'   => 'voyager.products.index',
        ]);

        $menuItem = MenuItem::create([
            'menu_id' => $menu->id,
            'title'   => 'Blacklist SKUS',
            'icon_class' => 'voyager-x',
            'route'   => 'product-handler.index',
            'url' => '',
            'order' => 20,
        ]);

        $menuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-shop',
            'color'      => null,
            'parent_id'  => null,
            'order'      => 1,
        ])->save();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Discounts',
            'url'     => '',
            'route'   => 'voyager.discounts.index',
        ]);

        $menuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-ticket',
            'color'      => null,
            'parent_id'  => null,
            'order'      => 2,
        ])->save();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Categories',
            'url'     => '',
            'route'   => 'voyager.categories.index',
        ]);

        $menuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-tag',
            'color'      => null,
            'parent_id'  => null,
            'order'      => 3,
        ])->save();


        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Orders',
            'url'     => '',
            'route'   => 'voyager.orders.index',
        ]);

        $menuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-dollar',
            'color'      => null,
            'parent_id'  => null,
            'order'      => 4,
        ])->save();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Brands',
            'url'     => '',
            'route'   => 'voyager.brands.index',
        ]);

        $menuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-lighthouse',
            'color'      => null,
            'parent_id'  => null,
            'order'      => 5,
        ])->save();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.dashboard'),
            'url'     => '',
            'route'   => 'voyager.dashboard',
        ]);

        $menuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-boat',
            'color'      => null,
            'parent_id'  => null,
            'order'      => 6,
        ])->save();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.media'),
            'url'     => '',
            'route'   => 'voyager.media.index',
        ]);

        $menuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-images',
            'color'      => null,
            'parent_id'  => null,
            'order'      => 10,
        ])->save();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.users'),
            'url'     => '',
            'route'   => 'voyager.users.index',
        ]);

        $menuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-person',
            'color'      => null,
            'parent_id'  => null,
            'order'      => 8,
        ])->save();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.roles'),
            'url'     => '',
            'route'   => 'voyager.roles.index',
        ]);

        $menuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-lock',
            'color'      => null,
            'parent_id'  => null,
            'order'      => 7,
        ])->save();

        $toolsMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.tools'),
            'url'     => '',
        ]);
        
        $toolsMenuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-tools',
            'color'      => null,
            'parent_id'  => null,
            'order'      => 14,
        ])->save();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.menu_builder'),
            'url'     => '',
            'route'   => 'voyager.menus.index',
        ]);

        $menuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-list',
            'color'      => null,
            'parent_id'  => $toolsMenuItem->id,
            'order'      => 15,
        ])->save();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.database'),
            'url'     => '',
            'route'   => 'voyager.database.index',
        ]);

        $menuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-data',
            'color'      => null,
            'parent_id'  => $toolsMenuItem->id,
            'order'      => 16,
        ])->save();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.compass'),
            'url'     => '',
            'route'   => 'voyager.compass.index',
        ]);

        $menuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-compass',
            'color'      => null,
            'parent_id'  => $toolsMenuItem->id,
            'order'      => 17,
        ])->save();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.bread'),
            'url'     => '',
            'route'   => 'voyager.bread.index',
        ]);

        $menuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-bread',
            'color'      => null,
            'parent_id'  => $toolsMenuItem->id,
            'order'      => 18,
        ])->save();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.settings'),
            'url'     => '',
            'route'   => 'voyager.settings.index',
        ]);

        $menuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-settings',
            'color'      => null,
            'parent_id'  => $toolsMenuItem->id,
            'order'      => 19,
        ])->save();    

        $role = Role::where('name', 'admin')->firstOrFail();
    
        $permissions = Permission::all();
    
        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );
    }

}
