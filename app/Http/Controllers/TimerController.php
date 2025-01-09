<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Project;
use App\Task;
use App\TaskName;
use App\UserTasks;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use phpDocumentor\Reflection\Types\Null_;

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

  public function getClientsNprojects()
  {
    $clients = Client::select('id', 'name') // Select only the 'id' and 'name' fields from the 'clients' table
      ->with([
        'projects' // Load the 'projects' relationship but only select 'id' and 'project_name' columns
      ])
      ->get(); // Fetch the data
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
  public function getTaskList()
  {
    $projects = TaskName::select('id', 'name')
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
    // DB::enableQueryLog();
    try {
      $validatedData = $request->validate([
        'client_select' => 'required|integer|exists:project,id',
        'task_select' => 'required|integer|exists:task,id',
        'taskDate' => 'required',
        'notes' => 'nullable',
        'timeInput' => 'nullable'
      ]);
      $project = Project::where('id', $request->input('client_select'))->first();
      if ($project) {
        $taskDate = preg_replace('/\s*\(.*?\)$/', '', $request->input('taskDate')); // "Tue Dec 24 2024 00:09:20 GMT+0500"
        // Get the time input from the user (e.g., 1:02)
        $timeInSeconds = 0;
        $timeInput = $request->input('timeInput');
        if ($timeInput) {
          // Call the helper function to convert to seconds
          $timeInSeconds = $this->convertTimeToSeconds($timeInput);
        }

        // Convert it to Y-m-d format
        $formattedDate = Carbon::parse($taskDate)->format('Y-m-d');
        $formattedDateTime = Carbon::parse($taskDate)->format('Y-m-d H:i:s'); // Output: "2024-12-24 00:05:56"
        $userTasks = UserTasks::where([
          'client_id' => $project->client_id,
          'project_id' => $validatedData['client_select'],
          'task_id' => $validatedData['task_select'],
          'user_id' => Auth::id(),
          'notes' => null,
          'is_weekly_only' => 1,
          'timer_date' => $formattedDate
        ])->first();
        if ($userTasks) {
          $userTasks->update(
            [
              'user_id' => Auth::id(),
              'client_id' => $project->client_id,
              'project_id' => $validatedData['client_select'],
              'task_id' => $validatedData['task_select'],
              'notes' => $validatedData['notes'],
              'timer' => $timeInSeconds,
              'started_at' => Carbon::now()->format('Y-m-d H:i:s'), // Current date and time
              'timer_date' => $formattedDate,     // Today's date
              'is_running' => 1,
              'is_weekly_only' => 0, // Updated to pass as an integer
            ]
          );
        } else {
          $userTasks = UserTasks::create([
            'user_id' => Auth::id(),
            'client_id' => $project->client_id,
            'project_id' => $validatedData['client_select'],
            'task_id' => $validatedData['task_select'],
            'notes' => $validatedData['notes'],
            'timer' => $timeInSeconds,
            'started_at' => Carbon::now()->format('Y-m-d H:i:s'), // Current date and time
            'timer_date' => $formattedDate,     // Today's date
            'is_running' => 1,
            'is_weekly_only' => 0
          ]);
        }

        $otherTasks = UserTasks::where('user_id',  Auth::id()) // Assuming user_id links tasks to the user
          ->where('id', '!=', $userTasks->id) // Exclude the current task
          ->where('timer_date', $formattedDate) // Exclude the current task
          ->get();
        if ($otherTasks) {
          foreach ($otherTasks as $task) {
            if ($task->is_running && $task->started_at) {

              $diffInSeconds = $task->started_at->diffInSeconds(now());
              $task->timer = ($task->timer ?? 0) + $diffInSeconds;
              $task->is_running = false;
              $task->started_at = null;
              $task->save();
            }
          }
        }
        return response()->json([
          'message' => 'Timer started successfully',
          'task_id' => $userTasks->id
        ], 201);
      } else {
        return response()->json([
          'message' => 'Failed to start timer',
          'error' => 'No project found'
        ], 500);
      }
    } catch (\Illuminate\Validation\ValidationException $ve) {
      Log::error('Validation Error: ' . json_encode($ve->errors()));
      return response()->json([
        'message' => 'Validation failed',
        'errors' => $ve->errors()
      ], 422);
    } catch (\Exception $e) {
      Log::error('Error starting timer: ' . $e->getMessage());
      Log::error('Error Trace: ' . $e->getTraceAsString());
      return response()->json([
        'message' => 'Failed to start timer',
        'error' => $e->getMessage()
      ], 500);
    }
  }
  public function initWeekTimer(Request $request)
  {
    try {
      $validatedData = $request->validate([
        'client_select' => 'required|integer|exists:project,id',
        'task_select' => 'required|integer|exists:task,id',
        'taskDate' => 'required',
        'notes' => 'nullable',
        'timeInput' => 'nullable'
      ]);
      $project = Project::where('id', $request->input('client_select'))->first();
      if ($project) {
        $taskDate = preg_replace('/\s*\(.*?\)$/', '', $request->input('taskDate')); // "Tue Dec 24 2024 00:09:20 GMT+0500"
        // Parse the date and calculate the start (Monday) and end (Sunday) of the week
        $startOfWeek = Carbon::parse($taskDate)->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::parse($taskDate)->endOfWeek(Carbon::SUNDAY);
        $weekDates = [];
        for ($date = $startOfWeek; $date->lte($endOfWeek); $date->addDay()) {
          $weekDates[] = $date->toDateString(); // Format: "YYYY-MM-DD"
        }
        $finalDateArray = [];
        foreach ($weekDates as $date) {
          $formattedDate = Carbon::parse($date)->format('Y-m-d');
          $finalDateArray[] = [
            'user_id' => Auth::id(),
            'client_id' => $project->client_id,
            'project_id' => $validatedData['client_select'],
            'task_id' => $validatedData['task_select'],
            'notes' => null,
            'timer' => 0,
            'started_at' => null, // Current date and time
            'timer_date' => $formattedDate,     // Today's date
            'is_running' => 0,
            'is_weekly_only' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), // Current date and time
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), // Current date and time
          ];
        }
        UserTasks::insert($finalDateArray);
        return response()->json([
          'message' => 'Timer started successfully',
        ], 201);
      } else {
        return response()->json([
          'message' => 'Failed to start timer',
          'error' => 'No project found'
        ], 500);
      }
    } catch (\Illuminate\Validation\ValidationException $ve) {
      Log::error('Validation Error: ' . json_encode($ve->errors()));
      return response()->json([
        'message' => 'Validation failed',
        'errors' => $ve->errors()
      ], 422);
    } catch (\Exception $e) {
      Log::error('Error starting timer: ' . $e->getMessage());
      Log::error('Error Trace: ' . $e->getTraceAsString());
      return response()->json([
        'message' => 'Failed to start timer',
        'error' => $e->getMessage()
      ], 500);
    }
  }



  /**
   * Generate an array of dates from the start to the end of the week.
   */
  private function generateWeekDates(Carbon $startOfWeek, Carbon $endOfWeek): array
  {
    $dates = [];
    for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
      $dates[] = $date->toDateString(); // Format: "YYYY-MM-DD"
    }
    return $dates;
  }

  public function getTasks($date)
  {
    $userId = Auth::id(); // Get the authenticated user's ID
    $tasks = UserTasks::whereDate('timer_date', $date)->where('user_id', $userId)->where('is_weekly_only', 0)
      ->select('id', 'user_id', 'client_id', 'project_id', 'timer', 'started_at', 'timer_date', 'is_running', 'task_id', 'notes')->with([
        'username',
        'client',
        'project',
        'project.picInitial',
        'task'
      ])
      ->get();

    return response()->json($tasks);
  }
  private function convertTimeToSeconds(string $timeInput): int
  {
    [$hours, $minutes] = array_pad(explode('.', $timeInput), 2, 0);
    return ((int)$hours * 3600) + ((int)$minutes * 60);
  }

  public function getTask($taskId)
  {
    $userId = Auth::id(); // Get the authenticated user's ID
    $tasks = UserTasks::where('id', $taskId)->where(['user_id' => $userId, 'is_weekly_only' => 0])
      ->select('id', 'user_id', 'client_id', 'project_id', 'timer', 'started_at', 'timer_date', 'is_running', 'task_id', 'notes')
      ->first();

    return response()->json($tasks);
  }
  public function getWeeklyTasks($date)
  {
    $userId = Auth::id(); // Get the authenticated user's ID

    // Parse the date and calculate the start (Monday) and end (Sunday) of the week
    $startOfWeek = Carbon::parse($date)->startOfWeek(Carbon::MONDAY);
    $endOfWeek = Carbon::parse($date)->endOfWeek(Carbon::SUNDAY);

    // Fetch tasks for the week, grouped by date, project, and client
    // Updated query without project_name and client_name
    $tasks = UserTasks::whereBetween('timer_date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
      ->select(
        'id',
        'user_id',
        'client_id',
        'project_id',
        'timer',
        'started_at',
        'timer_date',
        'is_running',
        'task_id',
        DB::raw('SUM(timer) as total_time')
      )
      ->with([
        'username',
        'client',
        'project',
        'task',
        'project.picInitial',
      ])
      ->groupBy(
        'timer_date',
        'task_id',
        'project_id',
        'client_id',
        'created_at',
        'user_id',
        'started_at',
        'is_running',
        'id',
        'timer'
      )
      ->orderBy('created_at', 'ASC')
      ->get();

    // Initialize the response structure
    $result = [];

    // Get all unique combinations of project_id, client_id, task_id, and notes
    $projectsClientsTasksNotes = $tasks->map(function ($task) {
      return "{$task->project_id}+{$task->client_id}+{$task->task_id}";
    })->unique();

    // Iterate over each combination of project (client), task, and notes
    foreach ($projectsClientsTasksNotes as $combination) {
      $result[$combination] = [];

      // Split the combination back into IDs and notes
      [$projectId, $clientId, $taskId] = explode('+', $combination);

      // Extract tasks for the current combination
      $tasksForCombination = $tasks->filter(function ($task) use ($projectId, $clientId, $taskId) {
        return $task->project_id == $projectId
          && $task->client_id == $clientId
          && $task->task_id == $taskId;
      });

      // Use the first task to retrieve additional task details
      $sampleTask = $tasksForCombination->first();
      // Iterate through each day of the week
      for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
        $currentDate = $date->toDateString();
        $dayName = $date->format('l'); // Get the day name (e.g., Monday)

        // Find tasks for the current date
        $dayTask = $tasksForCombination->first(function ($task) use ($currentDate) {
          return Carbon::parse($task->timer_date)->toDateString() === $currentDate;
        });
        $notes = UserTasks::where(['timer_date' => $currentDate, 'project_id' => $projectId, 'client_id' => $clientId, 'task_id' => $taskId,])->select('notes')->first();
        // Append the task data for the current day=
        $result[$combination][] = [
          'task_id' => $taskId, // Use the task_id for this combination
          'day' => $dayName,
          'time' => $dayTask->total_time ?? 0, // Default to 0 if no tasks found
          'project_id' => $projectId,
          'project_name' => $sampleTask->project->project_name ?? '',
          'project_pic' => ($sampleTask->project && $sampleTask->project->picInitial && $sampleTask->project->picInitial->initial ? $sampleTask->project->picInitial->initial : ''),
          'client_name' => $sampleTask->client->name ?? '',
          'task_name' => $sampleTask->task->name ?? '',
          'client_id' => $clientId,
          'notes' => $notes->notes ?? "", // Include the notes
          'date' => $currentDate, // Use the current date for the week
        ];
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
    // Stop all other tasks for the user
    UserTasks::where('user_id', $task->user_id) // Assuming user_id links tasks to the user
      ->where('id', '!=', $taskId) // Exclude the current task
      ->where('timer_date', $task->timer_date) // Exclude the current task
      ->update([
        'is_running' => false,
        'started_at' => null, // Clear started_at for stopped tasks
      ]);
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

  public function getWeekSummary($date)
  {
    // $date = "2024-12-25";
    $carbonDate = Carbon::parse($date);
    // Set week start as Sunday and week end as Saturday
    Carbon::setWeekStartsAt(Carbon::SUNDAY);
    Carbon::setWeekEndsAt(Carbon::SATURDAY);
    // Start and end of the week for the parsed date
    $weekStart = $carbonDate->copy()->startOfWeek();
    $weekEnd = $carbonDate->copy()->endOfWeek();

    // Dump results
    // dd($date, $carbonDate, $weekStart, $weekEnd);
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
  public function saveTasks(Request $request)
  {
    $validatedData = $request->validate([
      'tasks' => 'required|array',
    ]);
    DB::enableQueryLog();
    foreach ($validatedData['tasks'] as $taskData) {
      $timeValue = $taskData['time']; // Assuming the input timeValue is passed via a request
      if (preg_match('/^\d{1,2}(\.\d{1,2})?$/', $timeValue)) {
        // If the input is a valid decimal hour format
        $hours = (float)$timeValue; // Convert the input directly to float
        $totalTimeInSeconds = $hours * 3600; // Convert hours to seconds
      } elseif (preg_match('/^\d{1,2}$/', $timeValue)) {
        // If only hours are entered as integers, append ":00"
        $timeValue = $timeValue . ':00';
        [$hours, $minutes] = explode(':', $timeValue);
        $totalTimeInSeconds = ($hours * 3600) + ($minutes * 60);
      } elseif (preg_match('/^\d{1,2}:\d{1,2}$/', $timeValue)) {
        // If time is in "HH:mm" format
        [$hours, $minutes] = explode(':', $timeValue);
        $totalTimeInSeconds = ($hours * 3600) + ($minutes * 60);
      } else {
        // Handle invalid input
        throw new InvalidArgumentException('Invalid time format. Please enter time in HH:mm or decimal hours.');
      }

      $formattedDate = Carbon::parse($taskData['date'])->format('Y-m-d');
      UserTasks::updateOrCreate(
        [
          'client_id' => $taskData['client_id'],
          'project_id' => $taskData['project_id'],
          'task_id' => $taskData['task_id'],
          'user_id' => Auth::id(),
          'timer_date' => $formattedDate
        ],
        [
          'timer' => $totalTimeInSeconds,
          'client_id' => $taskData['client_id'],
          'project_id' => $taskData['project_id'],
          'task_id' => $taskData['task_id'],
          'timer_date' => $formattedDate,     // Today's date
          'is_weekly_only' => 0
        ]
      );
    }
    // dd(DB::getQueryLog());

    return response()->json(['message' => 'Tasks saved successfully.']);
  }
  public function updateTimer(Request $request, $id)
  {
    // DB::enableQueryLog();
    try {
      $validatedData = $request->validate([
        'client_select' => 'required|integer|exists:project,id',
        'task_select' => 'required|integer|exists:task,id',
        'taskDate' => 'required',
        'notes' => 'nullable',
        'timeInput' => 'nullable'
      ]);
      $project = Project::where('id', $request->input('client_select'))->first();
      if ($project) {
        $taskDate = preg_replace('/\s*\(.*?\)$/', '', $request->input('taskDate')); // "Tue Dec 24 2024 00:09:20 GMT+0500"
        // Get the time input from the user (e.g., 1:02)


        // Convert it to Y-m-d format
        $formattedDate = Carbon::parse($taskDate)->format('Y-m-d');
        $formattedDateTime = Carbon::parse($taskDate)->format('Y-m-d H:i:s'); // Output: "2024-12-24 00:05:56"
        $userTasks = UserTasks::where([
          'id' => $id
        ])->first();
        $timeInSeconds = 0;
        $timeInput = $request->input('timeInput');
        if ($timeInput) {
          // Call the helper function to convert to seconds
          $timeInSeconds = $this->convertTimeToSeconds($timeInput);
        } else {
          // Use the timer value from the database if it exists
          $timeInSeconds = $userTasks->timer;
        }
        if ($userTasks) {
          $userTasks->update(
            [
              'user_id' => Auth::id(),
              'client_id' => $project->client_id ?? $userTasks->client_id,
              'project_id' => $validatedData['client_select'] ?? $userTasks->project_id,
              'task_id' => $validatedData['task_select'] ?? $userTasks->task_id,
              'notes' => $validatedData['notes'],
              'timer' => $timeInSeconds,
              // 'started_at' => Carbon::now()->format('Y-m-d H:i:s'), // Current date and time
              // 'timer_date' => $formattedDate,     // Today's date
            ]
          );
        }

        return response()->json([
          'message' => 'Timer updated successfully',
          'task_id' => $userTasks->id
        ], 201);
      } else {
        return response()->json([
          'message' => 'Failed to start timer',
          'error' => 'No project found'
        ], 500);
      }
    } catch (\Illuminate\Validation\ValidationException $ve) {
      Log::error('Validation Error: ' . json_encode($ve->errors()));
      return response()->json([
        'message' => 'Validation failed',
        'errors' => $ve->errors()
      ], 422);
    } catch (\Exception $e) {
      Log::error('Error starting timer: ' . $e->getMessage());
      Log::error('Error Trace: ' . $e->getTraceAsString());
      return response()->json([
        'message' => 'Failed to start timer',
        'error' => $e->getMessage()
      ], 500);
    }
  }
  public function getRunningTaskList()
  {
    $userId = Auth::id(); // Get the authenticated user's ID
    $tasks = UserTasks::where('user_id', $userId)->where('is_weekly_only', 0)->where('is_running', 1)
      ->select('id', 'user_id', 'client_id', 'project_id', 'timer', 'started_at', 'timer_date', 'is_running', 'task_id', 'notes')->with([
        'username',
        'client',
        'project',
        'project.picInitial',
        'task'
      ])
      ->groupby('id', 'user_id', 'client_id', 'project_id', 'timer', 'started_at', 'timer_date', 'is_running', 'task_id', 'notes')
      ->get();

    return response()->json($tasks);
  }
  public function updateNotes(Request $request, $taskid, $projectid, $clientid, $date)
  {
    // DB::enableQueryLog();
    try {
      $validatedData = $request->validate([
        'notes' => 'nullable',
      ]);
      $taskDate = preg_replace('/\s*\(.*?\)$/', '', $request->input('taskDate')); // "Tue Dec 24 2024 00:09:20 GMT+0500"
      // Get the time input from the user (e.g., 1:02)


      // Convert it to Y-m-d format
      $formattedDateTime = Carbon::parse($date)->format('Y-m-d H:i:s'); // Output: "2024-12-24 00:05:56"
      $userTasks = UserTasks::where([
        'task_id' => $taskid,
        'project_id' => $projectid,
        'client_id' => $clientid,
        'timer_date' => $formattedDateTime,
      ])->get();
      if ($userTasks->isNotEmpty()) {
        foreach ($userTasks as $task) {
          $task->update(
            [
              'notes' => $validatedData['notes'],
            ]
          );
        }
      } else {
        $userTasks = UserTasks::create([
          'user_id' => Auth::id(),
          'client_id' => $clientid,
          'project_id' => $projectid,
          'task_id' => $taskid,
          'notes' => $validatedData['notes'],
          'timer' => 0,
          'started_at' => Null, // Current date and time
          'timer_date' => $formattedDateTime,     // Today's date
          'is_running' => 0,
          'is_weekly_only' => 1
        ]);
      }

      return response()->json([
        'message' => 'Notes updated successfully',
      ], 201);
    } catch (\Illuminate\Validation\ValidationException $ve) {
      Log::error('Validation Error: ' . json_encode($ve->errors()));
      return response()->json([
        'message' => 'Validation failed',
        'errors' => $ve->errors()
      ], 422);
    } catch (\Exception $e) {
      Log::error('Error starting timer: ' . $e->getMessage());
      Log::error('Error Trace: ' . $e->getTraceAsString());
      return response()->json([
        'message' => 'Failed to start timer',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
