<?php

namespace App\Http\Controllers;

use App\Models\times;
use Illuminate\Http\Request;

class watchmeController extends Controller
{
    function home(Request $request){
        return view("/app");
    }





    function api_add_time_to_project(Request $request){
        $data = $request->validate([
            'duration' => 'required|integer', 'comment' => 'String']
        );



        times::add_times(1,$data['duration'],$data['comment']);

    }
}
