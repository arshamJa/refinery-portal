// function printTask(title = 'بدون عنوان جلسه', personName = 'نامشخص') {
//     const meetingInfo = document.getElementById("meeting-info")?.innerHTML || '';
//     const taskCards = document.querySelectorAll('.mt-8 > div.bg-white'); // task cards container
//     const printWindow = window.open('', '', 'height=900,width=1000');
//
//     printWindow.document.write('<html lang="fa" dir="rtl"><head><meta charset="UTF-8"><title>پرینت صورتجلسه</title>');
//     printWindow.document.write('<style>');
//     printWindow.document.write(`
//         @import url('https://cdn.fontcdn.ir/Fonts/Vazir/Vazir.css');
//         body {
//             font-family: 'Vazir', Tahoma, sans-serif;
//             background: #f9fafb;
//             color: #222;
//             margin: 40px 30px;
//             font-size: 15px;
//             line-height: 1.6;
//         }
//         .header-container {
//             display: flex;
//             justify-content: space-between;
//             align-items: center;
//             border-bottom: 3px solid #0c63e4;
//             padding-bottom: 14px;
//             margin-bottom: 30px;
//             gap: 10px;
//         }
//         .header-container h1 {
//             font-size: 26px;
//             font-weight: 900;
//             color: #0c63e4;
//             margin: 0;
//             white-space: nowrap;
//         }
//         .header-container img {
//             max-width: 130px;
//             height: auto;
//         }
//         .meeting-info-grid {
//             background: white;
//             border-radius: 12px;
//             box-shadow: 0 3px 8px rgb(12 99 228 / 0.15);
//             padding: 20px 30px;
//             display: grid;
//             grid-template-columns: repeat(auto-fit,minmax(240px,1fr));
//             gap: 18px 35px;
//             color: #333;
//             margin-bottom: 40px;
//         }
//         .meeting-info-grid > div {
//             display: flex;
//             flex-direction: column;
//             gap: 6px;
//         }
//         .meeting-info-grid span:first-child {
//             font-weight: 700;
//             color: #0c63e4;
//             font-size: 14.5px;
//             user-select: text;
//         }
//         .meeting-info-grid span:last-child {
//             color: #444;
//             font-size: 14px;
//             user-select: text;
//         }
//         .task-card {
//             background: white;
//             border-radius: 14px;
//             box-shadow: 0 2px 10px rgb(0 0 0 / 0.05);
//             padding: 24px 28px;
//             margin-bottom: 28px;
//             border-left: 6px solid #0c63e4;
//             transition: box-shadow 0.3s ease;
//             page-break-inside: avoid;
//         }
//         .task-card:hover {
//             box-shadow: 0 6px 20px rgb(12 99 228 / 0.25);
//         }
//         .task-card h3 {
//             color: #0c63e4;
//             font-size: 18px;
//             font-weight: 800;
//             margin-bottom: 12px;
//             border-bottom: 1px solid #ddd;
//             padding-bottom: 6px;
//         }
//         .task-card p {
//             font-size: 14.5px;
//             color: #555;
//             line-height: 1.5;
//             user-select: text;
//         }
//         .task-details {
//             display: grid;
//             grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
//             gap: 10px 24px;
//             font-size: 14px;
//             color: #666;
//             margin-top: 18px;
//         }
//         .task-details div {
//             line-height: 1.3;
//         }
//         .task-details span.label {
//             font-weight: 700;
//             color: #0c63e4;
//         }
//         .task-details .files-list {
//             grid-column: 1 / -1;
//             margin-top: 8px;
//         }
//         .task-details ul {
//             padding-inline-start: 20px;
//             color: #0b5ed7;
//             font-size: 13px;
//         }
//         .task-details ul li {
//             margin-bottom: 6px;
//         }
//         .task-details ul li a {
//             text-decoration: none;
//             color: #0b5ed7;
//         }
//         .task-details ul li a:hover {
//             text-decoration: underline;
//         }
//         p.no-action {
//             font-style: italic;
//             color: #cc0000;
//             font-weight: 700;
//         }
//         p.no-files {
//             color: #999;
//             font-size: 13px;
//         }
//         @media print {
//             body {
//                 margin: 10mm 10mm 10mm 10mm;
//                 -webkit-print-color-adjust: exact;
//             }
//             .task-card:hover {
//                 box-shadow: none;
//             }
//         }
//     `);
//     printWindow.document.write('</style></head><body>');
//
//     // Header with meeting title and logo
//     printWindow.document.write(`
//         <div class="header-container">
//             <h1>گزارش اقدامات: ${personName}</h1>
//             <img src="/fajrgam-b.png" alt="لوگو" />
//         </div>
//     `);
//
//     // Meeting Info
//     if (meetingInfo) {
//         printWindow.document.write('<div class="meeting-info-grid">');
//         printWindow.document.write(meetingInfo);
//         printWindow.document.write('</div>');
//     }
//
//     // Task Cards
//     if (taskCards.length) {
//         taskCards.forEach(card => {
//             printWindow.document.write('<div class="task-card">');
//             printWindow.document.write(card.innerHTML);
//             printWindow.document.write('</div>');
//         });
//     } else {
//         printWindow.document.write('<p class="no-action">هیچ اقدامی برای چاپ وجود ندارد.</p>');
//     }
//
//     printWindow.document.write('</body></html>');
//     printWindow.document.close();
//
//     setTimeout(() => {
//         printWindow.print();
//     }, 1000);
// }

function printTask(title = 'بدون عنوان جلسه', personName = 'نامشخص') {
    const printableContent = document.getElementById("printable-meeting-content")?.innerHTML;
    if (!printableContent) {
        alert("هیچ محتوایی برای چاپ وجود ندارد.");
        return;
    }

    const printWindow = window.open('', '', 'height=900,width=1000');

    printWindow.document.write(`
        <html lang="fa" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <title>پرینت صورتجلسه</title>
            <link href="https://cdn.fontcdn.ir/Fonts/Vazir/Vazir.css" rel="stylesheet" type="text/css" />
            <style>
                body {
                    font-family: 'Vazir', Tahoma, sans-serif;
                    background: #f9fafb;
                    color: #222;
                    margin: 40px 30px;
                    font-size: 14px;
                    line-height: 1.6;
                    direction: rtl;
                }

                @media print {
                    body {
                        margin: 10mm;
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                    }
                }

                /* Optional: Style fixes to override dark mode or apply clean layout */
                .dark\\:bg-gray-800 { background-color: white !important; }
                .dark\\:text-white { color: black !important; }
                .text-indigo-700 { color: #0c63e4 !important; }
                a { color: #0b5ed7; text-decoration: none; }
                a:hover { text-decoration: underline; }
            </style>
        </head>
        <body>
            <div class="header-container" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #0c63e4; padding-bottom: 14px; margin-bottom: 30px;">
                <h1 style="font-size: 24px; color: #0c63e4;">گزارش اقدامات: ${personName}</h1>
                <img src="/fajrgam-b.png" alt="لوگو" style="max-width: 130px; height: auto;" />
            </div>

            <div id="printable-meeting-content">${printableContent}</div>
        </body>
        </html>
    `);

    printWindow.document.close();

    setTimeout(() => {
        printWindow.print();
    }, 1000);
}

