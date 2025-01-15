const selectedDateEl = document.querySelector(".selected-date");
const weekViewButtons = document.querySelectorAll(".week-view button");
const previousDayButton = document.querySelector(".previous-day");
const taskDate = document.querySelector(".js-spent-at-display");
const taskNotes = document.querySelector(".entry-notes");
const nextDayButton = document.querySelector(".next-day");
const timeTrackerButton = document.querySelector(".time-tracker");
const dialogue = document.querySelector(".dialogue");
const clientSelect = document.getElementById("clientSelect");
const userSelect = document.getElementById("userSelect");
const projectSelect = document.getElementById("projectSelect");
const timeInput = document.getElementById("timeInput");
// const startTimerButton = document.getElementById("startTimerButton");
const cancelButton = document.getElementById("cancelButton");
const taskRows = document.querySelector(".day-tasks tbody");
const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

let currentUser = null;
userSelect.addEventListener("change", function () {
    const selecteduserId = this.value;
    currentUser = selecteduserId;
    $('#user_id').val(selecteduserId);
    updateUI();

});
let currentDate = new Date();
const urlParams = new URLSearchParams(window.location.search);
const dateParam = urlParams.get('date');
if (dateParam) {
    changeDay(dateParam);
    urlParams.delete('date');
    const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
    window.history.replaceState({}, document.title, newUrl);
}


let currentUserName = null;

const calendarButton = document.getElementById("calendar-button");
const datePicker = document.getElementById("date-picker");
calendarButton.addEventListener("click", () => {
    datePicker.showPicker(); // Trigger the date picker
});
datePicker.addEventListener("change", (event) => {
    const selectedDate = new Date(event.target.value);
    changeDay(selectedDate);

});
function formatDate(date) {
    const today = new Date();
    const isToday = date.toDateString() === today.toDateString();

    const options = { weekday: "long", day: "numeric", month: "short" };
    const formattedDate = date.toLocaleDateString("en-GB", options)
        .replace(/(\w+)\s(\d+)/, '$1, $2'); // Add comma after day

    return isToday
        ? `<strong>Today:</strong> ${formattedDate}`
        : `${formattedDate} <a href="javascript:void(0)" id="return-to-today" type="button" class="small-button" onClick="changeDay('today')">Return to today</a>`;
}
function formatDateForPopUp(date) {
    const today = new Date();
    const isToday = date.toDateString() === today.toDateString();

    const options = { weekday: "long", day: "numeric", month: "short" };
    const formattedDate = date.toLocaleDateString("en-GB", options)
        .replace(/(\w+)\s(\d+)/, '$1, $2'); // Add comma after day
    return formattedDate;
}
function getCurrentWeekRange(date) {
    const dayOfWeek = date.getDay();
    const startOfWeek = new Date(date);
    startOfWeek.setDate(date.getDate() - dayOfWeek);

    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6);

    return { startOfWeek, endOfWeek };
}
function changeDay(direction) {

    if (direction === "today") {
        // Reset to today's date
        currentDate = new Date();
    }
    else if (typeof direction === "number") {
        // Increment or decrement by direction
        currentDate.setDate(currentDate.getDate() + direction);
    }
    else if (!isNaN(Date.parse(direction))) {
        // Set currentDate to a specific valid date
        currentDate = new Date(direction);
    }
    else {
        console.error("Invalid direction:", direction);
        return;
    }

    updateUI(); // Update the UI with the new currentDate
}

function clearTable() {
    taskRows.innerHTML = "";
}

