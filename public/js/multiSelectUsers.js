document.addEventListener("DOMContentLoaded", function () {
    const customSelects = document.querySelectorAll(".custom-select");

    // Function to update the selected options display
    function updateSelectedOptions(customSelect) {
        const selectedOptions = Array.from(customSelect.querySelectorAll(".option.active"))
            .filter(option => option !== customSelect.querySelector(".option.all-tags")).map(function (option) {
                return {
                    value: option.getAttribute("data-value"),
                    text: option.textContent.trim()
                };
            });

        const selectedValues = selectedOptions.map(function (option) {
            return option.value;
        });

        customSelect.querySelector(".tags_input").value = selectedValues.join(', ');

        let tagsHTML = "";
        if (selectedOptions.length === 0) {
            tagsHTML = '<span class="placeholder">...</span>';
        } else {
            const maxTagsToShow = 2;
            let additionalTagsCount = 0;

            selectedOptions.forEach(function (option, index) {
                if (index < maxTagsToShow) {
                    tagsHTML += '<span class="tag">' + option.text + '<span class="remove-tag" data-value="' + option.value + '">&times;</span></span>';
                } else {
                    additionalTagsCount++;
                }
            });

            if (additionalTagsCount > 0) {
                tagsHTML += '<span class="tag">+' + additionalTagsCount + '</span>';
            }
        }

        customSelect.querySelector(".selected-options").innerHTML = tagsHTML;
    }

    customSelects.forEach(function (customSelect) {
        const searchInput = customSelect.querySelector(".search-tags");
        const optionsContainer = customSelect.querySelector(".options");
        const noResultMessage = customSelect.querySelector(".no-result-message");
        const options = customSelect.querySelectorAll(".option");
        const allTagsOption = customSelect.querySelector(".option.all-tags");
        const clearButton = customSelect.querySelector(".clear");

        // Pre-select old values (if any)
        const oldValues = customSelect.querySelector(".tags_input").value.split(',').map(val => val.trim());
        options.forEach(function (option) {
            if (oldValues.includes(option.getAttribute("data-value"))) {
                option.classList.add("active");
            }
        });

        allTagsOption.addEventListener("click", function () {
            const isActive = allTagsOption.classList.contains("active");
            options.forEach(function (option) {
                if (option !== allTagsOption) {
                    option.classList.toggle("active", !isActive);
                }
            });
            updateSelectedOptions(customSelect);
        });

        clearButton.addEventListener("click", function () {
            searchInput.value = "";
            options.forEach(function (option) {
                option.style.display = "block";
            });
            noResultMessage.style.display = "none";
        });

        searchInput.addEventListener('keyup', function () {
            const filter = this.value.toUpperCase();
            const items = customSelect.querySelectorAll(".option");
            items.forEach(function (item) {
                const text = item.textContent || item.innerText;
                item.style.display = text.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
            });
        });

        options.forEach(function (option) {
            option.addEventListener("click", function () {
                option.classList.toggle("active");
                updateSelectedOptions(customSelect);
            });
        });

        document.addEventListener("click", function (event) {
            const removeTag = event.target.closest(".remove-tag");
            if (removeTag) {
                const customSelect = removeTag.closest(".custom-select");
                const valueToRemove = removeTag.getAttribute("data-value");
                const optionToRemove = customSelect.querySelector(".option[data-value='" + valueToRemove + "']");
                optionToRemove.classList.remove("active");
                updateSelectedOptions(customSelect);
            }
        });

        const selectBoxes = customSelect.querySelectorAll(".select-box");
        selectBoxes.forEach(function (selectBox) {
            selectBox.addEventListener("click", function (event) {
                if (!event.target.closest(".tag")) {
                    selectBox.parentNode.classList.toggle("open");
                }
            });
        });

        document.addEventListener("click", function (event) {
            if (!event.target.closest(".custom-select") && !event.target.classList.contains("remove-tag")) {
                customSelects.forEach(function (customSelect) {
                    customSelect.classList.remove("open");
                });
            }
        });

        updateSelectedOptions(customSelect);
    });
});
