<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime; // You need to import the DateTime class

class times extends Model
{
    // verwendeter Primärschlüssel
    protected $primaryKey = 'id';

    // Name der Tabelle
    protected $table = 'times';

    protected $fillable = ['comment', 'duration', 'project_id', 'created_at', 'updated_at'];

    static function get_all_times()
    {
       return self::orderBy('created_at')->get();
    }

    static function add_times($project_id, $duration, $comment = '', $created_at = null)
    {
        $customCreatedAt = now()->addHours(2);

        if (is_null($created_at)) {
            self::create([
                'project_id' => $project_id,
                'duration' => $duration,
                'comment' => $comment,
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt
            ]);
        } else {
            // Convert the date string to a DateTime object
            $timeString = '00:00:00';
            $dateTime = DateTime::createFromFormat('d.m.Y', $created_at);

            if ($dateTime !== false) {
                // Format the DateTime object with your desired time string
                $customCreatedAt = $dateTime->format('Y-m-d') . ' ' . $timeString;

            self::create([
                'project_id' => $project_id,
                'duration' => $duration,
                'comment' => $comment,
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt
            ]);
            } else {
                // Handle invalid date format
                // You may want to throw an exception or log an error here
            }
        }
    }
}
