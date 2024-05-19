<?php

namespace Tests\Unit;

use Illuminate\Http\Request;
use App\Http\Controllers\WorkerController;
use App\Models\Worker;
use Mockery;
use PHPUnit\Framework\TestCase;

class ClockInTest extends TestCase
{
    public function testClockInSuccess()
    {
        // Create a mock request
        $request = Request::create('/worker/clock-in', 'POST', [
            'worker_id' => 1,
            'timestamp' => time(),
            'latitude' => 30.048143,
            'longitude' => 31.236892
        ]);

        // Create a mock WorkerModel
        $workerModelMock = Mockery::mock('App\Models\Worker');
        $workerModelMock->shouldReceive('check_within_diameter')
                        ->andReturn(1); // Distance is within 2km
        $workerModelMock->shouldReceive('check_todays_clockIn')
                        ->andReturn(false); // No previous clock-in
        $workerModelMock->shouldReceive('save_clockIn')
                        ->andReturn(true); // Successful save

        // Inject the mock into the controller
        $workerController = new WorkerController($workerModelMock);

        // Call the clockIn method
        $response = $workerController->clockIn($request);

        // Assert the response
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Clock-in successful.', $response->getData()->message);
    }

    public function testClockInValidationError()
    {
        // Create a mock request with missing parameters
        $request = Request::create('/worker/clock-in', 'POST', [
            'worker_id' => 1,
            // 'timestamp' => time(),
            'latitude' => 30.048143,
            'longitude' => 31.236892
        ]);

        // Inject the mock into the controller
        $workerController = new WorkerController(new Worker());

        // Call the clockIn method
        $response = $workerController->clockIn($request);

        // Assert the response
        $this->assertEquals(422, $response->status());
        $this->assertEquals('error', $response->getData());
    }

    public function testClockInOutOfRange()
    {
        // Create a mock request
        $request = Request::create('/worker/clock-in', 'POST', [
            'worker_id' => 1,
            'timestamp' => time(),
            'latitude' => 30.048143,
            'longitude' => 31.236892
        ]);

        // Create a mock WorkerModel
        $workerModelMock = Mockery::mock('App\Models\Worker');
        $workerModelMock->shouldReceive('check_within_diameter')
                        ->andReturn(3); // Distance is out of 2km range

        // Inject the mock into the controller
        $workerController = new WorkerController($workerModelMock);

        // Call the clockIn method
        $response = $workerController->clockIn($request);

        // Assert the response
        $this->assertEquals(422, $response->status());
        $this->assertEquals('Coordinates are not within the allowed range.', $response->getData()->error);
    }

    public function testClockInAlreadyClockedIn()
    {
        // Create a mock request
        $request = Request::create('/worker/clock-in', 'POST', [
            'worker_id' => 1,
            'timestamp' => time(),
            'latitude' => 30.048143,
            'longitude' => 31.236892
        ]);

        // Create a mock WorkerModel
        $workerModelMock = Mockery::mock('App\Models\Worker');
        $workerModelMock->shouldReceive('check_within_diameter')
                        ->andReturn(1); // Distance is within 2km
        $workerModelMock->shouldReceive('check_todays_clockIn')
                        ->andReturn(true); // Already clocked in today

        // Inject the mock into the controller
        $workerController = new WorkerController($workerModelMock);

        // Call the clockIn method
        $response = $workerController->clockIn($request);

        // Assert the response
        $this->assertEquals(422, $response->status());
        $this->assertEquals('You already clocked in today.', $response->getData()->error);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
