<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class testseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Lese den Inhalt der JSON-Datei
        $pfad = "database/seeders/data_times.json";
        // Generierte Datensätze
        $data = json_decode(file_get_contents($pfad),true);


        // Generierte Datensätze für die Tabelle "project"
        $projectsData = [
            ["id" => 1, "name" => "projekt 1"],
            ["id" => 2, "name" => "projekt 2"],
            ["id" => 3, "name" => "projekt 3"],
            ["id" => 4, "name" => "projekt 4"],
            ["id" => 5, "name" => "projekt 5"],
            ["id" => 6, "name" => "projekt 6"],
            ["id" => 7, "name" => "projekt 7"],
            ["id" => 8, "name" => "projekt 8"],
            ["id" => 9, "name" => "projekt 9"],
            ["id" =>10, "name" => "projekt 10"],
            ["id" => 11, "name" => "projekt 11"],
            ["id" => 12, "name" => "projekt 12"],
            ["id" => 13, "name" => "projekt 13"],
            ["id" => 14, "name" => "projekt 14"],
            ["id" => 15, "name" => "projekt 15"],
            ["id" => 16, "name" => "projekt 16"],
            ["id" => 17, "name" => "projekt 17"],
            ["id" => 18, "name" => "projekt 18"],
            ["id" => 19, "name" => "projekt 19"],
            ["id" =>20, "name" => "projekt 20"],
        ];

// Tabelle "projects" mit den generierten Datensätzen befüllen
        DB::table('projects')->insert($projectsData);
        // Tabelle "times" mit den generierten Datensätzen befüllen
        DB::table('times')->insert($data);

        // Autoincrement aktualisieren
        $tableName = 'times';
        $maxId = count($data);
        $query = "ALTER TABLE $tableName AUTO_INCREMENT = $maxId;";
        DB::statement($query);

        $tableName = 'projects';
        $maxId = count($projectsData);
        $query = "ALTER TABLE $tableName AUTO_INCREMENT = $maxId;";
        DB::statement($query);
    }
}
