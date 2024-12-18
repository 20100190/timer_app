@extends('layouts.main')
<link rel="stylesheet" href="https://cache.harvestapp.com/static/styles-F5WBYAS5.css" media="all" />

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

  .small-button {
    font-size: 12px;
    padding: 4px 8px;
    border: 1px solid #0078D4;
    background-color: #f0f8ff;
    color: #0078D4;
    cursor: pointer;
    border-radius: 4px;
    margin-left: 8px;
  }

  .small-button:hover {
    background-color: #e6f2ff;
  }

  .hidden-date-picker {
    position: absolute;
    visibility: hidden;
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
    justify-content: flex-start;
    /* Align items to the left */
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
    display: flex;
    /* Enable horizontal alignment */
    align-items: center;
    /* Vertically center all items */
    justify-content: flex-start;
    /* Align items to the left */
    gap: 10px;
    /* Add spacing between elements */
    margin-top: 10px;
    /* Add spacing above */
    flex-wrap: nowrap;
    /* Prevent wrapping to a new line */
  }

  /* Week days container */
  .week-view ul {
    display: flex;
    /* Keep day buttons inline */
    align-items: center;
    /* Align vertically */
    gap: 10px;
    /* Space between day buttons */
    margin: 0;
    padding: 0;
    list-style: none;
    /* Remove bullets */
  }

  /* Individual day buttons */
  .week-view button {
    background-color: var(--secondary-bg);
    color: var(--primary-color);
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 6px 8px;
    transition: background-color 0.3s ease;
    white-space: nowrap;
    /* Prevent button content wrapping */
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
    margin-left: 10px;
    /* Space after day names */
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

    .day-tasks th,
    .day-tasks td {
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
  <nav id="sub-nav" class="pds-screen-only">
    <div class="pds-container">
      <ul class="sub-nav-tabs">
        <li><a data-analytics-element-id="sub-nav-time-timesheet" class="current" href="https://budgetwebform.harvestapp.com/time">Timesheet</a></li>

      </ul>
    </div>
  </nav>

  <div style="margin: 20px">
    <div class="inner-container">

      <header class="day-view-header">
        <div class="js-flash-wrap">
          <div id="pds-alerts">
          </div>
        </div>

        <div class="js-timesheet-header">
          <div class="pds-flex-list pds-justify-between pds-gap-sm">
            <div class="pds-flex-list pds-gap-sm">
              <nav class="pds-button-toggle pds-screen-only">
                <a href="javascript:void(0)" aria-label="Previous day" data-analytics-element-id="timesheet-navigate-previous-day" class="pds-button pds-button-icon test-previous previous-day"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="Arrow left icon">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                  </svg></a>
                <a href="javascript:void(0)" aria-label="Next day" data-analytics-element-id="timesheet-navigate-next-day" class="pds-button pds-button-icon test-next next-day"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="Arrow right icon">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                  </svg></a>
              </nav>
              <h1><span class="pds-weight-normal selected-date">Monday, 16 Dec</span><span class="pds-weight-normal pds-text-lg pds-print-only">(Timesheet for Shiza Noor1800)</span></h1>

            </div>
            <div class="pds-flex-list pds-screen-only">
              <button type="button" id="calendar-button" aria-label="Change date" aria-haspopup="true" aria-expanded="false" data-analytics-element-id="timesheet-navigate-change-date" data-popover="true" class="pds-button pds-button-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="Calendar icon">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
              </button>
              <input type="date" id="date-picker" class="hidden-date-picker">
              <div class="pds-button-toggle"><button type="button" tabindex="-1" title="Day View" aria-label="Day view" aria-disabled="true" class="pds-button pds-button-selected">Day</button><a href="/timer/weekly" aria-label="Week view" data-analytics-element-id="timesheet-week-view" class="pds-button">Week</a></div>
            </div>
          </div>
        </div>
      </header>
      <div class="day-view">
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
</div>
<script>
  var imagesUrl = '{{ URL::asset(' / image ') }}';
</script>
<script src="{{ asset('js/timer.js')}}"></script>
<script src=" {{ asset('js/budgetWebform.js') . '?p=' . rand()  }}"></script>
@endsection