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
            'projectname'=>'required|string',
            'created_at' => 'required|string']
        );
        $id = projects::get_project_id($data['projectname']) ?? -1;

        if($id === -1) return response()->json("name not found", 500);


        times::add_times($id,$data['duration'],$data['comment'],$data['created_at']);
        return response()->json("successfully created", 201);


    }

    function api_import_times(Request $request) {
        $data =  $request->all();

        // Process the "times" array
        if (isset($data['times']) && is_array($data['times'])) {
            foreach ($data['times'] as $time) {
                // Add the time entry to the times array
                $id = projects::get_project_id($time['projectname']) ?? -1;


                if($id === -1) return response()->json("No Project ".$time['projectname']." available", 404);

                times::add_times($id,$time['duration']*60*60,$time['comment'],$time['created_at']);
            }
                return response()->json("successfully created", 201);
        } return response()->json("Problem with import", 500);

    }

    function api_import_projects(Request $request) {
        $data =  $request->all();
        // Process the "projects" array
        $string = "";
        if (isset($data['projects']) && is_array($data['projects'])) {
            foreach ($data['projects'] as $project) {
                projects::add_project($project);

            }
            return response()->json("successfully created", 201);
        } return response()->json("Problem with import", 500);
    }


}


