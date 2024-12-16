const selectedDateEl = document.querySelector(".selected-date");
const weekViewButtons = document.querySelectorAll(".week-view button");
const previousDayButton = document.querySelector(".previous-day");
const nextDayButton = document.querySelector(".next-day");
const timeTrackerButton = document.querySelector(".time-tracker");
const dialogue = document.querySelector(".dialogue");
const clientSelect = document.getElementById("clientSelect");
const projectSelect = document.getElementById("projectSelect");
const timeInput = document.getElementById("timeInput");
const startTimerButton = document.getElementById("startTimerButton");
const cancelButton = document.getElementById("cancelButton");
const taskRows = document.querySelector(".day-tasks tbody");
const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
let currentDate = new Date();
let currentUserName = null;

function formatDate(date) {
    const options = { weekday: "long", day: "numeric", month: "short" };
    return date.toLocaleDateString("en-GB", options);
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
    currentDate.setDate(currentDate.getDate() + direction);
    updateUI();
}

function clearTable() {
    taskRows.innerHTML = "";
}

function createRow(rowData) {
    const row = document.createElement("tr");

    const projectCell = document.createElement("td");
    projectCell.textContent = rowData.project;
    row.appendChild(projectCell);

    const clientCell = document.createElement("td");
    clientCell.textContent = rowData.client;
    row.appendChild(clientCell);

    const timeCell = document.createElement("td");
    timeCell.textContent = rowData.time;
    row.appendChild(timeCell);

    const actionCell = document.createElement("td");
    const actionButton = document.createElement("button");
    actionButton.textContent = rowData.action;
    actionButton.setAttribute("data-id", rowData.id);
    actionButton.setAttribute("data-is-running", rowData.is_running);

    actionButton.addEventListener("click", function (e) {
        const button = e.target;
        const taskId = button.getAttribute("data-id");
        const isRunning = button.getAttribute("data-is-running") === "1";

        if (isRunning) {
            stopTask(taskId).then(() => {
                actionButton.textContent = "Start";
            });
        } else {
            startTask(taskId).then(() => {
                actionButton.textContent = "Stop";
            });
        }

        updateUI();
        populateTasksForDate(currentDate);
    });

    actionCell.appendChild(actionButton);
    row.appendChild(actionCell);

    document.querySelector(".day-tasks tbody").appendChild(row);
}

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

function populateTasksForDate(dateObject) {
    const dateFormatted = getLocalDateString(dateObject);
    fetch(`/timer/get-tasks/${dateFormatted}`)
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
                    project: task.project_name,
                    client: task.client_name,
                    time: secondsToHHMM(elapsedSeconds),
                    action: task.is_running ? "Stop" : "Start",
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

function updateUI() {
    selectedDateEl.textContent = formatDate(currentDate);

    const { startOfWeek, endOfWeek } = getCurrentWeekRange(currentDate);
    const daysOfWeek = Array.from(weekViewButtons);

    daysOfWeek.forEach((button) => (button.style.backgroundColor = ""));

    const selectedDay = currentDate.getDay();
    daysOfWeek[selectedDay].style.backgroundColor = "lightgreen";

    populateTasksForDate(currentDate);
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
        console.log(currentDate);
        updateUI();
    });
});

previousDayButton.addEventListener("click", () => changeDay(-1));
nextDayButton.addEventListener("click", () => changeDay(1));
timeTrackerButton.addEventListener("click", () => openDialogue());
cancelButton.addEventListener("click", () => closeDialogue());

clientSelect.addEventListener("change", function () {
    const selectedClientId = this.value;
    populateProjects(selectedClientId);
});

startTimerButton.addEventListener("click", function () {
    const clientId = parseInt(clientSelect.value, 10);
    const projectId = parseInt(projectSelect.value, 10);

    const rawTime = timeInput.value;
    const { hours, minutes } = parseTimeString(rawTime);
    initialTime = hours * 3600 + minutes * 60;

    const isRunning = true;
    const now = new Date();
    const startedAt = getUTCDateString(now);
    const timerDate = getLocalDateString(currentDate);

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

    const timerData = {
        username: currentUserName,
        client_id: clientId,
        project_id: projectId,
        client_name: clientName,
        project_name: projectName,
        timer: initialTime,
        started_at: startedAt,
        timer_date: timerDate,
        is_running: isRunning,
    };

    fetch("/timer/init-timer", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify(timerData),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.text();
        })
        .then((data) => {
            try {
                const jsonData = JSON.parse(data);
                console.log("Timer started successfully", jsonData);
            } catch (e) {
                console.error("Error parsing JSON:", e, data);
                alert("Failed to start timer. Invalid response format.");
            }
        })
        .catch((error) => {
            console.error("Error saving timer:", error);
            alert("Failed to start timer. Please try again.");
        });

    populateTasksForDate(currentDate);
    closeDialogue();
});

setInterval(() => populateTasksForDate(currentDate), 60_000);
getWeekData();
updateUI();


function getWeekData(){
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
