<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Project;
use App\UserTasks;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
  public function indexWeekly()
  {
    return view("weekly-timer");
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
  public function getWeeklyTasks($date)
  {
    $userId = Auth::id(); // Get the authenticated user's ID

    // Parse the date and calculate the start (Monday) and end (Sunday) of the week
    $startOfWeek = Carbon::parse($date)->startOfWeek(Carbon::MONDAY);
    $endOfWeek = Carbon::parse($date)->endOfWeek(Carbon::SUNDAY);

    // Fetch tasks for the week, grouped by date, project, and client
    $tasks = UserTasks::whereBetween('timer_date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
      ->select(
        'timer_date',
        'project_name',
        'client_name',
        'project_id',
        'client_id',
        DB::raw('SUM(timer) as total_time') // Sum the 'timer' field
      )
      ->groupBy('timer_date', 'project_name', 'client_name', 'project_id', 'client_id', 'created_at')
      ->orderBy('created_at', 'ASC')
      ->get();
    // Initialize the response structure
    $result = [];

    // Get all unique combinations of project and client names from tasks
    $projectsAndClients = $tasks->map(function ($task) {
      return "{$task->project_name} + {$task->client_name}";
    })->unique();

    // Iterate over each project + client combination
    foreach ($projectsAndClients as $projectAndClient) {
      $result[$projectAndClient] = [];

      // Extract project and client names
      $tasksForCombination = $tasks->filter(function ($task) use ($projectAndClient) {
        return "{$task->project_name} + {$task->client_name}" === $projectAndClient;
      });

      // Iterate through each day of the week
      for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
        $currentDate = $date->toDateString();
        $dayName = $date->format('l'); // Get the day name (e.g., Monday)

        // Find tasks for the current date
        $dayTask = $tasksForCombination->first(function ($task) use ($currentDate) {
          return Carbon::parse($task->timer_date)->toDateString() === $currentDate;
        });
        // Append the task data for the current day
        $result[$projectAndClient][] = [
          'day' => $dayName,
          'time' => $dayTask->total_time ?? 0, // Default to 0 if no tasks found
          'project_id' => $dayTask->project_id ?? '',
          'client_id' => $dayTask->client_id ?? '',
        ];
        // dd($result);

      }
    }
    return $result;
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

  public function getWeekSummary()
  {
    $weekStart = Carbon::now()->startOfWeek();
    $weekEnd = Carbon::now()->endOfWeek();

    // Group tasks by date and calculate total time
    $summary = UserTasks::whereBetween('timer_date', [$weekStart, $weekEnd])
      ->selectRaw('DATE(timer_date) as date, SUM(timer) as total_time')
      ->groupBy('date')
      ->get()
      ->mapWithKeys(function ($item) {
        return [Carbon::parse($item->date)->format('D') => gmdate('H:i', $item->total_time)];
      });

    return response()->json($summary);
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
  public function deleteWeekData(Request $request)
  {
    $validated = $request->validate([
      'project_id' => 'required|integer',
      'client_id' => 'required|integer',
      'dates' => 'required|array',
      'dates.*' => 'date',
    ]);

    // Perform the deletion
    UserTasks::where('project_id', $validated['project_id'])
      ->where('client_id', $validated['client_id'])
      ->whereIn('timer_date', $validated['dates'])
      ->delete();

    return response()->json(['message' => 'Data deleted successfully']);
  }
}
