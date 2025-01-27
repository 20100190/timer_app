document.addEventListener('DOMContentLoaded', function() {
    const clientSelect = document.getElementById('clientSelect');
    const projectSelect = document.getElementById('projectSelect');
    const fileInput = document.getElementById('files');
    const filePreview = document.getElementById('file-preview');
    const dropArea = document.querySelector('.drag-drop-area');
    var allFiles = []; // This will hold all the files over time


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

    fileInput.addEventListener('change', function() {
        Array.from(this.files).forEach(file => {
            allFiles.push(file); // Append new files to the global files array
        });
    
        // Update the file preview
        updateFilePreview(allFiles);
    });
    
    function updateFilePreview(files) {
        filePreview.innerHTML = ''; // Clear previous previews
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.classList.add('file-preview-item');
                if (file.type.startsWith('image/')) {
                    const img = new Image();
                    img.src = e.target.result;
                    img.alt = file.name;
                    div.appendChild(img);
                } else if (file.type === 'application/pdf') {
                    const iframe = document.createElement('iframe');
                    iframe.src = e.target.result;
                    iframe.style.width = '100px';
                    iframe.style.height = '100px';
                    div.appendChild(iframe);
                }
                 else {
                    const p = document.createElement('p');
                    p.textContent = file.name; // Show file name for non-images
                    div.appendChild(p);
                }
                filePreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
        // Update the file input element with the new files
        const dataTransfer = new DataTransfer();
        Array.from(files).forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
    }

    // Handle form submission
    document.getElementById('invoice-form').addEventListener('submit', function(e) {
        e.preventDefault();
    
        // Create FormData object
        const formData = new FormData();
    
        // Append all files from the allFiles array
        allFiles.forEach(file => {
            formData.append('files[]', file);
        });
    
        // Append other form data
        Array.from(this.elements).forEach(element => {
            if (element.name && element.type !== 'file') { // Avoid file inputs
                formData.append(element.name, element.value);
            }
        });
    
        // Use axios to send FormData
        axios.post(this.action, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(response => {
            console.log('Success:', response.data);
            // Hide the form

            const filePreview = document.getElementById('file-preview');
            var form = document.getElementById('invoice-form');
            form.style.display = 'none';
            // Clear the form for the next input
            form.reset();
            // Hide file preview area if necessary
            filePreview.innerHTML = ''
            filePreview.style.display = 'none';
            // Optionally reload the invoices table by fetching updated data
            updateInvoiceTable();
            // Handle successful submission here
        })
        .catch(error => {
            console.error('Error during form submission:', error.response.data);
            // Handle errors here
        });
    });


        // Drag & Drop Events
    dropArea.addEventListener('dragover', function(e) {
        e.preventDefault(); // Prevent default behavior (prevent file from being opened)
    });

    dropArea.addEventListener('drop', function(e) {
        e.preventDefault();
        fileInput.files = e.dataTransfer.files; // Transfer files
        handleFiles(e.dataTransfer.files); // Handle dropped files
    });

    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });
    
});

document.addEventListener('DOMContentLoaded', function() {
    var toggleButton = document.getElementById('invoicetoggleFormButton');
    var form = document.getElementById('invoice-form');
    const filePreview = document.getElementById('file-preview');

    toggleButton.addEventListener('click', function() {
        // Toggle visibility
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';  // Show form
            filePreview.style.display = 'flex';
        } else {
            form.style.display = 'none';  // Hide form
            filePreview.style.display = 'none';
        }
    });
});


function updateInvoiceTable() {
    axios.get('/get-invoice') // Adjust the URL as per your routing
        .then(response => {
            const tableBody = document.querySelector('#invoicesTableContainer tbody');
            tableBody.innerHTML = ''; // Clear existing rows

            // Loop through the JSON data to create table rows
            response.data.forEach(invoice => {
                const row = `
                    <tr>
                        <td>${invoice.date}</td>
                        <td>${invoice.client.name}</td>
                        <td>${invoice.project.project_name}</td>
                        <td>${invoice.type}</td>
                        <td>${invoice.amount}</td>
                        <td>${invoice.billable ? 'Yes' : 'No'}</td>
                        <td>
                            ${invoice.files.map(file => `<a href="${file.file_path}" target="_blank">${file.file_path}</a>`).join('<br>')}
                        </td>
                        <td>${invoice.reported ? 'Yes' : 'No'}</td>
                        <td>${invoice.invoiced ? 'Yes' : 'No'}</td>
                    </tr>`;
                tableBody.innerHTML += row; // Append the new row
            });
        })
        .catch(error => {
            console.error('Error updating the invoices table:', error);
        });
}
