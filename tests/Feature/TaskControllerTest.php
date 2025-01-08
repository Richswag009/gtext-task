<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function it_can_create_a_task()
    {
        $task = [
            'title' => 'Test Task',
            'description' => 'This is a test task description.',
            'priority' => 'High',
            'due_date' => '2025-01-10',
        ];

        $response = $this->postJson('tasks/tasks', $task);
        $response->assertStatus(201);
        $response->assertJson([
            'status' => true,
            'message' => 'Task created successfully',
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task description.',
        ]);
    }

    /** @test */
    public function it_returns_validation_error_when_required_fields_are_missing()
    {
        $task = [
            'description' => 'This is a test task without a title.',
        ];

        // When making a POST request to create a task
        $response = $this->postJson('tasks/tasks', $task);

        // Then it should return validation errors
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    public function it_can_fetch_tasks_with_optional_filters()
{
    // Given some tasks in the database
    $task1 = Task::create([
        'title' => 'Task 1',
        'description' => 'First task description',
        'priority' => 'High',
        'due_date' => '2025-02-01',
    ]);

    $task2 = Task::create([
        'title' => 'Task 2',
        'description' => 'Second task description',
        'priority' => 'Low',
        'due_date' => '2025-01-15',
    ]);

    // When making a GET request to fetch tasks
    $response = $this->getJson('/tasks?priority=High');

    // Then the response should only contain tasks with priority 'High'
    $response->assertStatus(200);
    $response->assertJsonFragment(['title' => 'Task 1']);
    $response->assertJsonMissing(['title' => 'Task 2']);
}

/** @test */
public function it_can_fetch_all_tasks_without_filters()
{
    // Given tasks are in the database
    $task1 = Task::create([
        'title' => 'Task 1',
        'description' => 'First task description',
        'priority' => 'High',
        'due_date' => '2025-02-01',
    ]);
    $task2 = Task::create([
        'title' => 'Task 2',
        'description' => 'Second task description',
        'priority' => 'Low',
        'due_date' => '2025-01-15',
    ]);

    // When making a GET request to fetch all tasks
    $response = $this->getJson('/tasks');

    // Then the response should contain all tasks
    $response->assertStatus(200);
    $response->assertJsonFragment(['title' => 'Task 1']);
    $response->assertJsonFragment(['title' => 'Task 2']);
}
}
