// document.addEventListener("DOMContentLoaded", function () {
//     const customSelects = document.querySelectorAll(".custom-select");
//
//     // Function to update the selected options display
//     function updateSelectedOptions(customSelect) {
//         const selectedOptions = Array.from(customSelect.querySelectorAll(".option.active"))
//             .filter(option => option !== customSelect.querySelector(".option.all-tags")).map(function (option) {
//                 return {
//                     value: option.getAttribute("data-value"),
//                     text: option.textContent.trim()
//                 };
//             });
//
//         const selectedValues = selectedOptions.map(function (option) {
//             return option.value;
//         });
//
//         customSelect.querySelector(".tags_input").value = selectedValues.join(', ');
//
//         let tagsHTML = "";
//         if (selectedOptions.length === 0) {
//             tagsHTML = '<span class="placeholder">...</span>';
//         } else {
//             const maxTagsToShow = 2;
//             let additionalTagsCount = 0;
//
//             selectedOptions.forEach(function (option, index) {
//                 if (index < maxTagsToShow) {
//                     tagsHTML += '<span class="tag">' + option.text + '<span class="remove-tag" data-value="' + option.value + '">&times;</span></span>';
//                 } else {
//                     additionalTagsCount++;
//                 }
//             });
//
//             if (additionalTagsCount > 0) {
//                 tagsHTML += '<span class="tag">+' + additionalTagsCount + '</span>';
//             }
//         }
//
//         customSelect.querySelector(".selected-options").innerHTML = tagsHTML;
//     }
//
//     customSelects.forEach(function (customSelect) {
//         const searchInput = customSelect.querySelector(".search-tags");
//         const optionsContainer = customSelect.querySelector(".options");
//         const noResultMessage = customSelect.querySelector(".no-result-message");
//         const options = customSelect.querySelectorAll(".option");
//         const allTagsOption = customSelect.querySelector(".option.all-tags");
//         const clearButton = customSelect.querySelector(".clear");
//
//         // Pre-select old values (if any)
//         const oldValues = customSelect.querySelector(".tags_input").value.split(',').map(val => val.trim());
//         options.forEach(function (option) {
//             if (oldValues.includes(option.getAttribute("data-value"))) {
//                 option.classList.add("active");
//             }
//         });
//
//         allTagsOption.addEventListener("click", function () {
//             const isActive = allTagsOption.classList.contains("active");
//             options.forEach(function (option) {
//                 if (option !== allTagsOption) {
//                     option.classList.toggle("active", !isActive);
//                 }
//             });
//             updateSelectedOptions(customSelect);
//         });
//
//         clearButton.addEventListener("click", function () {
//             searchInput.value = "";
//             options.forEach(function (option) {
//                 option.style.display = "block";
//             });
//             noResultMessage.style.display = "none";
//         });
//
//         searchInput.addEventListener('keyup', function () {
//             const filter = this.value.toUpperCase();
//             const items = customSelect.querySelectorAll(".option");
//             items.forEach(function (item) {
//                 const text = item.textContent || item.innerText;
//                 item.style.display = text.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
//             });
//         });
//
//         options.forEach(function (option) {
//             option.addEventListener("click", function () {
//                 option.classList.toggle("active");
//                 updateSelectedOptions(customSelect);
//             });
//         });
//
//         document.addEventListener("click", function (event) {
//             const removeTag = event.target.closest(".remove-tag");
//             if (removeTag) {
//                 const customSelect = removeTag.closest(".custom-select");
//                 const valueToRemove = removeTag.getAttribute("data-value");
//                 const optionToRemove = customSelect.querySelector(".option[data-value='" + valueToRemove + "']");
//                 optionToRemove.classList.remove("active");
//                 updateSelectedOptions(customSelect);
//             }
//         });
//
//         const selectBoxes = customSelect.querySelectorAll(".select-box");
//         selectBoxes.forEach(function (selectBox) {
//             selectBox.addEventListener("click", function (event) {
//                 if (!event.target.closest(".tag")) {
//                     selectBox.parentNode.classList.toggle("open");
//                 }
//             });
//         });
//
//         document.addEventListener("click", function (event) {
//             if (!event.target.closest(".custom-select") && !event.target.classList.contains("remove-tag")) {
//                 customSelects.forEach(function (customSelect) {
//                     customSelect.classList.remove("open");
//                 });
//             }
//         });
//
//         updateSelectedOptions(customSelect);
//     });
// });



