<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ProjectTask;

class ProjectTaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexView()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testApiGetTasks()
    {
        $this->seed('DatabaseSeeder');
        
        $response = $this->get(route('getTasks', ['id' => 1]))->decodeResponseJson();
        
        $this->assertEquals(3, count($response));
        
        $this->assertEquals('1', $response[0]['day']);
        $this->assertEquals('Task FF', $response[0]['name']);
        $this->assertEquals('Simple Task', $response[0]['type']);

        $this->assertEquals('2 - 3', $response[1]['day']);
        $this->assertEquals('Store A', $response[1]['name']);
        $this->assertEquals('Story', $response[1]['type']);

        $this->assertEquals('4', $response[2]['day']);
        $this->assertEquals('Task GG', $response[2]['name']);
        $this->assertEquals('Simple Task', $response[2]['type']);
    }

    public function testUpudate()
    {
        $this->seed('DatabaseSeeder');

        $inputs = [
            ['id' => 1, 'day' => '1', 'name' => 'Task FF', 'type' => 'Simple Task'],
            ['id' => 4, 'day' => '4', 'name' => 'Task CC', 'type' => 'Simple Task'],
            ['id' => 2, 'day' => '2 - 3', 'name' => 'Store A', 'type' => 'Story'],
        ];

        $this->post('/api/update', ['tasks' => $inputs])->assertStatus(200);

        $tasks = ProjectTask::find([1, 2, 3, 4]);

        $this->assertEquals('1', $tasks[0]['absolute_day']);

        $this->assertEquals('2', $tasks[3]['absolute_day']);
        $this->assertEquals('Task CC', $tasks[3]['name']);

        $this->assertEquals('3', $tasks[1]['absolute_day']);
        $this->assertEquals('Task A', $tasks[1]['name']);
        
        $this->assertEquals('4', $tasks[2]['absolute_day']);
        $this->assertEquals('Task B', $tasks[2]['name']);
    }
}
