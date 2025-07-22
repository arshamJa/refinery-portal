function printTable() {
    const meetingInfo = document.getElementById("meeting-info")?.innerHTML || '';
    const table = document.getElementById("task-table")?.outerHTML || '';
    const signatureSection = document.getElementById("signature-section")?.innerHTML || '';

    const printWindow = window.open('', '', 'height=700,width=900');
    printWindow.document.write('<html lang="fa"><head><title></title>');
    printWindow.document.write('<meta charset="UTF-8">');
    printWindow.document.write(`<style>
        @import url('https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/font-face.css');
        body {
            font-family: "Vazir", sans-serif;
            direction: rtl;
            background: #fff;
            color: #333;
            font-size: 15px;
            line-height: 1.8;
            margin: 30px 50px;
            padding-top: 40px;
            padding-bottom: 40px;
        }
        button, .no-print, .screen-only {
            display: none !important;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #007acc;
            padding-bottom: 12px;
            margin-bottom: 30px;
        }
        .header-container h1 {
            font-size: 26px;
            font-weight: bold;
            color: #005e91;
            margin: 0;
        }
        .header-container img {
            max-width: 150px;
            height: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            font-size: 14px;
        }
        caption {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px 12px;
            text-align: right;
            vertical-align: top;
        }
        thead {
            background-color: #007acc;
            color: #fff;
        }
        tbody tr:nth-child(odd) {
            background-color: #f4f7fa;
        }
        h1, h3 {
            font-weight: bold;
            color: #444;
            margin: 0 0 20px 0;
        }
        h1 {
            font-size: 20px;
        }
        h3 {
            font-size: 18px;
        }
        .meeting-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 12px;
            background-color: #fafafa;
            border: 1px solid #ccc;
            border-radius: 12px;
            padding: 18px;
            font-size: 14px;
            color: #000;
        }
        .meeting-info-grid div {
            display: grid;
            grid-template-columns: auto 1fr;
            align-items: start;
            gap: 4px;
            white-space: normal;
            overflow-wrap: anywhere;
        }
        .meeting-info-grid div:last-child {
            grid-column: span 2;
        }
        .meeting-info-grid strong, .meeting-info-grid span {
            display: inline;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        td a {
            text-decoration: none;
            color: #007bff;
            white-space: pre-wrap;
        }
        .signature-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .signature-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        .signature-card img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        .signature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 12px;
        }
        /* Hide interactive elements */
        .task-table td .no-print,
        .task-table td .screen-only button,
        .task-table td .screen-only [x-data] {
            display: none !important;
        }
        .task-details {
            margin-top: 20px;
            break-inside: avoid;
        }
        .task-card {
            border: 1px solid #ddd;
            background: #f9f9f9;
            padding: 18px;
            margin-bottom: 8px;
            border-radius: 12px;
            page-break-inside: avoid;
            break-inside: avoid;
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
        .section-title {
         font-size: 1.2rem;
         font-weight: bold;
         margin-bottom: 16px;
         border-bottom: 1px solid #ccc;
         padding-bottom: 8px;"
         text-align: right;
        }
    </style>`);
    printWindow.document.write('</head><body>');

    printWindow.document.write(`
        <div class="header-container">
            <h1>گزارش صورتجلسه</h1>
            <img src="/fajrgam-b.png" alt="Logo">
        </div>
    `);

    if (meetingInfo) {
        printWindow.document.write('<div class="meeting-info-grid">');
        printWindow.document.write(meetingInfo);
        printWindow.document.write('</div>');
    }

    if (table) {
        printWindow.document.write(table);
    }

    // Tasks section - gather tasks by user from the original document
    printWindow.document.write('<div class="task-details"><h1 class="section-title">شرح اقدامات</h1>');

    const taskMap = {};
    document.querySelectorAll("#task-table tbody tr").forEach(row => {
        const taskBody = row.querySelector(".print-only")?.innerText.trim();
        const userName = row.querySelector("td[data-username]")?.innerText.trim() || "نام کاربر ناشناس";

        if (taskBody && taskBody !== '---' && taskBody !== '' && taskBody !== 'بدون فایل') {
            if (!taskMap[userName]) {
                taskMap[userName] = [];
            }
            taskMap[userName].push(taskBody);
        }
    });

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
    }, 1000);
}

