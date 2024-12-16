@extends('layouts.main')

<style type="text/css">
/* Global Styles */
:root {
  --primary-bg: #f4f7fc;
  --secondary-bg: #ffffff;
  --primary-color: #4caf50;
  --hover-color: #45a049;
  --border-color: #e0e4eb;
  --text-color: #333;
}

body {
  font-family: 'Segoe UI', Tahoma, sans-serif;
  background-color: var(--primary-bg);
  color: var(--text-color);
  line-height: 1.6;
  margin: 0;
  padding: 0;
}

h1 {
  font-size: 2rem;
  color: var(--primary-color);
  margin: 10px 0;
}

/* Buttons */
button {
  background-color: var(--primary-color);
  color: white;
  border: none;
  border-radius: 6px;
  padding: 8px 12px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  transition: all 0.3s ease;
}

button:hover {
  background-color: var(--hover-color);
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

/* Table Design */
.day-tasks {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  background-color: var(--secondary-bg);
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.day-tasks th, 
.day-tasks td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid var(--border-color);
}

.day-tasks tr:nth-child(even) {
  background-color: var(--primary-bg);
}

.day-tasks tr:hover {
  background-color: #e8f5e9;
  cursor: pointer;
}

/* Navigation and Week View */
/* Align arrows, + button, and week days on the same line */
/* Navigation and Date Container */
.date-navigator {
  display: flex;
  align-items: center;
  gap: 10px;
  justify-content: flex-start; /* Align items to the left */
  margin-bottom: 10px;
}

.nav-buttons {
  display: flex;
  gap: 10px;
}

.date-title {
  font-size: 2rem;
  color: var(--text-color);
  margin: 0;
}

/* Week view container */
.week-view {
  display: flex;                /* Enable horizontal alignment */
  align-items: center;          /* Vertically center all items */
  justify-content: flex-start;  /* Align items to the left */
  gap: 10px;                    /* Add spacing between elements */
  margin-top: 10px;             /* Add spacing above */
  flex-wrap: nowrap;            /* Prevent wrapping to a new line */
}

/* Week days container */
.week-view ul {
  display: flex;                /* Keep day buttons inline */
  align-items: center;          /* Align vertically */
  gap: 10px;                    /* Space between day buttons */
  margin: 0;
  padding: 0;
  list-style: none;             /* Remove bullets */
}

/* Individual day buttons */
.week-view button {
  background-color: var(--secondary-bg);
  color: var(--primary-color);
  border: 1px solid var(--border-color);
  border-radius: 6px;
  padding: 6px 8px;
  transition: background-color 0.3s ease;
  white-space: nowrap;          /* Prevent button content wrapping */
}

.week-view button:hover {
  background-color: var(--hover-color);
  color: white;
}

/* + Button */
.time-tracker {
  background-color: var(--primary-color);
  color: white;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  margin-left: 10px; /* Space after day names */
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  transition: transform 0.2s ease;
}

.time-tracker:hover {
  transform: scale(1.1);
}

/* Dialogue Popup */
.dialogue {
  display: none;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: var(--secondary-bg);
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.dialogue label {
  margin-bottom: 5px;
}

.dialogue input, 
.dialogue select {
  padding: 8px;
  width: 100%;
  border: 1px solid var(--border-color);
  border-radius: 4px;
}

.dialogue-actions {
  display: flex;
  justify-content: space-between;
  margin-top: 10px;
}

/* Responsive Design */
@media (max-width: 768px) {
  .date-navigator {
    flex-direction: column;
    align-items: center;
  }

  .week-view ul {
    flex-direction: column;
    align-items: center;
  }

  .day-tasks th, .day-tasks td {
    font-size: 0.9rem;
  }

  button {
    padding: 6px 10px;
  }
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