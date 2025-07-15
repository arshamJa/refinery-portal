// document.addEventListener("DOMContentLoaded", () => {
//     const container = document.getElementById('scriptorium_dropdown');
//     const users = JSON.parse(container.getAttribute('data-users'));
//
//     const dropdownBtn = document.getElementById("scriptorium-dropdown-btn");
//     const dropdownMenu = document.getElementById("scriptorium-dropdown-menu");
//     const dropdownSearch = document.getElementById("scriptorium-dropdown-search");
//     const dropdownList = document.getElementById("scriptorium-dropdown-list");
//     const selectedText = document.getElementById("scriptorium-selected-text");
//     const hiddenInput = document.getElementById("scriptorium-hidden-id");
//     const noResult = document.getElementById("scriptorium-no-result");
//
//     let selected = null;
//
//     function renderOptions(filter = "") {
//         dropdownList.innerHTML = "";
//
//         const filteredUsers = users.filter(user => {
//             const text = `${user.full_name} - ${user.department?.department_name ?? "بدون بخش"} - ${user.position ?? ""}`;
//             return text.toLowerCase().includes(filter.toLowerCase());
//         });
//
//         if (filteredUsers.length === 0) {
//             noResult.style.display = "block";
//         } else {
//             noResult.style.display = "none";
//         }
//
//         filteredUsers.forEach(user => {
//             const li = document.createElement("li");
//             li.className = "px-4 py-2 text-gray-700 hover:bg-blue-50 cursor-pointer rounded-lg truncate";
//             li.setAttribute("role", "option");
//             li.setAttribute("tabindex", "0");
//             li.textContent = `${user.full_name} - ${user.department?.department_name ?? "بدون بخش"} - ${user.position ?? ""}`;
//             li.dataset.id = user.id;
//
//             if (selected && selected.id === user.id) {
//                 li.classList.add("bg-blue-100", "font-semibold");
//             }
//
//             li.addEventListener("click", () => {
//                 selectUser(user);
//                 closeDropdown();
//             });
//
//             li.addEventListener("keydown", (e) => {
//                 if (e.key === "Enter" || e.key === " ") {
//                     e.preventDefault();
//                     li.click();
//                 }
//             });
//
//             dropdownList.appendChild(li);
//         });
//     }
//
//     function selectUser(user) {
//         selected = user;
//         selectedText.textContent = `${user.full_name} - ${user.department?.department_name ?? "بدون بخش"} - ${user.position ?? ""}`;
//         hiddenInput.value = user.id;
//         document.getElementById('scriptorium-hidden-department').value = user.department?.department_name || '';
//         document.getElementById('scriptorium-hidden-position').value = user.position || '';
//     }
//
//     function clearSelection() {
//         selected = null;
//         selectedText.textContent = "...";
//         hiddenInput.value = "";
//     }
//
//     function toggleDropdown() {
//         const isHidden = dropdownMenu.classList.contains("hidden");
//         if (isHidden) {
//             openDropdown();
//         } else {
//             closeDropdown();
//         }
//     }
//
//     function openDropdown() {
//         dropdownMenu.classList.remove("hidden");
//         dropdownBtn.setAttribute("aria-expanded", "true");
//         dropdownSearch.focus();
//         renderOptions(dropdownSearch.value);
//     }
//
//     function closeDropdown() {
//         dropdownMenu.classList.add("hidden");
//         dropdownBtn.setAttribute("aria-expanded", "false");
//         dropdownSearch.value = "";
//         renderOptions();
//     }
//
//     dropdownBtn.addEventListener("click", toggleDropdown);
//
//     dropdownSearch.addEventListener("input", (e) => {
//         renderOptions(e.target.value);
//     });
//
//     document.addEventListener("click", (e) => {
//         if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
//             closeDropdown();
//         }
//     });
//
//     dropdownBtn.addEventListener("keydown", (e) => {
//         if (["ArrowDown", "Enter", " "].includes(e.key)) {
//             e.preventDefault();
//             openDropdown();
//         }
//     });
//
//     function initSelected() {
//         const oldId = hiddenInput.value;
//         if (oldId) {
//             const foundUser = users.find(u => u.id == oldId);
//             if (foundUser) selectUser(foundUser);
//         }
//     }
//
//     initSelected();
//     renderOptions();
// });
