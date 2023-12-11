import './bootstrap';


function addRow() {
    const table = document.querySelector('#invoice-rows tbody');
    const newRow = document.createElement('tr');
    const nextIndex = table.getElementsByTagName('tr').length;

    newRow.innerHTML = `
        <input type="hidden" name="rows[${nextIndex}][id]" value="">
        <td>
            <input type="text" class="form-control" name="rows[${nextIndex}][text]" value="">
        </td>
        <td>
            <input type="number" class="form-control" name="rows[${nextIndex}][unit_price]" value="">
        </td>
        <td>
            <input type="number" class="form-control" name="rows[${nextIndex}][quantity]" value="">
        </td>
        <td>
            <select class="form-control vat-rate" name="rows[${nextIndex}][vat_rate]">
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm remove-row">Odstranit</button>
        </td>
    `;

    const newRowVatRateSelect = newRow.querySelector('select.vat-rate');
    const existingVatRateSelect = document.querySelector('select.vat-rate');
    const existingVatRateSelectOptions = existingVatRateSelect.querySelectorAll('option');

    existingVatRateSelectOptions.forEach(option => {
        const newOption = document.createElement('option');
        newOption.value = option.value;
        newOption.innerText = option.innerText;
        newRowVatRateSelect.appendChild(newOption);
    });


    // Clear input values
    const inputs = newRow.getElementsByTagName('input');
    for (let i = 0; i < inputs.length; i++) {
        inputs[i].value = "";
    }

    table.appendChild(newRow);
}

function removeRow(button) {
    const row = button.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

function fetchVatRatesForCustomerCountry(country){
    const url = `/invoices/vat-rates/${country}`;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Update the invoice rows with correct VAT rates
            const vatRateSelects = document.querySelectorAll('select.vat-rate');
            vatRateSelects.forEach(select => {
                const vatRate = select.value;
                select.innerHTML = '';
                data.forEach((vatRate, index) => {
                    const option = document.createElement('option');
                    option.value = vatRate;
                    option.innerText = `${vatRate}%`;
                    select.appendChild(option);
                    if(index === 0){
                        select.value = vatRate;
                    }
                })
            });

        })
        .catch(error => {
            console.error('Error fetching vat rates:', error);
        });
}


document.addEventListener('DOMContentLoaded', () => {
    // Add event listeners for adding and removing rows
    document.querySelector('#invoice-rows').addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-row')) {
            removeRow(e.target);
        }
        if (e.target.classList.contains('add-row')) {
            addRow();
            window.scrollTo(0, document.body.scrollHeight);
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
                        <p><strong>Země:</strong> <span id="country">${data.country}</span></p>
                        <p><strong>Plátce DPH:</strong> ${data.vat_id ? 'Ano' : 'Ne'}</p>
                    `;

                fetchVatRatesForCustomerCountry(document.getElementById('country').innerText)
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
            fetchVatRatesForCustomerCountry('CZ')
        }



    });

    // Initialize customer info on page load if a customer is pre-selected
    const preSelectedCustomerId = customerSelect.value;
    if (preSelectedCustomerId) {
        fetchCustomerInfo(preSelectedCustomerId);
    }
});

