<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Worker extends Model implements Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password', // Include password if using worker authentication
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Hash the worker's password before saving.
     *
     * @param string $value
     * @return string
     */


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    // Implement missing methods from Authenticatable
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberTokenName()
    {
        return 'remember_token'; 
    }
    
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getAuthIdentifierName()
    {
        return 'email'; 
    }

    public function getAuthIdentifier()
    {
        return $this->email; 
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function get_workers_data($worker_id){
        
       return $worker = Worker::find($worker_id);

    }

    public function check_within_diameter( $latitudeFrom,$longitudeFrom,$latitudeTo,$longitudeTo){
           
            $earthRadius = 6371;
       
            // convert from degrees to radians
            $latFrom = deg2rad($latitudeFrom);
            $lonFrom = deg2rad($longitudeFrom);
            $latTo = deg2rad($latitudeTo);
            $lonTo = deg2rad($longitudeTo);
    
            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;
    
            $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
            return $angle * $earthRadius;
        
    }

    public function check_todays_clockIn($worker_id){

        $currentDate = date('Y-m-d');

        $sql = "SELECT COUNT(*) AS count FROM bluworks.clock_ins WHERE worker_id = ? AND DATE(timestamp) = ?";
        $result = DB::selectOne($sql, [$worker_id, $currentDate]);

        $count = $result ? $result->count : 0;

        return $count > 0;
    
    }

    public function save_clockIn($worker_id,$timestamp,$latitude,$longitude){

        $timestampInSeconds = $timestamp / 1000; // Convert milliseconds to seconds

        $datetime = Carbon::createFromTimestamp($timestampInSeconds, 'Africa/Cairo')->toDateTimeString();

        return DB::table('clock_ins')->insert([
            'worker_id' => $worker_id,
            'timestamp' => $datetime,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'created_at' => $datetime
        ]);
    }

    public function get_workers_clockIns($worker_id){
        return DB::table('clock_ins')->where('worker_id', $worker_id)->get();
    }
}