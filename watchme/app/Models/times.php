<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class times extends Model
{
    // verwendeter Primärschlüssel
    protected $primaryKey = 'id';

// Name der Tabelle
    protected $table = 'times';

    protected $guarded = ['created_at', 'updated_at'];
    protected $fillable = ['comment', 'duration', 'project_id'];


    static function add_times($project_id, $duration, $comment = ''){
        self::create([
            'project_id' => $project_id,
            'duration' => $duration,
            'comment' => $comment
        ]);
    }

}
