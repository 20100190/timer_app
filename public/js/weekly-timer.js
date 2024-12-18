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
const calendarButton = document.getElementById("calendar-button");
const datePicker = document.getElementById("date-picker");
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

function appendWeeklyTasks(weeklyTasks) {
    // Get the table body where rows need to be appended
    const tableBody = $('table tbody'); // Adjust the selector to target your table's body

    // Iterate over each client+project in the result
    $.each(weeklyTasks, function (clientProject, days) {
        // Create the <tr> element
        const projectId = days.find((day) => day.project_id)?.project_id || ''; // Find the first valid projectId, fallback to empty string
        const clientId = days.find((day) => day.client_id)?.client_id || ''; // Find the first valid projectId, fallback to empty string
        const $row = $('<tr>')
            .addClass('week-view-entry')
            .attr('data-project-id', projectId)
            .attr('data-client-id', clientId);
        // Add the project+client column
        const $nameCell = $('<td>').addClass('name').append(
            $('<div>').addClass('entry-project').text(clientProject.split(' + ')[0]), // Project name
            $('<div>').addClass('entry-client').html(
                `<span class="pds-show@md">(</span>${clientProject.split(' + ')[1]}<span class="pds-show@md">)</span>`
            )
        );
        $row.append($nameCell);

        // Add day-wise hours

        let totalTime = 0; // Initialize total time for the row

        days.forEach((day) => {
            const timeInSeconds = parseInt(day.time, 10) || 0; // Ensure time is a number, default to 0 if invalid
            totalTime += timeInSeconds; // Accumulate the total time

            const $dayCell = $('<td>')
                .addClass('day')
                .append(
                    $('<input>')
                        .addClass('pds-input js-compound-entry')
                        .attr('type', 'text')
                        .attr('aria-label', `Hours on ${day.day}`)
                        .val(
                            timeInSeconds
                                ? `${Math.floor(timeInSeconds / 3600)}:${String(Math.floor((timeInSeconds % 3600) / 60)).padStart(2, '0')}` // Format as HH:mm
                                : ''
                        )
                );

            // Highlight today (optional)
            if (day.isToday) {
                $dayCell.addClass('is-today');
            } else {
                $dayCell.addClass('is-not-today');
            }

            $row.append($dayCell);
        });

        // Calculate total hours and minutes for the row
        const totalHours = Math.floor(totalTime / 3600);
        const totalMinutes = Math.floor((totalTime % 3600) / 60);

        // Append the total time in the last column
        $row.append(
            $('<td>').addClass('total').text(`${totalHours}:${totalMinutes.toString().padStart(2, '0')}`)
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

    // Iterate through each column (excluding the first and last columns for name and total)
    table.find('tbody tr').each(function () {
        $(this).find('td.day').each(function (index) {
            const timeText = $(this).find('input').val() || $(this).text();
            if (!columnTotals[index]) columnTotals[index] = 0;

            if (timeText) {
                const [hours, minutes] = timeText.split(':').map(Number);
                columnTotals[index] += (hours || 0) * 3600 + (minutes || 0) * 60; // Convert to seconds
            }
        });
    });

    // Update the footer row with the totals
    tfootRow.find('td.day').each(function (index) {
        if (columnTotals[index] !== undefined) {
            const totalSeconds = columnTotals[index];
            const totalHours = Math.floor(totalSeconds / 3600);
            const totalMinutes = Math.floor((totalSeconds % 3600) / 60);

            $(this).text(`${totalHours}:${String(totalMinutes).padStart(2, '0')}`);
        }
    });

    // Calculate the overall total for the last column
    const overallTotal = columnTotals.reduce((sum, seconds) => sum + seconds, 0);
    const overallHours = Math.floor(overallTotal / 3600);
    const overallMinutes = Math.floor((overallTotal % 3600) / 60);

    tfootRow.find('td.total').text(`${overallHours}:${String(overallMinutes).padStart(2, '0')}`);
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

function populateClients() {
    clientSelect.innerHTML = '<option value="">Select a Client</option>';

    fetch("/timer/get-clients")
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((clients) => {
            clients.forEach((client) => {
                const option = document.createElement("option");
                option.value = client.id;
                option.setAttribute("data-name", client.name);
                option.textContent = client.name;
                clientSelect.appendChild(option);
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
    fetch(`/timer/get-week-tasks/${dateFormatted}`)
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
    console.log(currentDate);
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

clientSelect.addEventListener("change", function () {
    const selectedClientId = this.value;
    populateProjects(selectedClientId);
});

startTimerButton.addEventListener("click", function () {
    const clientId = parseInt(clientSelect.value, 10);
    const projectId = parseInt(projectSelect.value, 10);
    const now = new Date();
    const startedAt = getUTCDateString(now);
    const timerDate = getLocalDateString(currentDate); // Assume this is the selected week's start date

    if (isNaN(clientId) || clientId <= 0) {
        alert("Please select a valid client");
        return;
    }

    if (isNaN(projectId) || projectId <= 0) {
        alert("Please select a valid project");
        return;
    }

    const selectedClientOption =
        clientSelect.options[clientSelect.selectedIndex];
    const clientName = selectedClientOption.getAttribute("data-name");

    const selectedProjectOption =
        projectSelect.options[projectSelect.selectedIndex];
    const projectName = selectedProjectOption.getAttribute("data-name");

    // Function to calculate the start and end of the week
    function getWeekDates(baseDate) {
        const startOfWeek = new Date(baseDate);
        startOfWeek.setDate(baseDate.getDate() - baseDate.getDay() + 1); // Set to Monday

        const weekDates = [];
        for (let i = 0; i < 7; i++) {
            const date = new Date(startOfWeek);
            date.setDate(startOfWeek.getDate() + i); // Add days for the week
            weekDates.push(date);
        }

        return weekDates;
    }

    const weekDates = getWeekDates(currentDate);

    const timerEntries = weekDates.map((date) => ({
        username: currentUserName,
        client_id: clientId,
        project_id: projectId,
        client_name: clientName,
        project_name: projectName,
        is_running: false,
        timer: '0',
        started_at: startedAt,
        timer_date: timerDate, // Format each day's date
    }));

    // Send timer entries for each day of the week
    Promise.all(
        timerEntries.map((entry) =>
            fetch("/timer/init-timer", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify(entry),
            })
        )
    )
        .then((responses) => {
            const failedResponses = responses.filter((res) => !res.ok);
            if (failedResponses.length > 0) {
                alert("Some timer entries failed to save.");
            } else {
                console.log("All timer entries saved successfully.");
            }
        })
        .catch((error) => {
            console.error("Error saving timer entries:", error);
            alert("Failed to save timer entries. Please try again.");
        });

    populateTasksForWeek(currentDate); // Refresh tasks for the current week
    closeDialogue();
});

setInterval(() => populateTasksForWeek(currentDate), 60_000);
getWeekData();
updateUI();


function getWeekData() {
    fetch(`/timer/week-summary`)
        .then(response => response.json())
        .then(data => {
            updateWeekView(data);
        })
        .catch(error => console.error('Error fetching week summary:', error));
}
function updateWeekView(data) {
    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    dayNames.forEach((day, index) => {
        const timeSpan = document.querySelector(`.week-view li:nth-child(${index + 1}) .day-time`);
        timeSpan.textContent = data[day] || '0:00'; // Replace with fetched time or keep 0:00
    });
}
// Attach event delegation to a parent element
document.addEventListener('input', function (event) {
    // Check if the event target matches the desired input class
    if (event.target.classList.contains('js-compound-entry')) {
        const input = event.target;
        const value = input.value;

        // Remove invalid characters dynamically
        const sanitizedValue = value.replace(/[^0-9:]/g, '').slice(0, 5); // Limit to 5 characters
        if (value !== sanitizedValue) {
            input.value = sanitizedValue;
        }
    }
});

document.addEventListener('blur', function (event) {
    // Check if the event target matches the desired input class
    if (event.target.classList.contains('js-compound-entry')) {
        const input = event.target;
        const value = input.value.trim(); // Trim whitespace

        // Validate the time format (HH:MM)
        const timeRegex = /^([0-9]{1,2}):([0-5][0-9])$/;
        const match = value.match(timeRegex);

        if (!match) {
            // Reset the input if invalid
            input.value = '';
            alert('Please enter a valid time in HH:MM format.');
        } else {
            const hours = parseInt(match[1], 10);
            const minutes = parseInt(match[2], 10);
            saveButton.disabled = false; // Enable the button
            saveButton.classList.add('enabled');
            // Ensure valid hours and minutes
            if (hours > 23 || minutes > 59) {
                input.value = '';
                alert('Hours must be 0-23 and minutes must be 0-59.');
            }

        }
    }
}, true); // Use capture phase for blur events



function deleteWeekData(buttonElement) {
    // Find the parent row from the button element
    const row = buttonElement.closest('tr');
    const projectId = row.getAttribute('data-project-id');
    const clientId = row.getAttribute('data-client-id'); // Ensure this attribute exists in your row

    if (!projectId || !clientId) {
        alert('Project ID or Client ID not found.');
        return;
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
