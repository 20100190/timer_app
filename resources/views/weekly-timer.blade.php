@extends('layouts.main')
<link rel="stylesheet" href="{{asset('css/timer.css')}}" media="all" />

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

  .error {
    border: 2px solid red;
    outline: none;
  }

  .error-message {
    color: red;
    font-size: 0.9em;
    margin-top: 4px;
    display: block;
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


  .notes-icon {
    position: relative;
    cursor: pointer;
  }

  .tooltip-text {
    visibility: hidden;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 4px;
    padding: 5px;
    position: absolute;
    bottom: 150%;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    z-index: 10;
    opacity: 0;
    transition: opacity 0.3s;
  }

  .notes-icon:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
  }

  .tooltipForm {
    display: none;
    position: absolute;
    top: 30px;
    left: 0;
    background-color: #fff;
    border: 1px solid #ccc;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    padding: 10px;
    border-radius: 4px;
    z-index: 1000;
  }

  .tooltipForm.active {
    display: block;
  }

  .tooltipForm input {
    width: 200px;
    padding: 5px;
    margin-bottom: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  .tooltipForm button {
    padding: 5px 10px;
    border: none;
    background-color: #007bff;
    color: #fff;
    border-radius: 4px;
    cursor: pointer;
  }

  .tooltipForm button:hover {
    background-color: #0056b3;
  }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.2/xlsx.full.min.js"></script>

@section('content')
<div style="margin-left: 0px">
  <nav id="sub-nav" class="pds-screen-only">
    <div class="pds-container">
      <ul class="sub-nav-tabs">
        <li><a data-analytics-element-id="sub-nav-time-timesheet" class="current" href="https://budgetwebform.harvestapp.com/time">Timesheet</a></li>
        <li>
          <div class="" style="margin-top:10px;">
            <select id="userSelect" name="user_select" class="searchable-user-select">
              <option value="">Select a User</option>
              @if(!empty($users))
              @foreach($users as $user)
              <option value="{{$user->id}}" @if($user->id == Auth::id()) selected @endif>{{$user->name}}</option>
              @endforeach
              @endif
            </select>
          </div>
        </li>

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
                <a href="javascript:void(0)" aria-label="Previous day" data-analytics-element-id="timesheet-navigate-previous-day" class="pds-button pds-button-icon test-previous previous-week"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="Arrow left icon">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                  </svg></a>
                <a href="javascript:void(0)" aria-label="Next day" data-analytics-element-id="timesheet-navigate-next-day" class="pds-button pds-button-icon test-next next-week"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="Arrow right icon">
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
              <div class="pds-button-toggle">
                <a class="pds-button js-nav-day test-nav-day" href="/timer" aria-label="Day view" data-analytics-element-id="timesheet-day-view">Day</a>
                <button class="pds-button pds-button-selected" tabindex="-1" aria-label="Week view" type="button" aria-disabled="true">Week</button>
              </div>
            </div>

          </div>
        </div>
      </header>
      <div class="day-view">
        <div class="weekly-action-bar">
          <div class="pds-table-wrapper">
            <table class="week-view-table pds-table">
              <thead>
                <tr>
                  <th class="client" style="width:15%; padding-left:16px">Client</th>
                  <th class="pic" style="width:15%;padding-left:16px">PIC</th>
                  <th class="name" style="width:15%;padding-left:16px">Project And Task</th>
                  <th class="start_stop" style="width:15%;padding-left:16px"></th>
                  <th class="day focused-day"><a class="js-backbone test-Monday js-day is-today has-completion-signal" href="/time/day/2024/12/16/5123637" aria-label="Monday, 16 December" data-tooltip="" data-tooltip-delay="500" data-tooltip-hide-on-hover="" aria-describedby="has-completion-signal-tooltip-Monday" data-analytics-element-id="timesheet-navigate-day-Monday">
                      Mon
                      <span>16 Dec</span>
                    </a>

                  </th>
                  <th class="day"><a class="js-backbone test-Tuesday js-day is-not-today" href="/timer" aria-label="Tuesday, 17 December" data-analytics-element-id="timesheet-navigate-day-Tuesday">
                      Tue
                      <span>17 Dec</span>
                    </a>
                  </th>
                  <th class="day"><a class="js-backbone test-Wednesday js-day is-not-today" href="/time/day/2024/12/18/5123637" aria-label="Wednesday, 18 December" data-analytics-element-id="timesheet-navigate-day-Wednesday">
                      Wed
                      <span>18 Dec</span>
                    </a>
                  </th>
                  <th class="day"><a class="js-backbone test-Thursday js-day is-not-today" href="/time/day/2024/12/19/5123637" aria-label="Thursday, 19 December" data-analytics-element-id="timesheet-navigate-day-Thursday">
                      Thu
                      <span>19 Dec</span>
                    </a>
                  </th>
                  <th class="day"><a class="js-backbone test-Friday js-day is-not-today" href="/time/day/2024/12/20/5123637" aria-label="Friday, 20 December" data-analytics-element-id="timesheet-navigate-day-Friday">
                      Fri
                      <span>20 Dec</span>
                    </a>
                  </th>
                  <th class="day"><a class="js-backbone test-Saturday js-day is-not-today" href="/time/day/2024/12/21/5123637" aria-label="Saturday, 21 December" data-analytics-element-id="timesheet-navigate-day-Saturday">
                      Sat
                      <span>21 Dec</span>
                    </a>
                  </th>
                  <th class="day"><a class="js-backbone test-Sunday js-day is-not-today" href="/time/day/2024/12/22/5123637" aria-label="Sunday, 22 December" data-analytics-element-id="timesheet-navigate-day-Sunday">
                      Sun
                      <span>22 Dec</span>
                    </a>
                  </th>
                  <th class="total"></th>
                  <th class="delete"></th>
                </tr>
              </thead>
              <tbody>
                <tr class="week-view-entry focused-entry" data-project-id="43039591" data-task-id="23639166">
                  <td id="project43039591-task23639166" class="name">
                    <div class="entry-client">Budget Webform</div>

                  </td>
                  <td id="project43039591-task23639166" class="name">
                    <div class="entry-pic">Budget Webform</div>

                  </td>
                  <td id="project43039591-task23639166" class="name">
                    <div class="entry-project">Budget Webform</div>

                    <div class="entry-task">Design</div>
                  </td>
                  <td id="project43039591-task23639166" class="start_stop_row">
                    <button data-id="26" data-is-running="0"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                      </svg>Start</button>
                  </td>
                  <td class="day is-today">
                    <input
                      class="pds-input js-compound-entry"
                      data-unique-name="day351_project43039591_task23639166"
                      type="text"
                      value=""
                      aria-label="Hours on Monday, 16 December"
                      aria-describedby="project43039591-task23639166">

                  </td>

                  <td class="day is-not-today"> <input class="pds-input js-compound-entry" data-unique-name="day352_project43039591_task23639166" type="text" value="" aria-label="Hours on Tuesday, 17 December" aria-describedby="project43039591-task23639166">
                  </td>
                  <td class="day is-not-today"> <input class="pds-input js-compound-entry" data-unique-name="day353_project43039591_task23639166" type="text" value="" aria-label="Hours on Wednesday, 18 December" aria-describedby="project43039591-task23639166">
                  </td>
                  <td class="day is-not-today"> <input class="pds-input js-compound-entry" data-unique-name="day354_project43039591_task23639166" type="text" value="" aria-label="Hours on Thursday, 19 December" aria-describedby="project43039591-task23639166">
                  </td>
                  <td class="day is-not-today"> <input class="pds-input js-compound-entry" data-unique-name="day355_project43039591_task23639166" type="text" value="" aria-label="Hours on Friday, 20 December" aria-describedby="project43039591-task23639166">
                  </td>
                  <td class="day is-not-today"> <input class="pds-input js-compound-entry" data-unique-name="day356_project43039591_task23639166" type="text" value="" aria-label="Hours on Saturday, 21 December" aria-describedby="project43039591-task23639166">
                  </td>
                  <td class="day is-not-today"> <input class="pds-input js-compound-entry" data-unique-name="day357_project43039591_task23639166" type="text" value="" aria-label="Hours on Sunday, 22 December" aria-describedby="project43039591-task23639166">
                  </td>
                  <td class="total">
                  </td>
                  <td class="delete js-end-of-week-row ">
                    <button id="remove-row-43039591-23639166-view28" class="pds-button pds-button-sm pds-button-icon js-remove-row" aria-label="Delete row" type="button" aria-expanded="false" data-analytics-element-id="timesheet-week-remove-row" data-popover="">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="X icon">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                      </svg>
                    </button>
                    <div class="popover is-down-right pds-text-center pds-p-md pds-text-sm popover-week-delete" aria-labelledby="remove-row-43039591-23639166-view28" aria-hidden="true">
                      <p class="pds-mb-sm">Delete time entries from this timesheet?</p>
                      <button class="pds-button pds-button-sm pds-button-danger pds-mb-xs pds-w-full js-confirm-delete-entry" type="button" aria-label="Confirm delete" data-analytics-element-id="timesheet-week-remove-row-confirm">Delete time entries</button>
                      <button class="pds-button pds-button-sm pds-w-full js-cancel-delete-entry" type="button" data-analytics-element-id="timesheet-week-remove-row-cancel">Cancel</button>
                    </div>
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td class="name">
                    <div class="pds-flex-list pds-gap-sm">
                      <div>
                        <button class="pds-button js-new-week-row time-tracker" type="button" data-analytics-element-id="timesheet-week-new-row">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                          </svg>
                          Add row
                        </button>
                        <button class="pds-button pds-button-primary test-week-view-save js-save" type="button" aria-label="Save changes to timesheet" disabled data-analytics-element-id="timesheet-week-save">Save</button>
                      </div>

                    </div>
                  </td>
                  <td class="">
                  </td>
                  <td class="">
                  </td>
                  <td class="">
                  </td>
                  <td class="day today test-day-total-1">1:00
                  </td>
                  <td class="day is-not-today test-day-total-2">0
                  </td>
                  <td class="day is-not-today test-day-total-3">0
                  </td>
                  <td class="day is-not-today test-day-total-4">0
                  </td>
                  <td class="day is-not-today test-day-total-5">0
                  </td>
                  <td class="day is-not-today test-day-total-6">0
                  </td>
                  <td class="day is-not-today test-day-total-0">0
                  </td>
                  <td class="total">1:00</td>
                  <td class="delete">&nbsp;</td>
                </tr>
              </tfoot>
            </table>
          </div>



        </div>
        <div class="week-view">

        </div>
        <div id="new-entry-dialog" aria-labelledby="modal-time-entry-title" class="pds-dialog dialogue" role="dialog">
          <h1 id="modal-time-entry-title" class="pds-dialog-title">
            Add row to this time sheet
          </h1>

          <div class="js-editor">
            <form class="day-entry-editor" id="create_form" data-url="{{ route('startWeekTimer') }}">
              <div class="pds-mb-sm js-projects">
                <label class="pds-label pds-display-inline-block">Project / Task</label>

                <div class="pds-fl-right js-project-info"></div>
                <div id="calendar-recurring-event-popover-wrapper" class="pds-position-relative" data-popover-placement="right">
                  <div class="dialogue-row">
                    <select id="clientSelect" name="client_select" class="searchable-select">
                    </select>
                  </div>
                  <div class="dialogue-row" style="margin-top:10px;">
                    <select id="projectSelect" name="task_select" class="searchable-select" disabled>
                    </select>
                  </div>
                </div>
              </div>

              <input type="hidden" id="taskDate" name="taskDate">
              <input type="hidden" id="user_id" name="user_id">
              <div class="pds-flex-list pds-gap-md pds-justify-between pds-mt-md js-form-buttons">
                <div class="pds-flex-list@xs-stretch">
                  <button type="submit" id="startTimerButton" class="pds-button pds-button-primary js-submit" data-analytics-element-id="timesheet-day-entry-editor-start-timer-or-save-entry">Start timer</button>
                  <button type="button" class="pds-button js-close" id="cancelButton" aria-label="Cancel and close dialog" data-analytics-element-id="timesheet-day-entry-editor-cancel">Cancel</button>
                  <span class="form-loading js-loading"></span>
                  <span class="form-success js-success">Saved!</span>
                </div>

                <!-- <div class="pds-flex-list pds-ml-auto">
                <button type="button" class="pds-button-link pds-flex-list js-calendar-button">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                  </svg>
                  Pull in a calendar event
                </button>

                <button class="pds-button-link pds-color-muted js-change-date" type="button" hidden="">
                  Change date
                </button>
                <input class="pds-input pds-input-sm spent-at js-spent-at-field" name="spent_at" autocomplete="off" data-newdatepicker="" hidden="">

              </div> -->
              </div>

              <div class="expenses-form-delete-confirmation pds-flex-list pds-justify-end pds-button-input-height-md pds-text-sm pds-mt-md js-entry-delete-confirmation" hidden="">
                <span class="pds-mr@md-sm">Permanently delete this time entry?</span>
                <div class="pds-flex-list@xs-stretch">
                  <button class="pds-button pds-button-sm pds-button-danger js-confirm-delete-entry" type="button" data-analytics-element-id="timesheet-day-entry-editor-delete-confirm">Delete time entry</button>
                  <button class="pds-button pds-button-sm js-cancel-delete-entry" type="button" data-analytics-element-id="timesheet-day-entry-editor-delete-cancel">Cancel</button>
                </div>
              </div>

              <div class="pdsf-card pdsf-card-warning pds-text-center pds-mt-md js-timer-start-confirmation" hidden="">
                This is not today’s timesheet. Are you sure you want to start this timer?
                <div class="pds-flex-list pds-justify-center pds-mt-sm">
                  <button class="pds-button pds-button-sm pds-button-primary js-cancel-start-timer" type="button" data-analytics-element-id="timesheet-day-entry-editor-start-timer-cancel">Cancel</button>
                  <button class="pds-button pds-button-sm js-confirm-start-timer" type="button" data-analytics-element-id="timesheet-day-entry-editor-start-timer-not-today">Yes, start this timer on Friday</button>
                  <button class="pds-button pds-button-sm js-confirm-start-timer-today" type="button" data-analytics-element-id="timesheet-day-entry-editor-start-timer-today">Start timer on today’s date</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="pds-toast pds-toast-notice d-none"><span>Processing...</span></div>
  <div class="pds-toast pds-toast-success d-none"><span>Saved.</span></div>
  <script>
    var imagesUrl = '{{ URL::asset(' / image ') }}';
  </script>
  <script src=" {{ asset('js/weekly-timer.js') . '?p=' . rand()  }}"></script>

  <script src=" {{ asset('js/budgetWebform.js') . '?p=' . rand()  }}"></script>
  @endsection