const selectedDateEl = document.querySelector(".selected-date");
const previousWeekButton = document.querySelector(".previous-week");
const nextWeekButton = document.querySelector(".next-week");
const timeTrackerButton = document.querySelector(".js-new-week-row");
const dialogue = document.querySelector(".dialogue");
const clientSelect = document.getElementById("clientSelect");
const projectSelect = document.getElementById("projectSelect");
const timeInput = document.getElementById("timeInput");
const startTimerButton = document.getElementById("startTimerButton");
const cancelButton = document.getElementById("cancelButton");
const taskRows = document.querySelector(".day-tasks tbody");
const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
const saveButton = document.querySelector('.js-save');
let currentDate = new Date();
let currentUserName = null;
let currentUser = null;
const calendarButton = document.getElementById("calendar-button");
const datePicker = document.getElementById("date-picker");
const taskDate = document.querySelector(".js-spent-at-display");
const userSelect = document.getElementById("userSelect");
userSelect.addEventListener("change", function () {
    const selecteduserId = this.value;
    currentUser = selecteduserId;
    $('#user_id').val(selecteduserId);
    updateUI();

});

calendarButton.addEventListener("click", () => {
    datePicker.showPicker(); // Trigger the date picker
});
datePicker.addEventListener("change", (event) => {
    const selectedDate = new Date(event.target.value);
    changeWeek(selectedDate);

});

function formatWeekRange(currentDate) {
    const today = new Date();

    // Helper function to get the start and end of a week for a given date
    function getWeekRange(date) {
        const startOfWeek = new Date(date);
        startOfWeek.setDate(date.getDate() - date.getDay() + 1); // Monday of the week

        const endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(startOfWeek.getDate() + 6); // Sunday of the week

        const formatOptions = { day: "numeric", month: "short", year: "numeric" };
        const startFormatted = startOfWeek.toLocaleDateString("en-GB", formatOptions).replace(/\s/g, " ").slice(0, -5);
        const endFormatted = endOfWeek.toLocaleDateString("en-GB", formatOptions).replace(/\s/g, " ").slice(0, -5);

        return {
            range: `${startFormatted} – ${endFormatted} ${startOfWeek.getFullYear()}`, // "16 – 22 Dec 2024"
            startOfWeek,
            endOfWeek,
        };
    }

    // Get current week's range
    const thisWeek = getWeekRange(today);
    const targetWeek = getWeekRange(currentDate);

    // Check if the currentDate falls in this week
    const isCurrentWeek =
        currentDate >= thisWeek.startOfWeek && currentDate <= thisWeek.endOfWeek;

    if (isCurrentWeek) {
        return `<strong>This Week:</strong> ${thisWeek.range}`;
    } else {
        return `${targetWeek.range} <a href="javascript:void(0)" id="return-to-today" type="button" class="small-button" onClick="changeWeek('thisWeek')">Return to this week</a>`;
    }
}


