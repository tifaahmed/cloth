<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::updateOrCreate( [ 'name' => 'admin'       ,'guard_name' => 'web'] )  ;
        Role::updateOrCreate( [ 'name' => 'customer'    ,'guard_name' => 'web'] )  ;
        Role::updateOrCreate( [ 'name' => 'store'       ,'guard_name' => 'web'] )  ;
        Role::updateOrCreate( [ 'name' => 'super admin' ,'guard_name' => 'web'] )  ;
        Role::updateOrCreate( [ 'name' => 'sub admin'   ,'guard_name' => 'web'] )  ;

        Role::updateOrCreate( [ 'name' => 'cashier'     ,'guard_name' => 'web'] )  ;
        Role::updateOrCreate( [ 'name' => 'shife'       ,'guard_name' => 'web'] )  ;
        Role::updateOrCreate( [ 'name' => 'waiter'      ,'guard_name' => 'web'] )  ;
        Role::updateOrCreate( [ 'name' => 'driver'      ,'guard_name' => 'web'] )  ;
        Role::updateOrCreate( [ 'name' => 'call center' ,'guard_name' => 'web'] )  ;
        Role::updateOrCreate( [ 'name' => 'branch admin','guard_name' => 'web'] )  ;

    }
}
