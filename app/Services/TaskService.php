<?php

namespace App\Services;

use App\Models\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TaskCollection;

class TaskService
{
    public function index($pagePage)
    {
        return new TaskCollection(Task::with('user')->paginate($pagePage));
    }

    public function store($data)
    {   
        $data['user_id'] = Auth::id();
        $task = Task::create($data);

        return new TaskResource($task);
    }

    public function show($id)
    {   
        $task = Task::with('user')->find($id);
        
        return new TaskResource($task);
    }

    public function update($data, $id)
    {  
        $task = Task::find($id);
        if(!$task){
            return false;
        }

        $data =  $task->update($data);

        return $data;
    }

    public function destroy($id)
    {   
        $task = Task::find($id);

        if(!$task){
            return false;
        }
        
        return $task->delete();
    }

    public function toggleStatus($data, $id)
    {   
        $task = Task::find($id);

        if(!$task){
            return false;
        }
        
        return $task->update($data);
    }
}
