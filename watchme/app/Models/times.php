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

    // Hier überschreiben wir die boot-Methode
    protected static function boot()
    {
        parent::boot();

        // Beim Erstellen eines neuen Datensatzes
        static::creating(function ($model) {
            $model->created_at = now()->addHours(2); // Fügt 2 Stunden zur aktuellen Zeit hinzu
            $model->updated_at = now()->addHours(2);
        });

        // Beim Aktualisieren eines vorhandenen Datensatzes
        static::updating(function ($model) {
            $model->updated_at = now()->addHours(2); // Fügt 2 Stunden zur aktuellen Zeit hinzu
        });
    }

    static function get_all_times()
    {
        return self::all();
    }

    static function add_times($project_id, $duration, $comment = '')
    {
        self::create([
            'project_id' => $project_id,
            'duration' => $duration,
            'comment' => $comment
        ]);
    }
}
