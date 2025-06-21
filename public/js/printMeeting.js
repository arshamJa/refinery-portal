function printMeetingSummary(title = 'بدون عنوان') {
    const meetingElement = document.getElementById("meeting-summary");
    const clone = meetingElement.cloneNode(true);

    const getText = (selector) => meetingElement.querySelector(selector)?.innerText.trim() || '---';

    const bossName = getText('[data-role="boss-name"]');
    const bossUnit = getText('[data-role="boss-unit"]');
    const bossPosition = getText('[data-role="boss-position"]');

    const scriptoriumName = getText('[data-role="scriptorium-name"]');
    const scriptoriumUnit = getText('[data-role="scriptorium-unit"]');
    const scriptoriumPosition = getText('[data-role="scriptorium-position"]');

    const date = getText('[data-role="meeting-date"]');
    const time = getText('[data-role="meeting-time"]');
    const location = getText('[data-role="meeting-location"]');

    const heldBy = getText('[data-role="meeting-unit"]');
    const treat = getText('[data-role="meeting-treat"]');

    let overviewParagraph = `
    <div class="info-box">
        <p><span>رئیس جلسه: </span><span>${bossName}</span>، <span>واحد: </span><span>${bossUnit}</span>، <span>سمت: </span><span>${bossPosition}</span></p>
        <p><span>دبیر جلسه: </span><span>${scriptoriumName}</span>، <span>واحد: </span><span>${scriptoriumUnit}</span>، <span>سمت: </span><span>${scriptoriumPosition}</span></p>
        <p><span>تاریخ: </span><span>${date}</span>، <span>ساعت: </span><span>${time}</span>، <span>مکان: </span><span>${location}</span></p>
        <p><span>برگزار کننده: </span><span>${heldBy}</span>، <span>پذیرایی: </span><span>${treat}</span></p>
    </div>
    `;

    // Remove the grid block if it exists
    const gridBlock = clone.querySelector('.grid');
    if (gridBlock) {
        gridBlock.remove();
    }

    clone.insertAdjacentHTML('afterbegin', overviewParagraph);

    const meetingInfo = clone.innerHTML;

    const cleanedMeetingInfo = meetingInfo
        .replace(/\s+/g, ' ')
        .replace(/>\s+</g, '><')
        .trim();

    const printWindow = window.open('', '', 'height=1000,width=1100');
    printWindow.document.write('<html lang="fa"><head><title>چاپ جلسه</title>');
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
            margin: 40px 50px;
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
            max-width: 120px;
            height: auto;
        }
        .info-box {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 24px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .info-box p {
            margin: 6px 0;
        }
        .info-box span {
            font-weight: 600;
            color: #555;
            display: inline;
            margin: 0;
            padding: 0;
            white-space: nowrap;
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
        }
        thead {
            background-color: #007acc;
            color: #fff;
        }
        tbody tr:nth-child(odd) {
            background-color: #f4f7fa;
        }
        h3 {
            font-size: 16px;
            font-weight: bold;
            color: #444;
            border-bottom: 1px solid #ccc;
            padding-bottom: 6px;
            margin-top: 40px;
        }
        ul {
            padding-right: 20px;
            list-style: disc;
        }
        li {
            margin-bottom: 6px;
        }
        button, .no-print, .screen-only {
            display: none !important;
        }
        @media print {
            .print\\:flex-nowrap {
                flex-wrap: nowrap !important;
            }
            .print\\:break-inside-avoid-page {
                break-inside: avoid-page !important;
                page-break-inside: avoid !important;
            }
        }
    </style>`);
    printWindow.document.write('</head><body>');

    printWindow.document.write(`
        <div class="header-container">
            <h1>گزارش جلسه: ${title}</h1>
            <img src="/fajrgam-b.png" alt="لوگو" />
        </div>
    `);

    printWindow.document.write(cleanedMeetingInfo);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    setTimeout(() => {
        printWindow.print();
    }, 1000);
}

