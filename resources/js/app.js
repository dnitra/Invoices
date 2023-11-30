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
    row.parentNode.removeChild(row);
}


document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('#invoice-rows').addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-row')) {
            removeRow(e.target);
        }
        if (e.target.classList.contains('add-row')) {
            addRow();
            window.scrollTo(0,document.body.scrollHeight);
        }
    });
});
