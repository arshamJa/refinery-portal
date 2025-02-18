<div>

{{--    <x-template>--}}
          <div class="container" dir="ltr">
            <div class="card input-wrapper">
                <div class="from">
                    <span class="heading">From :</span>
                    <div class="dropdown-container" id="input-language">
                        <div class="dropdown-toggle">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m10.5 21 5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 0 1 6-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 0 1-3.827-5.802"/>
                            </svg>
                            <span class="selected" data-value="auto">Auto Detect</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                            </svg>
                        </div>
                        <ul class="dropdown-menu">
                            <li class="option active">DropDown Menu Item 1</li>
                            <li class="option">DropDown Menu Item 2</li>
                        </ul>
                    </div>
                </div>
                <div class="text-area">
                    <textarea id="input-text" cols="30" rows="10" placeholder="متن مورد نظر را تایپ کنید"></textarea>
                </div>
            </div>

            <div class="center">
                <div class="swap-position">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                    </svg>
                </div>
            </div>

            <div class="card output-wrapper">
                <div class="to">
                    <span class="heading">To :</span>
                    <div class="dropdown-container" id="output-language">
                        <div class="dropdown-toggle">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m10.5 21 5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 0 1 6-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 0 1-3.827-5.802"/>
                            </svg>
                            <span class="selected" data-value="en">Englsih</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                            </svg>
                        </div>
                        <ul class="dropdown-menu">
                            <li class="option active">DropDown Menu Item 1</li>
                            <li class="option">DropDown Menu Item 2</li>
                        </ul>
                    </div>
                </div>
                <textarea id="output-text" cols="30" rows="10" disabled></textarea>
            </div>
        </div>
{{--    </x-template>--}}


    <script>
        const languages = [
            {
                no: "0",
                name: "Auto",
                native: "Detect",
                code: "auto",
            },
            {
                no: "16",
                name: "English",
                native: "English",
                code: "en",
            },
            {
                no: "41",
                name: "Persian",
                native: "فارسی",
                code: "fa",
            },
        ];
        const dropdowns = document.querySelectorAll(".dropdown-container"),
            inputLanguageDropdown = document.querySelector("#input-language"),
            outputLanguageDropdown = document.querySelector("#output-language");

        function populateDropdown(dropdown, options) {
            dropdown.querySelector("ul").innerHTML = "";
            options.forEach((option) => {
                const li = document.createElement("li");
                const title = option.name + " (" + option.native + ")";
                li.innerHTML = title;
                li.dataset.value = option.code;
                li.classList.add("option");
                dropdown.querySelector("ul").appendChild(li);
            });
        }

        populateDropdown(inputLanguageDropdown, languages);
        populateDropdown(outputLanguageDropdown, languages);

        dropdowns.forEach((dropdown) => {
            dropdown.addEventListener("click", (e) => {
                dropdown.classList.toggle("active");
            });

            dropdown.querySelectorAll(".option").forEach((item) => {
                item.addEventListener("click", (e) => {
                    //remove active class from current dropdowns
                    dropdown.querySelectorAll(".option").forEach((item) => {
                        item.classList.remove("active");
                    });
                    item.classList.add("active");
                    const selected = dropdown.querySelector(".selected");
                    selected.innerHTML = item.innerHTML;
                    selected.dataset.value = item.dataset.value;
                    translate();
                });
            });
        });
        document.addEventListener("click", (e) => {
            dropdowns.forEach((dropdown) => {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove("active");
                }
            });
        });

        const swapBtn = document.querySelector(".swap-position"),
            inputLanguage = inputLanguageDropdown.querySelector(".selected"),
            outputLanguage = outputLanguageDropdown.querySelector(".selected"),
            inputTextElem = document.querySelector("#input-text"),
            outputTextElem = document.querySelector("#output-text");

        swapBtn.addEventListener("click", (e) => {
            const temp = inputLanguage.innerHTML;
            inputLanguage.innerHTML = outputLanguage.innerHTML;
            outputLanguage.innerHTML = temp;

            const tempValue = inputLanguage.dataset.value;
            inputLanguage.dataset.value = outputLanguage.dataset.value;
            outputLanguage.dataset.value = tempValue;

            //swap text
            const tempInputText = inputTextElem.value;
            inputTextElem.value = outputTextElem.value;
            outputTextElem.value = tempInputText;

            translate();
        });

        function translate() {
            const inputText = inputTextElem.value;
            const inputLanguage =
                inputLanguageDropdown.querySelector(".selected").dataset.value;
            const outputLanguage =
                outputLanguageDropdown.querySelector(".selected").dataset.value;
            const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=${inputLanguage}&tl=${outputLanguage}&dt=t&q=${encodeURI(
                inputText
            )}`;
            fetch(url)
                .then((response) => response.json())
                .then((json) => {
                    console.log(json);
                    outputTextElem.value = json[0].map((item) => item[0]).join("");
                })
                .catch((error) => {
                    console.log(error);
                });
        }

        inputTextElem.addEventListener("input", (e) => {
            //limit input to 5000 characters
            if (inputTextElem.value.length > 5000) {
                inputTextElem.value = inputTextElem.value.slice(0, 5000);
            }
            translate();
        });

        const uploadDocument = document.querySelector("#upload-document"),
            uploadTitle = document.querySelector("#upload-title");

        uploadDocument.addEventListener("change", (e) => {
            const file = e.target.files[0];
            if (
                file.type === "application/pdf" ||
                file.type === "text/plain" ||
                file.type === "application/msword" ||
                file.type ===
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
            ) {
                uploadTitle.innerHTML = file.name;
                const reader = new FileReader();
                reader.readAsText(file);
                reader.onload = (e) => {
                    inputTextElem.value = e.target.result;
                    translate();
                };
            } else {
                alert("Please upload a valid file");
            }
        });

        const downloadBtn = document.querySelector("#download-btn");

        downloadBtn.addEventListener("click", (e) => {
            const outputText = outputTextElem.value;
            const outputLanguage =
                outputLanguageDropdown.querySelector(".selected").dataset.value;
            if (outputText) {
                const blob = new Blob([outputText], {type: "text/plain"});
                const url = URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.download = `translated-to-${outputLanguage}.txt`;
                a.href = url;
                a.click();
            }
        });

        const darkModeCheckbox = document.getElementById("dark-mode-btn");

        darkModeCheckbox.addEventListener("change", () => {
            document.body.classList.toggle("dark");
        });

        const inputChars = document.querySelector("#input-chars");

        inputTextElem.addEventListener("input", (e) => {
            inputChars.innerHTML = inputTextElem.value.length;
        });
    </script>


</div>