function getCurrentWeekRange(date) {
    const dayOfWeek = date.getDay();
    const startOfWeek = new Date(date);
    startOfWeek.setDate(date.getDate() - dayOfWeek);

    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6);

    return { startOfWeek, endOfWeek };
}
function changeWeek(direction) {

    if (direction === "thisWeek") {
        // Reset to the start of the current week
        currentDate = new Date();
        const dayOfWeek = currentDate.getDay(); // 0 (Sunday) to 6 (Saturday)
        currentDate.setDate(currentDate.getDate() - dayOfWeek + 1); // Set to Monday
    }
    else if (typeof direction === "number") {
        // Increment or decrement weeks
        currentDate.setDate(currentDate.getDate() + (direction * 7));
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
    const tableBody = $('table tbody'); // Adjust the selector to target your table's body

    // Clear existing rows in the table body
    tableBody.empty();
}

function keeptimerunning(startedAtTime, elapsedSeconds, ID) {
    if (startedAtTime) {
        const nowMs = Date.now();
        const startedMs = startedAtTime.getTime();
        const diffInSeconds = Math.floor(
            (nowMs - startedMs) / 1000
        );
        elapsedSeconds = Number(elapsedSeconds) + Number(diffInSeconds);
    }
    const timeinhours = elapsedSeconds
        ? (elapsedSeconds / 3600).toFixed(2) // Convert seconds to hours and format to 2 decimal places
        : '';
    $(`#${ID}`).text(timeinhours);

}
function appendWeeklyTasks(weeklyTasks) {
    // Get the table body where rows need to be appended
    const tableBody = $('table tbody'); // Adjust the selector to target your table's body

    // Iterate over each client+project in the result
    $.each(weeklyTasks, function (clientProject, days) {
        // Create the <tr> element
        const projectId = days.find((day) => day.project_id)?.project_id || ''; // Find the first valid projectId, fallback to empty string
        const project_name = days.find((day) => day.project_name)?.project_name || ''; // Find the first valid projectId, fallback to empty string
        const project_pic = days.find((day) => day.project_pic)?.project_pic || ''; // Find the first valid projectId, fallback to empty string
        const client_name = days.find((day) => day.client_name)?.client_name || ''; // Find the first valid projectId, fallback to empty string
        const task_name = days.find((day) => day.task_name)?.task_name || ''; // Find the first valid projectId, fallback to empty string
        const clientId = days.find((day) => day.client_id)?.client_id || ''; // Find the first valid projectId, fallback to empty string
        const taskId = days.find((day) => day.task_id)?.task_id || ''; // Find the first valid projectId, fallback to empty string
        const getRunningTimer = days.some((day) =>
            day.is_task_running === 1 && new Date(day.date).toDateString() === new Date().toDateString()
        ) ? 1 : 0;
        const $row = $('<tr>')
            .addClass('week-view-entry')
            .attr('data-project-id', projectId)
            .attr('data-task-id', taskId)
            .attr('data-client-id', clientId);
        // Add the project+client column
        const $projectCell = $('<td>').addClass('name').append(
            $('<div>').addClass('entry-client').text(client_name), // Project name

        );
        $row.append($projectCell);
        const $picCell = $('<td>').addClass('name').append(
            $('<div>').addClass('entry-pic').text(project_pic), // Project name
        );
        $row.append($picCell);
        const $nameCell = $('<td>').addClass('name').append(
            $('<div>').addClass('entry-project').text(project_name), // Project name
            $('<div>').addClass('entry-task').html(
                `${task_name}`
            )
        );
        $row.append($nameCell);
        if (getRunningTimer) {
            var $startStopButton = $('<td>').addClass('name').append(
                $('<div>').html(`<button data-task-id="${taskId}" data-project-id="${projectId}" data-client-id="${clientId}" class="start_row_timer"  onclick='StopWeekData(this)'><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12" class="clock-running-minute-hand"></polyline>
                <polyline points="12 12 16 14" class="clock-running-hour-hand"></polyline>
                </svg>Stop Today</button>`), // Project name

            );
        } else {
            $startStopButton = $('<td>').addClass('name').append(
                $('<div>').html(`<button data-task-id="${taskId}" data-project-id="${projectId}" data-client-id="${clientId}" class="start_row_timer startButton-style"  onclick='StartWeekData(this)'><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>Start Today</button>`), // Project name

            );
        }

        // Save notes functionality

        $row.append($startStopButton);        // Add day-wise hours
        function createNotesIcon(day) {
            // Create the notes icon
            var $svg = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20">
                        <g clip-path="url(&quot;#a&quot;)" data-name="icons8-message-24">
                            <image width="20" height="20" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAALNJREFUSEvtlVEOgjAQRB/3QO/DRTiHHEMvotcxcg7EMTQpFah2KfGDJnzQsPN2tulQkHkVmfXZFFABF6A0umqBGrhKx3dwB45GcVf+cFo+oBuA1rE9B8pbZwdMHdl/jWjUDRB7l6NZB2Hxx8c5ACnX4icHO2B0k6cOedURKaCsSboYdorrM3BIadurUaOK61sYdqGuG5n2T69gbFLAS9HsAMni3zgwiccAEtdjWta/VxTeAy9uLxlndmWDAAAAAElFTkSuQmCC"/>
                        </g>
                        <defs>
                            <clipPath id="a"><path d="M0 0h24v24H0V0z"/></clipPath>
                        </defs>
                    </svg>`;
            if (day.notes) {
                $svg = ` <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20"><g clip-path="url(&quot;#a&quot;)" data-name="icons8-message-24 (1)"><image width="20" height="20" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAIFJREFUSEtjZKAxYKSx+Qx0teAvAwMDExV9BHY8sg9GLUAPXfoH0T+0OKE0vjF8MCAWwFLZf6h3CPGRfU2UDwgZiC5PsgWUxAOGD2DBQImheH0wagHBnDz0gwjdi8g+agAWI43kJC98NRrMArINR69wsPmAIsMJWQAyHIQpAjSv9AHrjR0Zem8DIAAAAABJRU5ErkJggg=="/></g><defs><clipPath id="a"><path d="M0 0h24v24H0V0z"/></clipPath></defs></svg>`;
            }
            const $notesIcon = $('<span>')
                .addClass('notes-icon')
                .attr('aria-label', 'Click to see notes')
                .attr('title', `${day.notes || 'Click to add/edit notes'}`) // Add title attribute for tooltip
                .html($svg);

            // Create the tooltip
            const $tooltip = $('<div>')
                .addClass('tooltipForm')
                .html(`
                    <input type="text" placeholder="Enter notes here" name="notes" value="${day.notes || ''}">
                    <button type="button" class="updateNotes" data-task-id="${day.task_id}" data-project-id="${day.project_id}" data-client-id="${day.client_id}" data-date="${day.date}">Save</button>
                    <button type="button" class="cancelNotes" onclick="updateUI()">Cancel</button>
                `);

            // Append tooltip to the notes icon
            $notesIcon.append($tooltip);

            // Toggle tooltip visibility on click
            $notesIcon.on('click', function () {
                $('.tooltipForm').css('display', 'none');
                $('.tooltipForm').removeClass('active');
                $tooltip.css('display', 'block');
                $tooltip.addClass('active');
            });
            // Attach cancel button click event


            // Save notes functionality
            $tooltip.find('.updateNotes').on('click', function () {
                const $button = $(this);
                const notes = $tooltip.find('input[name="notes"]').val();
                if (currentUser) {
                    var notesurl = `/update-notes/${$button.data('task-id')}/${$button.data('project-id')}/${$button.data('client-id')}/${$button.data('date')}/${currentUser}`;

                } else {
                    notesurl = `/update-notes/${$button.data('task-id')}/${$button.data('project-id')}/${$button.data('client-id')}/${$button.data('date')}`;

                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                });

                $.ajax({
                    url: notesurl,
                    method: 'POST',
                    data: { notes },
                    beforeSend: function () {
                        $('.pds-toast-success').addClass('d-none');
                        $('.pds-toast-notice').removeClass('d-none');
                    },
                    success: function (response) {
                        $('.pds-toast-success').removeClass('d-none');
                        $('.pds-toast-notice').addClass('d-none');
                        populateTasksForWeek(currentDate); // Refresh tasks
                        setTimeout(() => {
                            $('.pds-toast-success').addClass('d-none');
                        }, 5000);
                    },
                    error: function (error) {
                        console.error(error);
                        showError();
                    },
                });
            });
            return $notesIcon;
        }
        let totalTime = 0; // Initialize total time for the row

        days.forEach((day) => {
            const $icon = createNotesIcon(day);
            const timeInSeconds = parseInt(day.time, 10) || 0; // Ensure time is a number, default to 0 if invalid
            totalTime += timeInSeconds; // Accumulate the total time
            let startedAtTime = null;
            if (day.started_at) {
                const isoString = day.started_at.replace(" ", "T") + "Z";
                startedAtTime = new Date(isoString);
            }
            let elapsedSeconds;
            if (day.is_task_running && startedAtTime) {
                const nowMs = Date.now();
                const startedMs = startedAtTime.getTime();
                const diffInSeconds = Math.floor(
                    (nowMs - startedMs) / 1000
                );
                elapsedSeconds = Number(day.time) + Number(diffInSeconds);
            } else {
                elapsedSeconds = day.time;
            }
            const timeinhours = elapsedSeconds
                ? (elapsedSeconds / 3600).toFixed(2) // Convert seconds to hours and format to 2 decimal places
                : '';
            if (day.is_task_running === 1) {
                var $dayCell = $('<td>')
                    .addClass('day')
                    .append(
                        $(`<div style="display:flex; justify-content: center;"><span id="${day.task_id + day.project_id + day.client_id + day.date + day.day}" class=runningTime>${timeinhours}</span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12" class="clock-running-minute-hand"></polyline>
                            <polyline points="12 12 16 14" class="clock-running-hour-hand"></polyline>
                            </svg></div>`)
                    )
                    .append($icon); // Append $icon after the main content
                setInterval(() => {
                    keeptimerunning(startedAtTime, day.time, day.task_id + day.project_id + day.client_id + day.date + day.day);
                }, 60000);
            } else {
                $dayCell = $('<td>')
                    .addClass('day')
                    .append(
                        $('<input>')
                            .addClass('pds-input js-compound-entry')
                            .attr('type', 'text')
                            .attr('data-task-id', day.task_id)
                            .attr('data-project-id', day.project_id)
                            .attr('data-client-id', day.client_id)
                            .attr('data-date', day.date)
                            .attr('aria-label', `Hours on ${day.day}`)
                            .val(
                                timeInSeconds
                                    ? (timeInSeconds / 3600).toFixed(2) // Convert seconds to hours and format to 2 decimal places
                                    : ''
                            ),
                        $icon

                    );
            }

            const today = new Date(currentDate);
            const dayDate = new Date(day.date);
            // Highlight today (optional)
            if (today.toDateString() === dayDate.toDateString()) {
                $dayCell.addClass('is-today');
            } else {
                $dayCell.addClass('is-not-today');
            }
            $row.append($dayCell);
        });

        const totalHours = (totalTime / 3600).toFixed(2); // Convert seconds to hours and format to 2 decimals

        // Append the total time in the last column
        $row.append(
            $('<td>').addClass('total').text(totalHours)
        );

        // Add delete button column
        $row.append(
            $('<td>')
                .addClass('delete js-end-of-week-row')
                .append(
                    $('<button>')
                        .addClass('pds-button pds-button-sm pds-button-icon js-remove-row')
                        .attr('type', 'button')
                        .attr('onclick', 'deleteWeekData(this)') // Add the function call with `this` as parameter
                        .html(`
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="X icon">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        `) // Add the delete icon inside the button
                )
        );


        // Append the row to the table
        tableBody.append($row);

    });


    calculateAndAppendColumnTotals();

}
function calculateAndAppendColumnTotals() {
    const table = $('table'); // Adjust the selector to your table
    const tfootRow = table.find('tfoot tr'); // Target the footer row

    // Array to store column totals
    const columnTotals = [];
    let hasData = false; // Flag to check if there's valid data

    // Iterate through each row and calculate totals for each column
    table.find('tbody tr').each(function () {
        $(this).find('td.day').each(function (index) {
            const timeText = $(this).find('input').val() || $(this).text();
            if (!columnTotals[index]) columnTotals[index] = 0;

            if (timeText) {
                const timeInHours = parseFloat(timeText);
                if (!isNaN(timeInHours)) {
                    hasData = true; // Mark as having data if valid time found
                    columnTotals[index] += timeInHours; // Accumulate hours directly
                }
            }
        });
    });

    // If there's no valid data, reset the footer to 0.00
    if (!hasData) {
        tfootRow.find('td.day').each(function () {
            $(this).text('0.00');
        });
        tfootRow.find('td.total').text('0.00');
        return; // Exit the function
    }

    // Update the footer row with the totals for each column
    tfootRow.find('td.day').each(function (index) {
        if (columnTotals[index] !== undefined) {
            const totalHours = columnTotals[index];
            $(this).text(totalHours.toFixed(2)); // Format to 2 decimal points
        }
    });

    // Calculate the overall total for the last column
    const overallTotal = columnTotals.reduce((sum, hours) => sum + hours, 0);
    tfootRow.find('td.total').text(overallTotal.toFixed(2)); // Format to 2 decimal points
}
// Call the function after populating the table


