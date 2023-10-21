<?php

use App\UserList;
use Illuminate\Database\Seeder;

class UserListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $lists =[
            ['id' => '1','name' => 'Quick Order','slug' => 'quick-order','description' => 'This list is to allow the user to have a list 
            that will be used in the Quick Order feature of the site.'],
            ['id' => '2','name' => 'Save For Later','slug' => 'save-for-later','description' => 'This list is used on the Quick Order page to store items
            the end user wants to quickly kick out of the Quick Order list, but not lose the items.'],
            ['id' => '3','name' => 'Quote Request','slug' => 'quote-request','description' => 'This list is to allow the user to have a list 
            that will be used in the Quote Request feature of the site.']
        ];

        foreach ($lists as $list) {
            UserList::create($list);
        }

    }
}