function createRow(rowData) {
    const row = document.createElement("tr");

    const clientCell = document.createElement("td");
    clientCell.innerHTML = rowData.client;
    row.appendChild(clientCell);

    const picCell = document.createElement("td");
    picCell.innerHTML = rowData.pic;
    row.appendChild(picCell);

    const projectCell = document.createElement("td");
    projectCell.innerHTML = rowData.project;
    row.appendChild(projectCell);

    const timeCell = document.createElement("td");
    timeCell.setAttribute('class', 'time-cell')
    timeCell.setAttribute("data-is-running", rowData.is_running);
    timeCell.textContent = rowData.time;
    row.appendChild(timeCell);

    const actionCell = document.createElement("td");
    const actionButton = document.createElement("button");
    actionButton.innerHTML = rowData.action;
    actionButton.setAttribute("data-id", rowData.id);
    actionButton.setAttribute("data-is-running", rowData.is_running);
    if (rowData.is_running == 1) {
        actionButton.setAttribute("class", "stopButton-style");
    } else {
        actionButton.setAttribute("class", "startButton-style");
    }
    const editButton = document.createElement("button");
    editButton.innerHTML = 'Edit';
    editButton.setAttribute("data-id", rowData.id);
    editButton.setAttribute("class", "pds-button pds-button-sm js-edit-entry");

    editButton.setAttribute("data-is-running", rowData.is_running);

    actionButton.addEventListener("click", function (e) {
        const button = e.target;
        const taskId = button.getAttribute("data-id");
        const isRunning = button.getAttribute("data-is-running") == "1";
        if (isRunning) {
            stopTask(taskId).then(() => {
                actionButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12 16 14"></polyline>
        </svg>Start`;
            });
        } else {
            startTask(taskId).then(() => {

                actionButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12" class="clock-running-minute-hand"></polyline>
          <polyline points="12 12 16 14" class="clock-running-hour-hand"></polyline>
        </svg>Stop`;
            });
        }
        updateUI();
        populateTasksForDate(currentDate);
    });

    actionCell.appendChild(actionButton);
    actionCell.appendChild(editButton);
    row.appendChild(actionCell);

    document.querySelector(".day-tasks tbody").appendChild(row);
}

async function startTask(taskId) {
    if (taskId) {
        const response = await fetch(`/timer/start-timer/${taskId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        });
        if (!response.ok) {
            return response.json().then((err) => {
                throw err;
            });
        }
        return await response.json();
    }

}

async function stopTask(taskId) {
    if (taskId) {

        const response = await fetch(`/timer/stop-timer/${taskId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        });
        if (!response.ok) {
            return response.json().then((err) => {
                throw err;
            });
        }
        return await response.json();
    }
}

function fetchUserName() {
    fetch("/timer/get-user")
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((userData) => {
            currentUserName = userData.name;
        })
        .catch((error) => {
            console.error("Error fetching user:", error);
            alert("Failed to load user information.");
        });
}

function populateClients(project_id = null) {
    clientSelect.innerHTML = '<option value="">Select a Client</option>';

    fetch("/timer/get-clients-and-projects")
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((clients) => {
            clients.forEach((client) => {
                // Create an optgroup for the client
                const optgroup = document.createElement("optgroup");
                optgroup.label = client.name;

                // Add each project under the optgroup
                client.projects.forEach((project) => {
                    const option = document.createElement("option");
                    option.value = project.id;
                    option.textContent = project.project_name;
                    optgroup.appendChild(option);
                });

                // Append the optgroup to the select element
                clientSelect.appendChild(optgroup);
                if (client.projects.some(project => project.id === project_id)) {
                    $(clientSelect).val(project_id);
                }
            });
        })
        .catch((error) => {
            console.error("Error fetching clients:", error);
            alert("Failed to load clients. Please try again.");
        });

}

function populateProjects(clientId) {
    projectSelect.innerHTML = '<option value="">Select a Project</option>';
    projectSelect.disabled = true;

    if (!clientId) return;

    fetch(`/timer/get-projects/${clientId}`)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((projects) => {
            if (projects.length > 0) {
                projects.forEach((project) => {
                    const option = document.createElement("option");
                    option.value = project.id;
                    option.setAttribute("data-name", project.project_name);
                    option.textContent = project.project_name;
                    projectSelect.appendChild(option);
                });

                projectSelect.disabled = false;
            } else {
                projectSelect.innerHTML =
                    '<option value="">No projects available</option>';
            }
        })
        .catch((error) => {
            console.error("Error fetching projects:", error);
            alert("Failed to load projects. Please try again.");
        });
}

