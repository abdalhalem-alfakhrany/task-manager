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
        $readTask = Permission::create(['name' => 'read-task']);

        $assignTask = Permission::create(['name' => 'assign-task']);


        Role::create(['name' => 'manager'])
            ->permissions()
            ->attach([$createTask->id, $updateTask->id, $readTask->id, $assignTask->id]);

        Role::create(['name' => 'user'])
            ->permissions()
            ->attach([$updateTask->id, $readTask->id]);
    }
}
