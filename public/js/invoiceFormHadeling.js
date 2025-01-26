// public/js/form-handling.js
document.addEventListener('DOMContentLoaded', function() {
    const clientSelect = document.getElementById('clientSelect');
    const projectSelect = document.getElementById('projectSelect');

    // Load clients
    axios.get('/timer/get-clients')
        .then(response => {
            response.data.forEach(client => {
                let option = new Option(client.name, client.id);
                clientSelect.appendChild(option);
            });
            clientSelect.disabled = false; // Enable client select after loading
        })
        .catch(error => console.error('Error loading clients:', error));

    // Update projects when a client is selected
    clientSelect.addEventListener('change', function() {
        const clientId = this.value;
        projectSelect.innerHTML = '<option value="">Select a Project</option>'; // Reset
        projectSelect.disabled = true; // Disable until loaded

        axios.get(`/timer/get-projects/${clientId}`)
            .then(response => {
                response.data.forEach(project => {
                    let option = new Option(project.project_name, project.id);
                    projectSelect.appendChild(option);
                });
                projectSelect.disabled = false; // Enable project select after loading
            })
            .catch(error => console.error('Error loading projects:', error));
    });

    // Handle form submission
    document.getElementById('create_form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        axios.post(this.action, formData)
            .then(response => {
                console.log('Success:', response.data);
                // Handle successful submission, e.g., display a message or redirect
            })
            .catch(error => {
                console.error('Error during form submission:', error.response.data);
                // Handle errors, e.g., display error messages
            });
    });
});