function closeDialogue() {
    $('#timeInput').val('');
    $('#entry-notes').html('');
    $('#create_form').attr('data-url', '/timer/init-timer');
    $("#startTimerButton").text("Start Timer")
    clientSelect.innerHTML = "";
    projectSelect.innerHTML = "";
    projectSelect.disabled = true;
    taskNotes.innerHTML = "";
    dialogue.style.display = "none";
}

function openDialogue() {
    $('#taskDate').val(currentDate);
    dialogue.style.display = "block";
    clientSelect.innerHTML = "";
    projectSelect.innerHTML = "";
    projectSelect.disabled = true;
    taskDate.innerHTML = formatDateForPopUp(currentDate);
    populateClients();
    fetchUserName();
}

function parseTimeString(timeString) {
    if (!timeString || !/^\d{1,2}:\d{2}$/.test(timeString)) {
        return { hours: 0, minutes: 0 };
    }

    const [hours, minutes] = timeString.split(":").map(Number);
    return { hours, minutes };
}

function secondsToHHMM(totalSeconds) {
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);

    const hoursStr = hours.toString();
    const minutesStr = minutes.toString().padStart(2, "0");

    return `${hoursStr}:${minutesStr}`;
}
function secondtoHour(seconds) {
    const hours = (seconds / 3600).toFixed(2);
    return hours;
}

function populateTasksForDate(dateObject) {
    const dateFormatted = getLocalDateString(dateObject);
    if (currentUser) {
        var geturl = `/timer/get-tasks/${dateFormatted}/${currentUser}`;
    } else {
        var geturl = `/timer/get-tasks/${dateFormatted}`;
    }
    fetch(geturl)
        .then((response) => response.json())
        .then((tasks) => {
            const data = tasks.map((task) => {
                let startedAtTime = null;
                if (task.started_at) {
                    const isoString = task.started_at.replace(" ", "T") + "Z";
                    startedAtTime = new Date(isoString);
                }

                let elapsedSeconds;
                if (task.is_running && startedAtTime) {
                    const nowMs = Date.now();
                    const startedMs = startedAtTime.getTime();
                    const diffInSeconds = Math.floor(
                        (nowMs - startedMs) / 1000
                    );

                    elapsedSeconds = task.timer + diffInSeconds;
                } else {
                    elapsedSeconds = task.timer;
                }

                return {
                    id: task.id,
                    client: task.client.name,
                    pic: (task.project.pic_initial ? task.project.pic_initial.initial : ''),
                    project: `<div class="entry-details">
                    <div class="entry-project" style="font-weight: 550;">${task.project.project_name}</div>
                
                    <div class="entry-task">           ${task.task.name} 
                </div>

                        <div class="notes" style="    color: #1d1e1cb3;
                    font-size: 13px;
                    line-height: 1.25;
                    margin-top: 4px;
                    overflow-wrap: anywhere;">
                    ${task.notes ? task.notes : ''}
                        </div>
                    </div>`,
                    time: secondtoHour(elapsedSeconds),
                    action: task.is_running ? `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12" class="clock-running-minute-hand"></polyline>
          <polyline points="12 12 16 14" class="clock-running-hour-hand"></polyline>
        </svg>Stop` : `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12 16 14"></polyline>
        </svg>Start`,
                    is_running: task.is_running,
                };
            });

            clearTable();
            data.forEach(createRow);
        })
        .catch((error) => {
            console.error("Error fetching tasks:", error);
        });
}
function isAnyTaskRunning() {
    if (currentUser) {
        var runningTaskUrl = `/timer/get-running-tasks-list/${currentUser}`;
    } else {
        runningTaskUrl = `/timer/get-running-tasks-list`;
    }
    fetch(runningTaskUrl)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((runningTask) => {

        })
        .catch((error) => {
            console.error("Error fetching projects:", error);
            // alert("Failed to load projects. Please try again.");
        });
}

function populateAlerts() {
    if (isAnyTaskRunning()) {
        console.log("A task is running.");
    } else {
        console.log("No tasks are currently running.");
    }
}

