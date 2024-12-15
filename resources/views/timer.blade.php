@extends('layouts.main')

<style type="text/css">
  button {
    cursor: pointer;
  }

  .inner-container {
    margin: 20px;
  }

  .date-navigator {
    display: flex;
    flex-direction: row;
    align-items: baseline;
    gap: 20px;
  }

  .action-bar {
    display: flex;
    flex-direction: row;
    gap: 20px;
  }

  .time-tracker {
    background-color: green;
    color: white;
    width: 50px;
    height: 50px;
  }

  .week-view ul {
    margin: 0;
    display: flex;
    flex-direction: row;
  }

  .week-view li {
    list-style: none;
  }

  .day-tasks {
    margin-top: 20px;
    border-collapse: collapse;
  }

  .day-tasks th,
  .day-tasks td {
    padding: 10px;
    border: 1px solid #ccc;
    text-align: left;
  }

  .dialogue {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 20px;
    background-color: white;
    border: 1px solid #ccc;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  }

  .dialogue-inner {
    display: grid;
    grid-template-columns: 100px 200px;
    gap: 10px;
    margin-bottom: 20px;
  }

  .dialogue-row {
    display: contents;
  }

  .dialogue-row label {
    grid-column: 1;
    padding-right: 10px;
  }

  .dialogue-row input,
  .dialogue-row select {
    grid-column: 2;
    width: 100%;
  }

  .dialogue-actions {
    display: flex;
    flex-direction: row;
    gap: 20px;
  }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.2/xlsx.full.min.js"></script>

@section('content')   

<div style="margin-left: 0px">
  <div class="inner-container">
    <div class="date-navigator">
      <nav>
        <button class="previous-day">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            aria-label="Arrow left icon">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
          </svg>
        </button>
        <button class="next-day">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            aria-label="Arrow right icon">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </button>
      </nav>
      <h1>
        <span class="selected-date">Saturday, 14 Dec</span>
      </h1>
    </div>

    <div class="action-bar">
      <button class="time-tracker">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"></line>
          <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
      </button>
      <div class="week-view">
        <ul>
          <li><button><span class="day-name">Sun</span><br><span class="day-time">0:00</span></button></li>
          <li><button><span class="day-name">Mon</span><br><span class="day-time">0:00</span></button></li>
          <li><button><span class="day-name">Tue</span><br><span class="day-time">0:00</span></button></li>
          <li><button><span class="day-name">Wed</span><br><span class="day-time">0:00</span></button></li>
          <li><button><span class="day-name">Thu</span><br><span class="day-time">0:00</span></button></li>
          <li><button><span class="day-name">Fri</span><br><span class="day-time">0:00</span></button></li>
          <li><button><span class="day-name">Sat</span><br><span class="day-time">0:00</span></button></li>
        </ul>
      </div>
    </div>

    <div class="tasks-view">
      <table class="day-tasks">
        <thead>
          <tr>
            <th>Project</th>
            <th>Client</th>
            <th>Time</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <div class="dialogue">
      <div class="dialogue-inner">
        <div class="dialogue-row">
          <label for="clientSelect">Client (*)</label>
          <select id="clientSelect" class="searchable-select">
          </select>
        </div>
        <div class="dialogue-row">
          <label for="projectSelect">Project (*)</label>
          <select id="projectSelect" class="searchable-select" disabled>
          </select>
        </div>
        <div class="dialogue-row">
          <label for="timeInput">Add time</label>
          <input type="text" id="timeInput" placeholder="h:mm" pattern="^\d+:\d{2}$">
        </div>
      </div>
      <div class="dialogue-actions">
        <button id="startTimerButton">Start Timer</button>
        <button id="cancelButton">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>var imagesUrl = '{{ URL::asset('/image') }}';</script>
<script src=" {{ asset('js/budgetWebform.js') . '?p=' . rand()  }}"></script>
<script src="{{ asset('js/timer.js')}}"></script>
@endsection