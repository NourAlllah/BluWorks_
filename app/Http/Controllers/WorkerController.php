<?php

namespace App\Http\Controllers;
use App\Models\Worker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use OpenApi\Attributes as OA;


class WorkerController extends Controller
{
    protected $workerModel;

    public function __construct()
    {
        $this->workerModel = new \App\Models\Worker();
    }

    public function Myview(){

        $worker_id = $_GET['workerId'];

        $worker_data = $this->workerModel->get_workers_data($worker_id);

        return view('ClicksIn')->with(['worker'=> $worker_data]); 
    }

    
   /**
   * @OA\Post(
   *   path="/worker/clock-in",
   *   summary="Clock in a worker",
   *   description="Record a clock-in for a worker",
   *   tags={"Worker"},
   *   @OA\RequestBody(
   *     required=true,
   *     @OA\JsonContent(
   *       required={"worker_id", "timestamp", "latitude", "longitude"},
   *       @OA\Property(property="worker_id", type="integer", example=1),
   *       @OA\Property(property="timestamp", type="integer", example=1625247600),
   *       @OA\Property(property="latitude", type="number", format="float", example=30.048143),
   *       @OA\Property(property="longitude", type="number", format="float", example=31.236892)
   *     )
   *   ),
   *   @OA\Response(
   *     response=200,
   *     description="Clock-in successful",
   *     @OA\JsonContent(
   *       @OA\Property(property="message", type="string", example="Clock-in successful.")
   *     )
   *   ),
   *   @OA\Response(
   *     response=422,
   *     description="Validation error or other errors",
   *     @OA\JsonContent(
   *       @OA\Property(property="error", type="string", example="Validation error details")
   *     )
   *   )
   * )
   */

    public function clockIn(Request $request){

        //Validation 
        $validator = Validator::make($request->all(), [
            'worker_id' => 'required|integer',
            'timestamp' => 'required|integer',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $workLatitude = 30.0493604 ;  
        $workLongitude = 31.2377317; 

        // Check if the coordinates are within 2km of the hardcoded location
        $distance = $this->workerModel->check_within_diameter($workLatitude , $workLongitude , $request->latitude , $request->longitude);

        if ($distance > 2) {
            return response()->json(['error' => 'Coordinates are not within the allowed range.'], 422);
        }
        
        //check if the worker already clocked in today 
        if ($this->workerModel->check_todays_clockIn($request->worker_id)) {
            return response()->json(['error' => 'You already clocked in today.'], 422);
        }

        //Save Clock-in in db 
        $this->workerModel->save_clockIn( $request->worker_id,$request->timestamp,$request->latitude , $request->longitude);

        return response()->json(['message' => 'Clock-in successful.'], 200);
        
    }

    /**
   * @OA\Get(
   *   path="/worker/clock-ins",
   *   summary="Get clock-ins for a worker",
   *   description="Retrieve the list of clock-ins for a specific worker based on worker ID",
   *   tags={"Worker"},
   *   @OA\Parameter(
   *     name="worker_id",
   *     in="query",
   *     description="ID of the worker to get clock-ins for",
   *     required=true,
   *     @OA\Schema(type="integer")
   *   ),
   *   @OA\Response(
   *     response=200,
   *     description="List of clock-ins",
   *     @OA\JsonContent(
   *       type="array",
   *       @OA\Items(
   *         type="object",
   *         
   *            @OA\Property(property="id", type="integer", example=1),
   *            @OA\Property(property="worker_id", type="integer", example=1),
   *             @OA\Property(property="timestamp", type="string", format="date-time", example="2024-05-18 13:10:20"),
   *             @OA\Property(property="latitude", type="number", format="float", example=30.048143),
   *             @OA\Property(property="longitude", type="number", format="float", example=31.236892),
   *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-05-18 13:10:20"),
   *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-05-18 13:10:20")
   *    
   *       )
   *     )
   *   ),
   *   @OA\Response(
   *     response=422,
   *     description="Validation error",
   *     @OA\JsonContent(
   *       @OA\Property(property="error", type="string", example="The worker_id field is required.")
   *     )
   *   )
   * )
   */

    public function getClockIns(Request $request)
    {
        // Validate the request parameters
        $request->validate([
            'worker_id' => 'required|integer',
        ]);

        // Retrieve the worker_id from the query parameters
        $worker_id = $request->input('worker_id');

        // Query the database to get clock-ins associated with the worker
        $worker_clockIns = $this->workerModel->get_workers_clockIns($worker_id);

        // Return the list of clock-ins as JSON response
        return response()->json($worker_clockIns);
    }

}
