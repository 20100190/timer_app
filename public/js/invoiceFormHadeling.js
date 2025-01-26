document.addEventListener('DOMContentLoaded', function() {
    const clientSelect = document.getElementById('clientSelect');
    const projectSelect = document.getElementById('projectSelect');
    const fileInput = document.getElementById('files');
    const filePreview = document.getElementById('file-preview');

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
    document.getElementById('invoice-form').addEventListener('submit', function(e) {
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

    // Handle file input change event to preview files
    fileInput.addEventListener('change', function() {
        filePreview.innerHTML = ''; // Clear previous previews
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.classList.add('file-preview-item');
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = file.name;
                    div.appendChild(img);
                } else {
                    const p = document.createElement('p');
                    p.textContent = file.name;
                    div.appendChild(p);
                }
                filePreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });
});