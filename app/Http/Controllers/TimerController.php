<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Project;
use App\UserTasks;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TimerController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view("timer");
  }

  /**
   * Get all clients from the client table
   * 
   * @return \Illuminate\Http\JsonResponse
   */
  public function getClients()
  {
    $clients = Client::select('id', 'name')->get();

    return response()->json($clients);
  }

  /**
   * Get projects for a specific client
   * 
   * @param int $clientId
   * @return \Illuminate\Http\JsonResponse
   */
  public function getProjects($clientId)
  {
    $projects = Project::where('client_id', $clientId)
      ->select('id', 'project_name')
      ->get();

    return response()->json($projects);
  }


  /**
   * Get the current authenticated user's name
   * 
   * @return \Illuminate\Http\JsonResponse
   */
  public function getUser()
  {
    $user = Auth::user();

    if ($user) {
      return response()->json([
        'name' => $user->name
      ]);
    }

    return response()->json(['error' => 'No authenticated user'], 401);
  }

  public function initTimer(Request $request)
  {
    try {
      $validatedData = $request->validate([
        'username' => 'required|string',
        'client_id' => 'required|integer|exists:client,id',
        'project_id' => 'required|integer|exists:project,id',
        'client_name' => 'required|string',
        'project_name' => 'required|string',
        'timer' => 'required|integer',
        'started_at' => 'required|date',
        'timer_date' => 'required|date',
        'is_running' => 'required|boolean'
      ]);

      $userTasks = UserTasks::create([
        'username' => $validatedData['username'],
        'client_id' => $validatedData['client_id'],
        'project_id' => $validatedData['project_id'],
        'client_name' => $validatedData['client_name'],
        'project_name' => $validatedData['project_name'],
        'timer' => $validatedData['timer'],
        'started_at' => $validatedData['started_at'],
        'timer_date' => $validatedData['timer_date'],
        'is_running' => $validatedData['is_running']
      ]);

      return response()->json([
        'message' => 'Timer started successfully',
        'task_id' => $userTasks->id
      ], 201);
    } catch (\Illuminate\Validation\ValidationException $ve) {
      \Log::error('Validation Error: ' . json_encode($ve->errors()));
      return response()->json([
        'message' => 'Validation failed',
        'errors' => $ve->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error('Error starting timer: ' . $e->getMessage());
      \Log::error('Error Trace: ' . $e->getTraceAsString());
      return response()->json([
        'message' => 'Failed to start timer',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function getTasks($date)
  {
    $tasks = UserTasks::whereDate('timer_date', $date)
      ->select('id', 'username', 'client_id', 'project_id', 'client_name', 'project_name', 'timer', 'started_at', 'timer_date', 'is_running')
      ->get();

    return response()->json($tasks);
  }

  public function startTimer($taskId)
  {
    $task = UserTasks::findOrFail($taskId);

    $task->is_running = true;
    $task->started_at = now();
    $task->save();

    return response()->json([
      'success' => true,
      'message' => 'Timer started',
      'task' => $task
    ]);
  }

  public function stopTimer($taskId)
  {
    $task = UserTasks::findOrFail($taskId);

    if ($task->is_running && $task->started_at) {
      $diffInSeconds = $task->started_at->diffInSeconds(now());
      $task->timer = ($task->timer ?? 0) + $diffInSeconds;
      $task->is_running = false;
      $task->started_at = null;
      $task->save();
    }

    return response()->json([
      'success' => true,
      'message' => 'Timer stopped',
      'task' => $task
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
