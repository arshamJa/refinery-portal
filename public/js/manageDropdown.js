document.addEventListener("DOMContentLoaded", () => {
    const dropdowns = [
        {id: "boss", type: "single"},
        {id: "scriptorium", type: "single"},
        {id: "participants", type: "multi"},
        {id: "innerGuest", type: "multi"}
    ];

    const selectedUserIds = new Set();

    dropdowns.forEach(({id, type}) => {
        const container = document.getElementById(`${id}_dropdown`);
        if (!container) return;

        const users = JSON.parse(container.dataset.users || "[]");

        const button = document.getElementById(`${id}-dropdown-btn`);
        const menu = document.getElementById(`${id}-dropdown-menu`);
        const list = document.getElementById(`${id}-dropdown-list`);
        const searchInput = document.getElementById(`${id}-dropdown-search`);
        const hiddenInput = document.getElementById(`${id}-hidden-input`);
        const selectedText = document.getElementById(`${id}-selected-text`);
        const noResult = document.getElementById(`${id}-no-result`);
        const selectedContainer = document.getElementById(`${id}-selected-container`);

        const hiddenDepartment = document.getElementById(`${id}-hidden-department`);
        const hiddenPosition = document.getElementById(`${id}-hidden-position`);

        let currentSelected = type === "multi" ? [] : null;
        // Populate selected from old values
        const oldValue = hiddenInput.value;
        if (oldValue) {
            try {
                const parsed = JSON.parse(oldValue);
                const selectedIds = type === "multi" ? parsed : [parsed];
                currentSelected = type === "multi" ? [] : null;

                selectedIds.forEach(id => {
                    const found = users.find(u => u.user_id === Number(id));
                    if (found) {
                        selectedUserIds.add(String(found.user_id));
                        if (type === "multi") {
                            currentSelected.push(found);
                        } else {
                            currentSelected = found;

                            // Populate extra scriptorium fields if needed
                            if (id === "scriptorium") {
                                if (hiddenDepartment) hiddenDepartment.value = found.department_id || '';
                                if (hiddenPosition) hiddenPosition.value = found.position || '';
                            }
                        }
                    }
                });

                if (type === "multi") {
                    updateMultiDisplay();
                } else if (currentSelected) {
                    selectedText.innerHTML = `
                <div>
                    <div><strong>${currentSelected.full_name}</strong></div>
                    <div class="text-xs text-gray-500">${currentSelected.department_name || ''} - ${currentSelected.position || ''}</div>
                </div>
            `;
                }
            } catch (e) {
                console.error(`Invalid JSON in hidden input for #${id}:`, e);
            }
        }
        button?.addEventListener("click", (e) => {
            e.stopPropagation();
            menu?.classList.toggle("hidden");
            renderList();
            searchInput?.focus();
        });

        searchInput?.addEventListener("input", renderList);

        function renderList() {
            const search = searchInput?.value.toLowerCase() || "";
            list.innerHTML = "";

            const filtered = users.filter(user => {
                return !selectedUserIds.has(user.user_id.toString()) &&
                    user.full_name.toLowerCase().includes(search);
            });

            if (filtered.length === 0) {
                noResult?.classList.remove("hidden");
            } else {
                noResult?.classList.add("hidden");
                filtered.forEach(user => {
                    const li = document.createElement("li");
                    li.className = "px-4 py-2 hover:bg-gray-100 cursor-pointer text-right";
                    li.innerHTML = `
                        <div>
                            <div>${user.full_name}</div>
                            <div class="text-xs text-gray-500">${user.department_name || ''} - ${user.position || ''}</div>
                        </div>
                    `;
                    li.addEventListener("click", () => handleSelect(user));
                    list.appendChild(li);
                });
            }
        }

        function handleSelect(user) {
            const userIdStr = user.user_id.toString();

            if (type === "single") {
                if (currentSelected) {
                    selectedUserIds.delete(currentSelected.user_id.toString());
                }
                currentSelected = user;
                selectedUserIds.add(userIdStr);

                hiddenInput.value = user.user_id;

                if (id === "scriptorium") {
                    if (hiddenDepartment) hiddenDepartment.value = user.department_id || '';
                    if (hiddenPosition) hiddenPosition.value = user.position || '';
                }

                selectedText.innerHTML = `
                    <div>
                        <div><strong>${user.full_name}</strong></div>
                        <div class="text-xs text-gray-500">${user.department_name || ''} - ${user.position || ''}</div>
                    </div>
                `;

                menu.classList.add("hidden");

            } else {
                if (!currentSelected.find(u => u.user_id === user.user_id)) {
                    currentSelected.push(user);
                    selectedUserIds.add(userIdStr);
                }
                updateMultiDisplay();
                menu.classList.add("hidden");
            }

            // Re-render others for accurate filtering
            dropdowns.forEach(d => {
                if (d.id !== id) {
                    const si = document.getElementById(`${d.id}-dropdown-search`);
                    si?.dispatchEvent(new Event("input"));
                }
            });
        }

        function updateMultiDisplay() {
            if (!selectedContainer) return;
            selectedContainer.innerHTML = "";

            currentSelected.forEach(user => {
                const tag = document.createElement("span");
                tag.className = "bg-blue-100 text-blue-800 px-2 py-1 rounded-lg text-sm flex items-center gap-1";

                const close = document.createElement("span");
                close.textContent = "×";
                close.className = "ml-1 cursor-pointer";
                close.addEventListener("click", () => {
                    currentSelected = currentSelected.filter(u => u.user_id !== user.user_id);
                    selectedUserIds.delete(user.user_id.toString());
                    updateMultiDisplay();
                    renderList();
                });

                tag.innerHTML = `
                    <div>
                        <div><strong>${user.full_name}</strong></div>
                        <div class="text-xs text-gray-500">${user.department_name || ''} - ${user.position || ''}</div>
                    </div>
                `;
                tag.appendChild(close);
                selectedContainer.appendChild(tag);
            });

            const ids = currentSelected.map(u => u.user_id);
            hiddenInput.value = ids.join(',');
            selectedText.textContent = currentSelected.length > 0 ? `${currentSelected.length} انتخاب شده` : "انتخاب کنید";
        }

        // Set default selected (e.g., authenticated user)
        const defaultUserId = hiddenInput?.value;
        if (defaultUserId && type === "single") {
            const defaultUser = users.find(u => u.user_id.toString() === defaultUserId);
            if (defaultUser) {
                currentSelected = defaultUser;
                selectedUserIds.add(defaultUserId);

                selectedText.innerHTML = `
                    <div>
                        <div><strong>${defaultUser.full_name}</strong></div>
                        <div class="text-xs text-gray-500">${defaultUser.department_name || ''} - ${defaultUser.position || ''}</div>
                    </div>
                `;

                if (id === "scriptorium") {
                    if (hiddenDepartment) hiddenDepartment.value = defaultUser.department_id || '';
                    if (hiddenPosition) hiddenPosition.value = defaultUser.position || '';
                }
            }
        }

        renderList();
    });

    // Global click to close all dropdowns
    document.addEventListener("click", () => {
        dropdowns.forEach(({id}) => {
            const menu = document.getElementById(`${id}-dropdown-menu`);
            if (menu) menu.classList.add("hidden");
        });
    });
});
