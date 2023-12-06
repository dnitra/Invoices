import './bootstrap';


function addRow() {
    const table = document.querySelector('#invoice-rows tbody');
    const newRow = table.rows[0].cloneNode(true);

    // Clear input values
    const inputs = newRow.getElementsByTagName('input');
    for (let i = 0; i < inputs.length; i++) {
        inputs[i].value = "";
    }

    table.appendChild(newRow);
}

function removeRow(button) {
    const row = button.parentNode.parentNode;
    console.log(row)
    row.parentNode.removeChild(row);
}


document.addEventListener('DOMContentLoaded', () => {
    // Add event listeners for adding and removing rows
    document.querySelector('#invoice-rows').addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-row')) {
            removeRow(e.target);
        }
        if (e.target.classList.contains('add-row')) {
            addRow();
            window.scrollTo(0,document.body.scrollHeight);
        }
    });

    const customerSelect = document.getElementById('customer_id');
    const customerInfoContainer = document.getElementById('customer-info');

    // Function to fetch customer info based on selected customer
    function fetchCustomerInfo(customerId) {
        const url = `/customers/${customerId}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                // Update the customer info container with the fetched data
                customerInfoContainer.innerHTML = `
                        <p><strong>Ulice:</strong> ${data.street}</p>
                        <p><strong>Město:</strong> ${data.city}</p>
                        <p><strong>PSČ:</strong> ${data.zip}</p>
                        <p><strong>Země:</strong> ${data.country}</p>
                        <p><strong>Plátce DPH:</strong> ${data.vat_id ? 'Ano' : 'Ne'}</p>
                    `;
            })
            .catch(error => {
                console.error('Error fetching customer info:', error);
            });
    }

    // Event listener for changes in the customer selection
    customerSelect.addEventListener('change', function () {
        const selectedCustomerId = this.value;

        if (selectedCustomerId) {
            fetchCustomerInfo(selectedCustomerId);
        } else {
            // Clear customer info if no customer is selected
            customerInfoContainer.innerHTML = '';
        }
    });

    // Initialize customer info on page load if a customer is pre-selected
    const preSelectedCustomerId = customerSelect.value;
    if (preSelectedCustomerId) {
        fetchCustomerInfo(preSelectedCustomerId);
    }
});
