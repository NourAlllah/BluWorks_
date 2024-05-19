<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\WorkerController; // Replace with your controller path
use App\Models\Worker; // Replace with your worker model path
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;

class WorkerControllerTest extends TestCase
{
    use RefreshDatabase; // Clears database after each test

    protected $workerModel;

    public function __construct()
    {
        $this->workerModel = new \App\Models\Worker();
    }

    public function test_getClockIns_returns_empty_array_for_nonexistent_worker()
    {
        $worker_id = 1; // Replace with a non-existent worker ID

        $controller = new WorkerController();
        Request::shouldReceive('input')->with('worker_id')->andReturn($worker_id);
        $this->workerModel->shouldReceive('get_workers_clockIns')->with($worker_id)->andReturn([]);

        $response = $controller->getClockIns(Request::create('/worker/clock-ins'));

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function test_getClockIns_returns_clockins_for_existing_worker()
    {
        $worker = Worker::factory()->create();
        $clockIns = [
            [
                'worker_id' => $worker->id,
                'clocked_in_at' => now(),
            ],
            // Add more clock-in data as needed
        ];

        $controller = new WorkerController();
        Request::shouldReceive('input')->with('worker_id')->andReturn($worker->id);
        $this->workerModel->shouldReceive('get_workers_clockIns')->with($worker->id)->andReturn($clockIns);

        $response = $controller->getClockIns(Request::create('/worker/clock-ins'));

        $response->assertStatus(200);
        $response->assertJson($clockIns);
    }
}