document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById('participants_dropdown');
    if (!container) return; // safety check

    const users = JSON.parse(container.getAttribute('data-users'));

    const dropdownBtn = document.getElementById("participants-dropdown-btn");
    const dropdownMenu = document.getElementById("participants-dropdown-menu");
    const dropdownSearch = document.getElementById("participants-dropdown-search");
    const dropdownList = document.getElementById("participants-dropdown-list");
    const noResult = document.getElementById("participants-no-result");
    const selectedText = document.getElementById("participants-selected-text");
    const selectedContainer = document.getElementById("participants-selected-container");
    const hiddenInput = document.getElementById("participants-hidden-input");

    let selectedUsers = [];

    function renderOptions(filter = "") {
        dropdownList.innerHTML = "";

        // Filter users by search and exclude already selected ones
        const filteredUsers = users.filter(user => {
            const text = `${user.full_name} - ${user.department?.department_name ?? "بدون بخش"} - ${user.position ?? ""}`;
            const matchesFilter = text.toLowerCase().includes(filter.toLowerCase());
            const notSelected = !selectedUsers.some(u => u.id === user.id);
            return matchesFilter && notSelected;
        });

        if (filteredUsers.length === 0) {
            noResult.style.display = "block";
        } else {
            noResult.style.display = "none";
        }

        filteredUsers.forEach(user => {
            const li = document.createElement("li");
            li.className = "px-4 py-2 text-gray-700 hover:bg-blue-50 cursor-pointer rounded-lg truncate";
            li.setAttribute("role", "option");
            li.setAttribute("tabindex", "0");
            li.textContent = `${user.full_name} - ${user.department?.department_name ?? "بدون بخش"} - ${user.position ?? ""}`;
            li.dataset.id = user.id;

            li.addEventListener("click", () => {
                addUser(user);
                closeDropdown();
            });

            li.addEventListener("keydown", (e) => {
                if (e.key === "Enter" || e.key === " ") {
                    e.preventDefault();
                    li.click();
                }
            });

            dropdownList.appendChild(li);
        });
    }

    function updateSelectedDisplay() {
        selectedContainer.innerHTML = "";

        if (selectedUsers.length === 0) {
            selectedText.textContent = "انتخاب شرکت‌کنندگان";
            return;
        }

        selectedText.textContent = `${selectedUsers.length} شرکت‌کننده انتخاب شده`;

        selectedUsers.forEach(user => {
            const badge = document.createElement("div");
            badge.className = "flex items-center gap-2 bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-sm cursor-default";

            const nameSpan = document.createElement("span");
            nameSpan.textContent = user.full_name;

            const removeBtn = document.createElement("button");
            removeBtn.type = "button";
            removeBtn.className = "text-blue-600 hover:text-blue-800 focus:outline-none";
            removeBtn.innerHTML = "&times;";
            removeBtn.title = "حذف";
            removeBtn.addEventListener("click", () => {
                removeUser(user.id);
            });

            badge.appendChild(nameSpan);
            badge.appendChild(removeBtn);

            selectedContainer.appendChild(badge);
        });
    }

    function addUser(user) {
        if (!selectedUsers.some(u => u.id === user.id)) {
            selectedUsers.push(user);
            updateSelectedDisplay();
            updateHiddenInput();
        }
    }

    function removeUser(userId) {
        selectedUsers = selectedUsers.filter(u => u.id !== userId);
        updateSelectedDisplay();
        updateHiddenInput();
    }

    function updateHiddenInput() {
        hiddenInput.value = selectedUsers.map(u => u.id).join(",");
    }

    function toggleDropdown() {
        const isHidden = dropdownMenu.classList.contains("hidden");
        if (isHidden) {
            openDropdown();
        } else {
            closeDropdown();
        }
    }

    function openDropdown() {
        dropdownMenu.classList.remove("hidden");
        dropdownBtn.setAttribute("aria-expanded", "true");
        dropdownSearch.focus();
        renderOptions(dropdownSearch.value);
    }

    function closeDropdown() {
        dropdownMenu.classList.add("hidden");
        dropdownBtn.setAttribute("aria-expanded", "false");
        dropdownSearch.value = "";
        renderOptions();
    }

    dropdownBtn.addEventListener("click", toggleDropdown);

    dropdownSearch.addEventListener("input", (e) => {
        renderOptions(e.target.value);
    });

    document.addEventListener("click", (e) => {
        if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            closeDropdown();
        }
    });

    dropdownBtn.addEventListener("keydown", (e) => {
        if (["ArrowDown", "Enter", " "].includes(e.key)) {
            e.preventDefault();
            openDropdown();
        }
    });

    function initSelected() {
        const oldVal = hiddenInput.value;
        if (oldVal) {
            const ids = oldVal.split(",").map(id => id.trim()).filter(id => id !== "");
            selectedUsers = users.filter(u => ids.includes(u.id.toString()));
            updateSelectedDisplay();
            updateHiddenInput();
        }
    }

    initSelected();
    renderOptions();
});

