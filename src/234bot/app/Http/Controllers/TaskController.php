<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    function getTask(){
        return Task::all();
    }

    function addTask(Request $req){
        $task = new Task;
        $fromreq = $req->value;
        $task->user_id = (int)substr($fromreq,0,1);
        $task->deadline = null;
        $task->mata_kuliah = substr($fromreq,4,6);
        $task->kata_penting_id = (int)substr($fromreq,1,2);
        $task->topik = substr($fromreq,8,10);
        $task->save();
        return redirect('/');
    }
}
