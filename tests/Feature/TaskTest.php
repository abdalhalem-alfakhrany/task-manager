<?php

use App\Models\User;
use App\Models\Task;
use Carbon\Carbon;
use Database\Seeders\RolesAndPermissionsSeeder;
use Symfony\Component\HttpFoundation\Response;

test('managers only can create task', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $manager = User::factory()->create()->assignRole('manager');
    $user = User::factory()->create()->assignRole('user');


    $response = $this->actingAs($manager, 'api')->postJson('/api/task', [
        'title' => 'test title',
        'description' => 'test description',
        'due_date' => Carbon::today(),
        'user_id' => 1
    ]);

    $response->assertOk();


    $response = $this->actingAs($user, 'api')->postJson('/api/task', [
        'title' => 'test title',
        'description' => 'test description',
        'due_date' => Carbon::today(),
        'user_id' => 1
    ]);

    $response->assertForbidden();
});

test('managers only can update any task', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $manager = User::factory()->create()->assignRole('manager');
    $user = User::factory()->create()->assignRole('user');

    $task = Task::factory()->create();


    $response = $this->actingAs($manager, 'api')->patchJson('/api/task/' . $task->id, [
        'title' => 'updated title',
    ]);

    $response->assertOk();


    $response = $this->actingAs($user, 'api')->patchJson('/api/task/' . $task->id, [
        'title' => 'updated title',
    ]);

    $response->assertForbidden();
});

test('users can update only there own tasks', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $user = User::factory()->create()->assignRole('user');
    $anotherUser = User::factory()->create()->assignRole('user');

    $task = Task::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user, 'api')->patchJson('/api/task/' . $task->id, [
        'status' => 'pending',
    ]);
    $response->assertOk();

    $response = $this->actingAs($anotherUser, 'api')->patchJson('/api/task/' . $task->id, [
        'status' => 'pending',
    ]);
    $response->assertForbidden();
});

test('users can update only status for tasks', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $user = User::factory()->create()->assignRole('user');

    $task = Task::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user, 'api')->patchJson('/api/task/' . $task->id, [
        'title' => 'updated title',
    ]);
    $response->assertUnprocessable();
    $response->assertJsonPath('message', 'you cannot update any fields other the status of your own tasks');

    $response = $this->actingAs($user, 'api')->patchJson('/api/task/' . $task->id, [
        'status' => 'completed',
    ]);
    $response->assertOk();
});

test('managers only can show any task', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $manager = User::factory()->create()->assignRole('manager');
    $user = User::factory()->create()->assignRole('user');
    $anotherUser = User::factory()->create()->assignRole('user');

    $task = Task::factory()->create(['user_id' => $anotherUser->id]);

    $response = $this->actingAs($manager, 'api')->getJson('/api/task/' . $task->id);
    $response->assertOk();


    $response = $this->actingAs($user, 'api')->getJson('/api/task/' . $task->id);
    $response->assertForbidden();
});

test('users can show tasks assigned to them', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $user = User::factory()->create()->assignRole('user');
    $anotherUser = User::factory()->create()->assignRole('user');

    $task = Task::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user, 'api')->getJson('/api/task/' . $task->id);
    $response->assertOk();

    $response = $this->actingAs($anotherUser, 'api')->getJson('/api/task/' . $task->id);
    $response->assertForbidden();
});

test('managers only can list all tasks', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $manager = User::factory()->create()->assignRole('manager');
    $user = User::factory()->create()->assignRole('user');
    $anotherUser = User::factory()->create()->assignRole('user');

    $task = Task::factory()->create(['user_id' => $user->id]);
    $task = Task::factory()->create(['user_id' => $user->id]);
    $task = Task::factory()->create(['user_id' => $anotherUser->id]);

    $response = $this->actingAs($manager, 'api')->getJson('/api/task/');
    $response->assertOk();
    $response->assertJsonCount(3, 'data');


    $response = $this->actingAs($user, 'api')->getJson('/api/task/');
    $response->assertOk();
    $response->assertJsonCount(2, 'data');

    $response = $this->actingAs($anotherUser, 'api')->getJson('/api/task/');
    $response->assertOk();
    $response->assertJsonCount(1, 'data');
});

test('prevents completing a task when dependencies are incomplete', function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $user = User::factory()->create()->assignRole('manager');
    $dep1 = Task::factory()->create(['status' => 'pending']);
    $task = Task::factory()->create(['user_id' => $user->id]);
    $task->tasks()->save($dep1);

    $response = $this->actingAs($user, 'api')->patchJson("/api/task/{$task->id}", [
        'status' => 'completed'
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJson([
        'success' => false,
        'message' => 'Cannot complete this task until all dependencies are completed.'
    ]);
});
