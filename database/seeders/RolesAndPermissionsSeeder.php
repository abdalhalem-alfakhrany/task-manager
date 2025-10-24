<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createTask = Permission::create(['name' => 'create-task']);
        $updateTask = Permission::create(['name' => 'update-task']);
        $updateAnyTask = Permission::create(['name' => 'update-any-task']);
        $showTask = Permission::create(['name' => 'show-task']);
        $showAnyTask = Permission::create(['name' => 'show-any-task']);
        $listTasks = Permission::create(['name' => 'list-tasks']);


        $managerRole = Role::create(['name' => 'manager']);
        $managerRole->givePermissionTo([$createTask, $updateAnyTask, $showAnyTask, $listTasks,]);

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([$updateTask, $showTask]);
    }
}