function updateUI() {
    selectedDateEl.innerHTML = formatDate(currentDate,);

    const { startOfWeek, endOfWeek } = getCurrentWeekRange(currentDate);
    const daysOfWeek = Array.from(weekViewButtons);

    daysOfWeek.forEach((button) => (button.style.backgroundColor = ""));

    const selectedDay = currentDate.getDay();
    daysOfWeek[selectedDay].style.backgroundColor = "lightgreen";

    populateTasksForDate(currentDate);
    getWeekData();
    populateAlerts();
}

function getLocalDateString(dateObject) {
    const year = currentDate.getFullYear();
    let month = currentDate.getMonth() + 1;
    let day = currentDate.getDate();

    month = month < 10 ? "0" + month : month;
    day = day < 10 ? "0" + day : day;

    const formattedDate = `${year}-${month}-${day}`;
    return formattedDate;
}

function getUTCDateString(dateObject, include_time = true) {
    let carbon_date =
        dateObject.getUTCFullYear() +
        "-" +
        String(dateObject.getUTCMonth() + 1).padStart(2, "0") +
        "-" +
        String(dateObject.getUTCDate()).padStart(2, "0");

    if (include_time) {
        carbon_date =
            carbon_date +
            " " +
            String(dateObject.getUTCHours()).padStart(2, "0") +
            ":" +
            String(dateObject.getUTCMinutes()).padStart(2, "0") +
            ":" +
            String(dateObject.getUTCSeconds()).padStart(2, "0");
    }
    return carbon_date;
}

weekViewButtons.forEach((button, index) => {
    button.addEventListener("click", () => {
        const { startOfWeek } = getCurrentWeekRange(currentDate);
        const selectedDay = new Date(startOfWeek);
        selectedDay.setDate(startOfWeek.getDate() + index);
        currentDate = selectedDay;
        updateUI();
    });
});

previousDayButton.addEventListener("click", () => changeDay(-1));
nextDayButton.addEventListener("click", () => changeDay(1));
timeTrackerButton.addEventListener("click", () => openDialogue());
cancelButton.addEventListener("click", () => closeDialogue());
function populateTasks(selectedProjectId, selectedTaskId = null) {
    projectSelect.innerHTML = '<option value="">Select a Project</option>';
    projectSelect.disabled = true;

    if (!selectedProjectId) return;

    fetch(`/timer/get-tasks-list`)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((projects) => {
            if (projects.length > 0) {
                projects.forEach((project) => {
                    const option = document.createElement("option");
                    option.value = project.id;
                    option.setAttribute("data-name", project.name);
                    option.textContent = project.name;
                    projectSelect.appendChild(option);
                });
                if (projects.some(project => project.id === selectedTaskId)) {
                    $(projectSelect).val(selectedTaskId);
                }
                projectSelect.disabled = false;
            } else {
                projectSelect.innerHTML =
                    '<option value="">No Task available</option>';
            }
        })
        .catch((error) => {
            console.error("Error fetching projects:", error);
            alert("Failed to load projects. Please try again.");
        });
}

clientSelect.addEventListener("change", function () {
    const selectedProjectId = this.value;
    populateTasks(selectedProjectId);
});



setInterval(() => populateTasksForDate(currentDate), 60_000);
setInterval(() => getWeekData(), 60_000);

updateUI();


