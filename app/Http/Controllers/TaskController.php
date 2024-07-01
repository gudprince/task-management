<?php

namespace App\Http\Controllers;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\TaskRequest;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskController extends Controller
{
    use ApiResponse;

    public $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $pagePage = $request->has('pagePage') ? $request->pagePage : 10;
            $task =  $this->taskService->index($pagePage);

            return $this->successResponse($task,  'Tasks retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        try {
    
            $task =  $this->taskService->store($request->all());

            return $this->successResponse($task,  'Task created successfully', Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse( $e->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $task =  $this->taskService->show($id);

            return $this->successResponse($task,  'Task retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Task not found', [], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve task', [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $task =  $this->taskService->update($request->all(), $id);
            if(!$task){
                return $this->errorResponse('Task not found', [], Response::HTTP_NOT_FOUND);
            }

            return $this->successResponse(null,  'Task updated successfully');
        
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update task', [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
           $task = $this->taskService->destroy($id);
            if(!$task){
                return $this->errorResponse('Task not found', [], Response::HTTP_NOT_FOUND);
            }

            return $this->successResponse(null,  'Task deleted successfully');
       
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete task', [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
