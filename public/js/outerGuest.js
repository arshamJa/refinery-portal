document.addEventListener("DOMContentLoaded", function () {
    const guestsData = document.getElementById('guests-data');
    let outerGuests = JSON.parse(guestsData?.getAttribute('data-outer-guests')) || [];

    const outerGuestsTbody = document.getElementById('guests-outer-tbody');
    const addOuterGuestBtn = document.getElementById('add-outer-guest-btn');
    const outerOrganizationCheckbox = document.getElementById('outer-organization-checkbox');
    const outerOrganizationTable = document.getElementById('outer-organization-table');

    function addOuterGuest() {
        const index = outerGuests.length;
        const lastGuest = outerGuests[index - 1] || { name: '', companyName: '' };
        const guest = { name: '', companyName: '' };
        outerGuests.push(guest);
        renderOuterGuests();
    }

    function removeOuterGuest(index) {
        outerGuests.splice(index, 1);
        renderOuterGuests();
    }

    function updateOuterGuest(index, field, value) {
        outerGuests[index][field] = value;
    }

    function renderOuterGuests() {
        outerGuestsTbody.innerHTML = '';
        outerGuests.forEach((guest, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-4 py-2">${index + 1}</td>
                <td class="px-4 py-2">
                    <input type="text" name="guests[outer][${index}][name]" value="${guest.name}" class="w-full"
                           placeholder="نام مهمان"
                           oninput="window.updateOuterGuest(${index}, 'name', this.value)">
                </td>
                <td class="px-4 py-2">
                    <input type="text" name="guests[outer][${index}][companyName]" value="${guest.companyName}" class="w-full"
                           placeholder="نام شرکت"
                           oninput="window.updateOuterGuest(${index}, 'companyName', this.value)">
                </td>
                <td class="px-4 py-2 text-center">
                    <button type="button" class="text-red-600" onclick="window.removeOuterGuest(${index})">×</button>
                </td>
            `;
            outerGuestsTbody.appendChild(row);
        });
    }

    // Make functions globally accessible for inline HTML handlers
    window.updateOuterGuest = updateOuterGuest;
    window.removeOuterGuest = removeOuterGuest;

    if (addOuterGuestBtn) {
        addOuterGuestBtn.addEventListener('click', addOuterGuest);
    }

    if (outerOrganizationCheckbox && outerOrganizationTable) {
        outerOrganizationCheckbox.addEventListener('change', () => {
            outerOrganizationTable.classList.toggle('hidden', !outerOrganizationCheckbox.checked);
        });
    }

    renderOuterGuests();
});
