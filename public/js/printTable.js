
    function printTable() {
    const meetingInfo = document.getElementById("meeting-info")?.innerHTML || '';
    const table = document.getElementById("task-table").outerHTML;
    const signatureSection = document.getElementById("signature-section")?.innerHTML || '';
    const logo ='<img src="/fajrgam-b.png" alt="Logo">';
    const printWindow = window.open('', '', 'height=500,width=800');
    printWindow.document.write('<html><head><title>پرینت صورتجلسه</title>');
    printWindow.document.write('<style>');
    printWindow.document.write(`

                        @media screen {
                            .print-only { display: none !important; }
                            .print-only-logo { display: none !important; }
                        }
            @media print {
                body {font-family: "Vazir", Arial, sans-serif;direction: rtl;margin: 30px;font-size: 12px;color: #000;}
                button, .no-print, .screen-only {display: none !important;}
                .print-only {display: block !important;}

                .header-container {
                    display: flex;
                    justify-content: space-between; /* Centers the content horizontally */
                    align-items: center; /* Centers the content vertically */
                    margin-bottom: 10px; /* Remove bottom margin */
                    text-align: end; /* Ensures the text is also centered */
                }
                 .header-container h1 {
                        font-size: 24px; /* Controls the font size of the title */
                        font-weight: bold; /* Ensures the title is bold */
                        margin: 0; /* Removes any margins around the title */
                    }

                    .header-container img {
                        max-width: 150px; /* Limits the maximum width of the logo */
                        height: auto; /* Ensures the height scales proportionally */
                    }

                table {width: 100%;border-collapse: collapse;margin: 0;}
                th, td {border: 1px solid #ccc;padding: 6px 10px;text-align: right;vertical-align: top;}
                th {background-color: #f5f5f5;font-weight: bold;font-size: 18px;}
                h1 {font-size: 20px; /* Adjust font size */margin: 0; /* Remove any margin to ensure proper alignment */}
                h3 {text-align: center;margin: 0 0 20px 0;font-size: 18px;}

                .meeting-info-grid {
                    display: grid !important;
                    grid-template-columns: repeat(2, 1fr) !important;
                    gap: 2px 10px !important;
                    margin-bottom: 12px !important;
                    background-color: #fafafa !important;
                    border: 1px solid #ccc !important;
                    border-radius: 4px !important;
                    padding: 8px !important;
                    direction: rtl !important;
                    font-size: 14px !important;
                    color: #000 !important;
                }
                .meeting-info-grid div {
                    display: grid !important;
                    grid-template-columns: auto 1fr !important;
                    align-items: start !important;
                    gap: 4px !important;
                    white-space: normal !important;
                    overflow-wrap: anywhere !important;
                }

                .meeting-info-grid div:last-child {grid-column: span 2 !important;}

                .meeting-info-grid strong, .meeting-info-grid span {
                    display: inline !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    line-height: 1.5 !important;
                }
                td a {text-decoration: none;color: #007bff;white-space: pre-wrap;}
                .signature-section {margin-top: 40px;page-break-before: always;}
                .signature-card {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 10px;
                    border: 1px solid #ddd;
                    border-radius: 6px;
                }
                .signature-card img { width: 40px; height: 40px; object-fit: contain;}
                .signature-grid {display: grid;grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));gap: 12px;}

                /* Hides Show More / Show Less completely */
                .task-table td .no-print,
                .task-table td .screen-only button,
                .task-table td .screen-only [x-data] {
                    display: none !important;
                }
                .task-details {
                    margin-top: 20px;
                }
                .task-card {
                    border: 1px solid #ddd;
                    background: #f9f9f9;
                    padding: 8px;
                    margin-bottom: 8px;
                    border-radius: 5px;
                }
                .task-card strong {
                    display: block;
                    margin-bottom: 4px;
                    font-weight: bold;
                      font-size: 18px;
                }
                .task-card p {
                font-size: 16px;
                margin: 6px 0;
                }
            }
        `);
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');


    // Add the logo and "صورتجلسه" text in the print window
    printWindow.document.write(`
                        <div class="header-container">
                            <h1 style="font-size: 24px; font-weight: bold; margin: 0;">صورتجلسه</h1>
                            <img src="/fajrgam-b.png" alt="Logo" style="max-width: 150px; height: auto;">
                        </div>
                    `);


    // Add the meeting info container before the table
    if (meetingInfo) {
    printWindow.document.write('<div class="meeting-info-grid">');
    printWindow.document.write(meetingInfo);
    printWindow.document.write('</div>');
}

    // Add the table
    printWindow.document.write(table);

    // Add the task details in the print window
    printWindow.document.write('<div class="task-details"><h1 style="text-align:center; font-size: 20px; margin-bottom: 15px;">شرح اقدامات</h1>');

    // Collect all rows and group tasks by user
    const taskMap = {};
    document.querySelectorAll("#task-table tbody tr").forEach(row => {
    const taskBody = row.querySelector(".print-only")?.innerText.trim();
    const userName = row.querySelector("td[data-username]")?.innerText.trim() || "نام کاربر ناشناس";

    if (taskBody && userName && taskBody !== '---' && taskBody !== '' && taskBody !== 'بدون فایل') {
    if (!taskMap[userName]) {
    taskMap[userName] = [];
}
    taskMap[userName].push(taskBody);
}
});

    // Write each user's tasks in the print window
    Object.keys(taskMap).forEach(userName => {
    printWindow.document.write(`<div class="task-card">`);
    printWindow.document.write(`<strong>${userName}</strong>`);
    taskMap[userName].forEach(task => {
    printWindow.document.write(`<p>- ${task}</p>`);
});
    printWindow.document.write('</div>');
});

    printWindow.document.write('</div>');

    if (signatureSection) {
    printWindow.document.write('<div class="signature-section">');
    printWindow.document.write(signatureSection);
    printWindow.document.write('</div>');
}

    printWindow.document.write('</body></html>');
    printWindow.document.close();
    setTimeout(() => {
    printWindow.print();
}, 1000); // Wait 1 second before printing
}

