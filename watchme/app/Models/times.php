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

    static function add_times($project_id, $duration, $comment = '', $created_at = '')
    {
        $customCreatedAt = now()->addHours(2);
        if ($created_at == "") {
            self::create([
                'project_id' => $project_id,
                'duration' => $duration,
                'comment' => $comment,
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt
            ]);
             return true;
        } else {
            // Convert the date string to a DateTime object
            $timeString = '00:00:00';
            $customCreatedAt;
            $dateTime = DateTime::createFromFormat('Y-m-d', $created_at);
            if ($dateTime !== false){
              $customCreatedAt = $dateTime->format('Y-m-d') . ' ' . $timeString;
            }
            else{
                $dateTime = DateTime::createFromFormat('d.m.Y', $created_at);
                $customCreatedAt = $dateTime->format('Y-m-d') . ' ' . $timeString;
            }


                self::create([
                    'project_id' => $project_id,
                    'duration' => $duration,
                    'comment' => $comment,
                    'created_at' => $customCreatedAt,
                    'updated_at' => $customCreatedAt
                ]);
                return true;
        }
    }
}
