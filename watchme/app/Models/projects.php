<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class projects extends Model
{
    protected $primaryKey = 'id';

// Name der Tabelle
    protected $table = 'projects';

    protected $fillable = ['name'];

    public $timestamps = false;


    static function get_project_id($name){
        return self::where('name', $name)->value('id');
    }

    static function add_project($name){
       self::create([
            'name' => $name
        ]);

    }

    static function get_all_projects(){
        return self::all();
    }
}
