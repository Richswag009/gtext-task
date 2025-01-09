<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskControllerTest extends TestCase
{

    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $task;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
        $this->task = Task::factory()->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function it_can_show_a_task()
    {


        // Act
        $response = $this->getJson("/api/tasks/{$this->task->id}", [
            'Authorization' => "Bearer {$this->token}",
        ]);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Task fetched successfully',
                'data' => [
                    'id' => $this->task->id,
                    'title' => $this->task->title,
                    'description' => $this->task->description,
                ],
            ]);
    }


    /** @test */
    public function it_can_create_a_task()
    {

        //Arrange
        $taskData = [
            'title' => 'Test Task',
            'description' => 'This is a test task description.',
            'priority' => 'High',
            'due_date' => '2025-01-10',
            'user_id' => $this->user->id
        ];

        // Act
        $response = $this->postJson("/api/tasks", $taskData, [
            'Authorization' => "Bearer {$this->token}",
        ]);

        // Assert
        $response->assertStatus(200)
            ->assertJsonFragment($taskData);
        $this->assertDatabaseHas('tasks', $taskData);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        // Arrange
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        // Act
        // $response = $this->deleteJson("/api/tasks/{$task->id}");
        $response = $this->deleteJson("/api/tasks/{$task->id}", [], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }


    /** @test */
    public function it_can_fetch_all_tasks_without_filters()
    {
        // Arrange
        $tasks = Task::factory()->count(3)->create(['user_id' => $this->user->id]);
        // Act
        $response = $this->getJson('/api/tasks', [
            'Authorization' => "Bearer {$this->token}",
        ]);


        // Assert: 
        $response->assertStatus(200);
        foreach ($tasks as $task) {
            $response->assertJsonFragment([
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
            ]);
        }
    }
}