async function startTask(taskId) {
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

async function stopTask(taskId) {
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
            projectSelect.innerHTML = '<option value="">Select a Project</option>';

            // Get all projects currently in the table
            const existingProjects = new Set();
            document.querySelectorAll("table tbody tr").forEach((row) => {
                const projectId = row.getAttribute("data-project-id"); // Assuming rows have a `data-project-id` attribute
                if (projectId) {
                    existingProjects.add(projectId);
                }
            });

            if (projects.length > 0) {
                projects
                    .filter((project) => !existingProjects.has(project.id.toString())) // Exclude existing projects
                    .forEach((project) => {
                        const option = document.createElement("option");
                        option.value = project.id;
                        option.setAttribute("data-name", project.project_name);
                        option.textContent = project.project_name;
                        projectSelect.appendChild(option);
                    });

                if (projectSelect.options.length > 0) {
                    projectSelect.disabled = false;
                } else {
                    projectSelect.innerHTML =
                        '<option value="">No new projects available</option>';
                }
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
    dialogue.style.display = "none";
}

function openDialogue() {
    $('#taskDate').val(currentDate);
    dialogue.style.display = "block";
    clientSelect.innerHTML = "";
    projectSelect.innerHTML = "";
    projectSelect.disabled = true;
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

function populateTasksForWeek(dateObject) {
    const dateFormatted = getLocalDateString(dateObject);
    if (currentUser) {
        var geturl = `/timer/get-week-tasks/${dateFormatted}/${currentUser}`;
    } else {
        var geturl = `/timer/get-week-tasks/${dateFormatted}`;
    }
    fetch(geturl)
        .then((response) => response.json())
        .then((tasks) => {
            clearTable();
            appendWeeklyTasks(tasks);

        })
        .catch((error) => {
            console.error("Error fetching tasks:", error);
        });
}

function updateUI() {
    selectedDateEl.innerHTML = formatWeekRange(currentDate);
    updateDaysOfWeek(currentDate);
    populateTasksForWeek(currentDate);
}
// Function to update the days of the week dynamically
function updateDaysOfWeek(currentDate) {
    // Get the current date
    const today = new Date(currentDate);

    // Calculate the start of the week (Monday)
    const dayIndex = today.getDay(); // Sunday = 0, Monday = 1, ..., Saturday = 6
    const daysFromMonday = dayIndex === 0 ? -6 : 1 - dayIndex;
    const startOfWeek = new Date(today);
    startOfWeek.setDate(today.getDate() + daysFromMonday);

    // Array for day names
    const dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    const shortDayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    // Select the table header row
    const tableRow = document.querySelector('tr');
    const dayHeaders = tableRow.querySelectorAll('.day');

    // Update each day header
    for (let i = 0; i < 7; i++) {
        const currentDay = new Date(startOfWeek);
        currentDay.setDate(startOfWeek.getDate() + i);

        const dayName = dayNames[i];
        const shortDayName = shortDayNames[i];
        const dayDate = currentDay.getDate();
        const dayMonth = currentDay.toLocaleString('default', { month: 'short' });

        const dayAnchor = dayHeaders[i].querySelector('a');
        dayAnchor.setAttribute('href', `javascript:void(0)`);
        dayAnchor.setAttribute('class', `gotoDayView`);
        dayAnchor.setAttribute('daydate', currentDay.toDateString());
        dayAnchor.setAttribute('aria-label', `${dayName}, ${dayDate} ${dayMonth}`);
        dayAnchor.querySelector('span').innerText = `${dayDate} ${dayMonth}`;

        // Highlight today's day
        if (today.toDateString() === currentDay.toDateString()) {
            dayHeaders[i].classList.add('focused-day');
            dayAnchor.classList.add('is-today');
            dayAnchor.classList.remove('is-not-today');
        } else {
            dayHeaders[i].classList.remove('focused-day');
            dayAnchor.classList.remove('is-today');
            dayAnchor.classList.add('is-not-today');
        }
    }
}

// Example usage: Pass the current date


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


previousWeekButton.addEventListener("click", () => changeWeek(-1));
nextWeekButton.addEventListener("click", () => changeWeek(1));
timeTrackerButton.addEventListener("click", () => openDialogue());
cancelButton.addEventListener("click", () => closeDialogue());





updateUI();

// Attach event delegation to a parent element
let changedInputs = {}; // Object to track changed inputs
let autoSaveTimer = null; // Timer for auto-saving changes

// Track changes in input fields dynamically
document.addEventListener('input', function (event) {
    if (event.target.classList.contains('js-compound-entry')) {
        const input = event.target;
        const taskId = input.dataset.taskId;
        const projectId = input.dataset.projectId;
        const clientId = input.dataset.clientId; // Assuming `data-task-id` holds the task ID
        const date = input.dataset.date; // Assuming `data-task-id` holds the task ID
        const value = input.value.trim();

        // Sanitize the value (remove invalid characters)
        const sanitizedValue = value.replace(/[^0-9.]/g, '').slice(0, 5);

        if (value !== sanitizedValue) {
            input.value = sanitizedValue;
        }

        // Automatically append ':00' if only hours are entered
        // if (/^\d{1,2}$/.test(value)) {
        //     value = `${value}:00`;
        // }


        // Track changes in the `changedInputs` object
        changedInputs[taskId + '_' + projectId + '_' + clientId + '_' + date] = {
            time: value,
            date: input.dataset.date, // Assuming `data-date` holds the date
            project_id: input.dataset.projectId, // Assuming `data-project-id` holds the project ID
            client_id: input.dataset.clientId, // Assuming `data-client-id` holds the client ID
            task_id: input.dataset.taskId, // Assuming `data-client-id` holds the client ID
        };

        // Reset auto-save timer
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(autoSaveChanges, 5000); // Auto-save after 5 seconds
    }
});


// Validate and format input on blur
document.addEventListener(
    'blur',
    function (event) {
        if (event.target.classList.contains('js-compound-entry')) {
            const input = event.target;
            let value = input.value.trim();

            // Validate decimal hours format (e.g., 1.5, 0.25, 23.75)
            const decimalHoursRegex = /^(\d{1,2})(\.\d{1,2})?$/;
            const match = value.match(decimalHoursRegex);

            input.classList.remove('error');
            let errorMessage = input.nextElementSibling;
            if (errorMessage && errorMessage.classList.contains('error-message')) {
                errorMessage.remove();
            }

            if (!match) {
                // Invalid input, show error
                input.classList.add('error');
                errorMessage = document.createElement('span');
                errorMessage.textContent = 'Please enter a valid time in decimal hours (e.g., 1.25, 0.50, up to 23.99).';
                errorMessage.classList.add('error-message');
                input.after(errorMessage);
                input.value = '';
            } else {
                const hours = parseFloat(value);

                // Check if hours are within valid range (0 to 23.99)
                if (hours < 0 || hours >= 24) {
                    input.classList.add('error');
                    errorMessage = document.createElement('span');
                    errorMessage.textContent = 'Hours must be between 0 and 23.99.';
                    errorMessage.classList.add('error-message');
                    input.after(errorMessage);
                    input.value = '';
                } else {
                    // Format to 2 decimal points if necessary
                    input.value = hours.toFixed(2);

                    // Enable the save button and perform further calculations
                    saveButton.disabled = false;
                    saveButton.classList.add('enabled');
                    calculateAndAppendColumnTotals();
                }
            }
        }
    },
    true
);

// Auto-save function
function autoSaveChanges() {
    if (Object.keys(changedInputs).length === 0) return; // No changes to save
    saveChangesToDatabase();
}

// Save button click event
saveButton.addEventListener('click', function () {
    if (Object.keys(changedInputs).length === 0) {
        alert('No changes to save.');
        return;
    }
    saveChangesToDatabase();
});

// Function to save changes to the database
function saveChangesToDatabase() {
    if (currentUser) {
        var saveTaskURl = `/save-tasks/${currentUser}`;
    } else {
        saveTaskURl = '/save-tasks';

    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: saveTaskURl,
        method: "POST",
        data: {
            tasks: changedInputs
        },
        beforeSend: () => {
            $('.pds-toast-success').addClass('d-none');
            $('.pds-toast-notice').removeClass('d-none'); // Show toast notice before sending the request
        },
        success: (response) => {
            $('.pds-toast-success').removeClass('d-none'); // Show toast notice before sending the request
            $('.pds-toast-notice').addClass('d-none'); // Show toast notice before sending the request
            populateTasksForWeek(currentDate); // Refresh tasks for the current week
            // closeDialogue();
            saveButton.disabled = true;
            saveButton.classList.remove('enabled');
            setTimeout(() => {
                $('.pds-toast-success').addClass('d-none');
            }, 5000); // 30 seconds
        },
        error: (response) => {
            console.log(response);
            showError();
        }
    });
}

// Function to calculate the start and end of the week
function getdeleteWeekDates(baseDate) {
    const startOfWeek = new Date(baseDate);
    // Calculate the offset to set the date to Monday
    const dayOffset = (startOfWeek.getDay() + 6) % 7; // Adjust so Monday is 0, Sunday is 6
    startOfWeek.setDate(startOfWeek.getDate() - dayOffset); // Set to Monday

    const deleteWeekDates = [];
    for (let i = 0; i < 7; i++) {
        // Clone the `startOfWeek` date object to avoid mutating the original
        const date = new Date(startOfWeek.getTime());
        date.setDate(startOfWeek.getDate() + i); // Add days for the week

        // Format the date manually to avoid timezone issues
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
        const day = String(date.getDate()).padStart(2, '0');
        deleteWeekDates.push(`${year}-${month}-${day}`);
    }

    return deleteWeekDates;
}

function deleteWeekData(buttonElement) {
    // Find the parent row from the button element
    const row = buttonElement.closest('tr');
    const projectId = row.getAttribute('data-project-id');
    const clientId = row.getAttribute('data-client-id'); // Ensure this attribute exists in your row

    if (!projectId || !clientId) {
        alert('Project ID or Client ID not found.');
        return;
    }


    // Example usage
    const deleteWeekDates = getdeleteWeekDates(currentDate);


    // Confirm deletion
    const confirmDelete = confirm(
        `Are you sure you want to delete data for Project ID ${projectId}, Client ID ${clientId}, for the week ${deleteWeekDates[0]} to ${deleteWeekDates[6]}?`
    );

    if (!confirmDelete) {
        return;
    }

    // Prepare the payload
    const deleteData = {
        project_id: projectId,
        client_id: clientId,
        user_id: currentUser,
        dates: deleteWeekDates,
    };

    // Send the delete request to the backend
    fetch('/timer/delete-week-data', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content'),
        },
        body: JSON.stringify(deleteData),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then((data) => {
            console.log('Delete successful:', data);
            // Optionally, remove the row from the table
            row.remove();
        })
        .catch((error) => {
            console.error('Error deleting data:', error);
            alert('Failed to delete data. Please try again.');
        });
}
function StartWeekData(buttonElement) {
    // Find the parent row from the button element
    const row = buttonElement.closest('tr');
    const projectId = row.getAttribute('data-project-id');
    const taskId = row.getAttribute('data-task-id');
    const clientId = row.getAttribute('data-client-id'); // Ensure this attribute exists in your row
    if (!projectId || !clientId) {
        alert('Project ID or Client ID not found.');
        return;
    }
    // Example usage

    // Prepare the payload
    const updateData = {
        project_id: projectId,
        client_id: clientId,
        task_id: taskId,
        user_id: currentUser,
        date: new Date().toDateString(),
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    });

    $.ajax({
        url: '/start-row-timer',
        method: 'POST',
        data: updateData,
        beforeSend: function () {
            $('.pds-toast-success').addClass('d-none');
            $('.pds-toast-notice').removeClass('d-none');
        },
        success: function (response) {
            $('.pds-toast-success').removeClass('d-none');
            $('.pds-toast-notice').addClass('d-none');
            populateTasksForWeek(currentDate); // Refresh tasks
            setTimeout(() => {
                $('.pds-toast-success').addClass('d-none');
            }, 5000);
        },
        error: function (error) {
            console.error(error);
            showError();
        },
    });
}
function StopWeekData(buttonElement) {
    // Find the parent row from the button element
    const row = buttonElement.closest('tr');
    const projectId = row.getAttribute('data-project-id');
    const taskId = row.getAttribute('data-task-id');
    const clientId = row.getAttribute('data-client-id'); // Ensure this attribute exists in your row
    if (!projectId || !clientId) {
        alert('Project ID or Client ID not found.');
        return;
    }

    // Prepare the payload
    const updateData = {
        project_id: projectId,
        client_id: clientId,
        task_id: taskId,
        user_id: currentUser,
        date: new Date().toDateString(),
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    });

    $.ajax({
        url: '/stop-row-timer',
        method: 'POST',
        data: updateData,
        beforeSend: function () {
            $('.pds-toast-success').addClass('d-none');
            $('.pds-toast-notice').removeClass('d-none');
        },
        success: function (response) {
            $('.pds-toast-success').removeClass('d-none');
            $('.pds-toast-notice').addClass('d-none');
            populateTasksForWeek(currentDate); // Refresh tasks
            setTimeout(() => {
                $('.pds-toast-success').addClass('d-none');
            }, 5000);
        },
        error: function (error) {
            console.error(error);
            showError();
        },
    });
}
$(document).ready(() => {
    document.querySelectorAll('a.gotoDayView').forEach(link => {
        link.addEventListener('click', function () {
            const dayDate = this.getAttribute('daydate');
            if (dayDate) {
                const targetDate = new Date(dayDate);
                const year = targetDate.getFullYear();
                const month = String(targetDate.getMonth() + 1).padStart(2, '0');
                const day = String(targetDate.getDate()).padStart(2, '0');

                // Redirect to the new URL with the date in local format (YYYY-MM-DD)
                const newUrl = `/timer?date=${year}-${month}-${day}`;
                // console.log(newUrl);          
                window.location.href = newUrl;

            }
        });
    });
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
            const url = $("#create_form").data("url");
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
                    populateTasksForWeek(currentDate); // Refresh tasks for the current week
                    closeDialogue();
                },
                error: (response) => {

                    // showError();
                }
            });
        }
    });
});
