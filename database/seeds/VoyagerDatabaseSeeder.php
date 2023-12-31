<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Traits\Seedable;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class VoyagerDatabaseSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__.'/';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seed('DataTypesTableSeeder');
        $this->seed('DataRowsTableSeeder');
        $this->seed('MenusTableSeeder');
        $this->seed('MenuItemsTableSeeder');
        $this->seed('RolesTableSeeder');
        $this->seed('PermissionsTableSeeder');
        $this->seed('PermissionRoleTableSeeder');
        $this->seed('SettingsTableSeeder');
        $this->seed('VoyagerDeploymentOrchestratorSeeder');
        $this->seed('FMEMenuItemsTableSeeder');
    }
}
