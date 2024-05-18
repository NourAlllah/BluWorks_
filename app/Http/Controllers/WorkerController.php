<?php

namespace App\Http\Controllers;
use App\Models\Worker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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
