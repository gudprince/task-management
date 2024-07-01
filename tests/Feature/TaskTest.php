<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    //use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->accessToken;

        return ['Authorization' => "Bearer $token"];
    }

    public function test_it_lists_all_tasks()
    {
        $headers = $this->authenticate();
        Task::factory()->count(5)->create();

        $response = $this->withHeaders($headers)->getJson('/api/v1/tasks');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_it_creates_a_task()
    {
        $headers = $this->authenticate();

        $response = $this->withHeaders($headers)->postJson('/api/v1/tasks', [
            'title' => 'Test Task',
            'description' => 'Test Description',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_it_retrieves_a_task()
    {
        $headers = $this->authenticate();
        $task = Task::factory()->create();

        $response = $this->withHeaders($headers)->getJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_it_updates_a_task()
    {
        $headers = $this->authenticate();
        $task = Task::factory()->create();

        $response = $this->withHeaders($headers)->putJson("/api/v1/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_it_deletes_a_task()
    {
        $headers = $this->authenticate();
        $task = Task::factory()->create();

        $response = $this->withHeaders($headers)->deleteJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(200);
    }
}
