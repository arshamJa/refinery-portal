// Get the data attributes from the div with id="guests-data"
const guestsData = document.getElementById('guests-data');
let innerGuests = JSON.parse(guestsData.getAttribute('data-inner-guests'));
let outerGuests = JSON.parse(guestsData.getAttribute('data-outer-guests'));

// The rest of your JavaScript code
const innerGuestsTbody = document.getElementById('guests-inner-tbody');
const outerGuestsTbody = document.getElementById('guests-outer-tbody');
const addInnerGuestBtn = document.getElementById('add-inner-guest-btn');
const addOuterGuestBtn = document.getElementById('add-outer-guest-btn');
const outerOrganizationCheckbox = document.getElementById('outer-organization-checkbox');
const innerOrganizationTable = document.getElementById('inner-organization-table');
const outerOrganizationTable = document.getElementById('outer-organization-table');

// Function to add a new inner organization guest
function addInnerGuest() {
    const index = innerGuests.length;

    // Use the last guest's data, or default to empty fields if no previous guest
    const lastGuest = innerGuests[index - 1] || { name: '', department: '' };
    const guest = {
        name: lastGuest.name || '',
        department: lastGuest.department || ''
    };

    innerGuests.push(guest);
    renderInnerGuests(); // Re-render the inner guest table
}

// Function to add a new outer organization guest
function addOuterGuest() {
    const index = outerGuests.length;

    // Use the last guest's data, or default to empty fields if no previous guest
    const lastGuest = outerGuests[index - 1] || { name: '', companyName: '' };
    const guest = {
        name: lastGuest.name || '',
        companyName: lastGuest.companyName || ''
    };

    outerGuests.push(guest);
    renderOuterGuests(); // Re-render the outer guest table
}

// Function to remove an inner organization guest by index
function removeInnerGuest(index) {
    innerGuests.splice(index, 1);
    renderInnerGuests();
}

// Function to remove an outer organization guest by index
function removeOuterGuest(index) {
    outerGuests.splice(index, 1);
    renderOuterGuests();
}

// Function to update an inner organization guest's data
function updateInnerGuest(index, field, value) {
    innerGuests[index][field] = value;
}

// Function to update an outer organization guest's data
function updateOuterGuest(index, field, value) {
    outerGuests[index][field] = value;
}

// Function to render all inner organization guests as table rows
function renderInnerGuests() {
    innerGuestsTbody.innerHTML = ''; // Clear the current rows

    // Loop through the innerGuests array and render the rows
    innerGuests.forEach((guest, index) => {
        let row = document.createElement('tr');
        row.id = `inner-guest-${index}`;  // Give the row a unique ID

        row.innerHTML = `
            <td class="px-4 py-2">${index + 1}</td>
            <td class="px-4 py-2"><input type="text" name="guests[inner][${index}][name]" value="${guest.name}" class="w-full" placeholder="نام مهمان" oninput="updateInnerGuest(${index}, 'name', this.value)" /></td>
            <td class="px-4 py-2"><input type="text" name="guests[inner][${index}][department]" value="${guest.department}" class="w-full" placeholder="واحد سازمانی" oninput="updateInnerGuest(${index}, 'department', this.value)" /></td>
            <td class="px-4 py-2 text-center">
                <button type="button" class="text-red-600" onclick="removeInnerGuest(${index})">×</button>
            </td>
        `;

        // Append the new row to the table
        innerGuestsTbody.appendChild(row);
    });
}

// Function to render all outer organization guests as table rows
function renderOuterGuests() {
    outerGuestsTbody.innerHTML = ''; // Clear the current rows

    // Loop through the outerGuests array and render the rows
    outerGuests.forEach((guest, index) => {
        let row = document.createElement('tr');
        row.id = `outer-guest-${index}`;  // Give the row a unique ID

        row.innerHTML = `
            <td class="px-4 py-2">${index + 1}</td>
            <td class="px-4 py-2"><input type="text" name="guests[outer][${index}][name]" value="${guest.name}" class="w-full" placeholder="نام مهمان" oninput="updateOuterGuest(${index}, 'name', this.value)" /></td>
            <td class="px-4 py-2"><input type="text" name="guests[outer][${index}][companyName]" value="${guest.companyName}" class="w-full" placeholder="نام شرکت" oninput="updateOuterGuest(${index}, 'companyName', this.value)" /></td>
            <td class="px-4 py-2 text-center">
                <button type="button" class="text-red-600" onclick="removeOuterGuest(${index})">×</button>
            </td>
        `;

        // Append the new row to the table
        outerGuestsTbody.appendChild(row);
    });
}

// Event listener for adding guests
addInnerGuestBtn.addEventListener('click', addInnerGuest);
addOuterGuestBtn.addEventListener('click', addOuterGuest);

// Toggle the visibility of the outer organization table based on checkbox
outerOrganizationCheckbox.addEventListener('change', () => {
    if (outerOrganizationCheckbox.checked) {
        outerOrganizationTable.classList.remove('hidden');
    } else {
        outerOrganizationTable.classList.add('hidden');
    }
});

// Initial render of tables
renderInnerGuests();
renderOuterGuests();
