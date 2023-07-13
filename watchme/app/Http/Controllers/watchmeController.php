<?php

namespace App\Http\Controllers;

use App\Models\projects;
use App\Models\times;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

class watchmeController extends Controller
{
    function home(Request $request){
        return view("/app");
    }


    function api_get_all(){
        $times = times::get_all_times();
        $projectsraw = projects::get_all_projects();
        foreach ($projectsraw as $project) {
            $projects[$project['id']] = $project['name'];
        }

        foreach ($times as $time) {
            $projectId = $time['project_id'];

            if (isset($projects[$projectId])) {
                $time['project_id'] = $projects[$projectId];
                $result[] = $time;
            }
        }


        return $result;

    }

    function api_get_projects(Request $request){
        return projects::get_all_projects();
    }

    function api_add_new_project(Request $request){
        $data = $request->validate([
            'name' => 'required|string|min:1'
        ]);
            projects::add_project($data['name']);
        return response()->json("successfully created", 201);
    }


    function api_add_time_to_project(Request $request){
        $data = $request->validate([
            'duration' => 'required|integer',
            'comment' => '',
            'projectname'=>'required|string']
        );
        $id = projects::get_project_id($data['projectname']) ?? -1;

        if($id === -1) return response()->json("name not found", 500);
        times::add_times($id,$data['duration'],$data['comment']);
        return response()->json("successfully created", 201);

    }
}