function getWeekData() {
    const dateFormatted = getLocalDateString(currentDate);
    if (currentUser) {
        var getweekSummaryUrl = `/timer/week-summary/${dateFormatted}/${currentUser}`;
    } else {
        getweekSummaryUrl = `/timer/week-summary/${dateFormatted}`;
    }
    fetch(getweekSummaryUrl)
        .then(response => response.json())
        .then(data => {
            updateWeekView(data);
        })
        .catch(error => console.error('Error fetching week summary:', error));
}
function updateWeekView(data) {
    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    // Create a Date object from the currentDate
    const current = new Date(currentDate);

    // Get the index of the current day (0 = Sunday, 6 = Saturday)
    const currentDayIndex = current.getDay();

    days.forEach((day, index) => {
        const timeSpan = document.querySelector(`.week-view li:nth-child(${index + 1}) .day-time`);
        if (data[day] && data[day]['is_running'] == true) {
            let realTimeStartTime = null;
            if (data[day]['started_at']) {
                const isoString = data[day]['started_at'].replace(" ", "T") + "Z";
                realTimeStartTime = new Date(isoString);
            }

            let realTimeTimeUpdate;
            if (data[day]['is_running'] && realTimeStartTime) {
                const nowMs = Date.now();
                const startedMs = realTimeStartTime.getTime();
                const diffInTime = Math.floor(
                    (nowMs - startedMs) / 1000
                );
                realTimeTimeUpdate = Number(data[day]['time_in_sec']) + Number(diffInTime);
            } else {
                realTimeTimeUpdate = data[day]['time_in_sec'];
            }
            timeSpan.textContent = secondtoHour(realTimeTimeUpdate); // Replace with fetched time or keep 0:00

        } else {
            timeSpan.textContent = data[day] && data[day]['total_time'] ? data[day]['total_time'] : '0.00'; // Replace with fetched time or keep 0:00
        }

        const dateSpan = document.querySelector(`.week-view li:nth-child(${index + 1}) .day-date`);

        // Calculate the correct date for each day
        const date = new Date(current);
        date.setDate(current.getDate() + (index - currentDayIndex)); // Adjust to match the week's starting day

        // Format the date as "2 Dec"
        const options = { day: 'numeric', month: 'short' };
        const formattedDate = new Intl.DateTimeFormat('en-US', options).format(date);

        // Update the date span
        dateSpan.textContent = formattedDate;
    });
}


$(document).ready(() => {
    const showError = () => {
        Swal.fire({
            title: "Error!",
            text: 'Sorry, looks like there are some errors detected, please try again.',
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
    };
    $("#create_form").validate({
        rules: {
            client_select: {
                required: true,
            },
            task_select: {
                required: true,
            },
        },
        messages: {
            client_select: {
                required: "Please enter your name",
            },
            task_select: {
                required: "Please enter your email",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        submitHandler: function (form, event) { // Add `event` as the second parameter
            event.preventDefault(); // Prevent default form submission
            const url = $("#create_form").attr("data-url");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: url,
                method: "POST",
                data: $("#create_form").serialize(),
                success: (response) => {
                    populateTasksForDate(currentDate); // Refresh tasks for the current week
                    getWeekData();
                    closeDialogue();
                },
                error: (response) => {

                    showError();
                }
            });
        }
    });
    $('#day-tasks').on('click', '.js-edit-entry', function () {
        const selectedTaskId = $(this).attr("data-id");
        $('#entry-notes').html('');
        if (currentUser) {
            var getsingleTaskURl = `/timer/get-task/${selectedTaskId}/${currentUser}`;
        } else {
            var getsingleTaskURl = `/timer/get-task/${selectedTaskId}`;
        }
        fetch(getsingleTaskURl)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((response) => {
                // $('#taskDate').val(currentDate);
                populateClients(response.project_id);
                populateTasks(response.project_id, response.task_id);
                $('#taskDate').val(response.timer_date);
                $('#entry-notes').html(response.notes);
                $('#timeInput').removeAttr('disabled');

                let startedAtTime = null;
                if (response.started_at) {
                    const isoString = response.started_at.replace(" ", "T") + "Z";
                    startedAtTime = new Date(isoString);
                }

                let elapsedSeconds;
                if (response.is_running && startedAtTime) {
                    $('#timeInput').attr('disabled', 'disabled');
                    const nowMs = Date.now();
                    const startedMs = startedAtTime.getTime();
                    const diffInSeconds = Math.floor(
                        (nowMs - startedMs) / 1000
                    );

                    elapsedSeconds = response.timer + diffInSeconds;
                } else {
                    elapsedSeconds = response.timer;
                }
                $("#timeInput").val(secondtoHour(elapsedSeconds));
                $("#startTimerButton").text("update Timer")
                $('#create_form').attr('data-url', `/timer/update-timer/${response.id}`)
                dialogue.style.display = "block";
            })
            .catch((error) => {
                console.error("Error fetching projects:", error);
                alert("Failed to load projects. Please try again.");
            });
    });
});
