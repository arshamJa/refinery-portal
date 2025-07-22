function printTask(title = 'بدون عنوان جلسه', personName = 'نامشخص') {
    const printableContent = document.getElementById("printable-meeting-content")?.cloneNode(true);
    if (!printableContent) {
        alert("هیچ محتوایی برای چاپ وجود ندارد.");
        return;
    }

    function getText(selector) {
        const el = document.querySelector(selector);
        return el ? el.textContent.trim() : '';
    }

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

    const overviewParagraph = `
    <div class="info-box print:break-inside-avoid-page">
        <p><span>عنوان جلسه: </span><span>${title}</span></p>
        <p><span>رئیس جلسه: </span><span>${bossName}</span>، <span>واحد: </span><span>${bossUnit}</span>، <span>سمت: </span><span>${bossPosition}</span></p>
        <p><span>دبیر جلسه: </span><span>${scriptoriumName}</span>، <span>واحد: </span><span>${scriptoriumUnit}</span>، <span>سمت: </span><span>${scriptoriumPosition}</span></p>
        <p><span>تاریخ: </span><span>${date}</span>، <span>ساعت: </span><span>${time}</span>، <span>مکان: </span><span>${location}</span></p>
        <p><span>برگزار کننده: </span><span>${heldBy}</span>، <span>پذیرایی: </span><span>${treat}</span></p>
    </div>
    `;

    printableContent.querySelectorAll('.no-print').forEach(el => el.remove());

    const gridContainer = printableContent.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.xl\\:grid-cols-4.gap-6');
    if (gridContainer) {
        gridContainer.remove();
    }

    // Add <hr> after each article inside the cloned content for print separation
    printableContent.querySelectorAll('article').forEach(article => {
        const hr = document.createElement('hr');
        hr.style.margin = '30px 0';
        hr.style.border = 'none';
        hr.style.borderTop = '1px solid #ccc';
        article.after(hr);

        // Remove background color and border for print
        article.style.background = 'transparent';
        article.style.border = 'none';
        article.style.boxShadow = 'none';
        article.style.padding = '0';  // Optional: adjust padding if needed
    });

    // Separate "بند مذاکره:" label and content in header h3
    printableContent.querySelectorAll('article header h3').forEach(h3 => {
        const originalText = h3.textContent.trim();
        h3.innerHTML = '';  // Clear current content

        const labelSpan = document.createElement('span');
        labelSpan.classList.add('label');
        labelSpan.textContent = 'بند مذاکره:';

        const contentP = document.createElement('p');
        contentP.textContent = originalText.replace('بند مذاکره:', '').trim();

        h3.appendChild(labelSpan);
        h3.appendChild(contentP);
    });

    const printWindow = window.open('', '', 'height=1000,width=1100');
    printWindow.document.title = `چاپ اقدامات جلسه: ${title}`;

    printWindow.document.write(`
        <html lang="fa" dir="rtl">
        <head>
            <meta charset="UTF-8" />
<!--            <title>چاپ اقدامات جلسه</title>-->
            <style>
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
                article header h3 {
                    font-size: 20px;
                    color: #1e40af;
                    font-weight: 700;
                    margin-bottom: 12px;
                    padding-bottom: 8px;
                }
                article header h3 span.label {
                    font-weight: 900;
                    color: #2563eb;
                }
                article header h3 p {
                    font-size: 17px;
                    color: #1e293b;
                    margin: 0;
                    line-height: 1.5;
                    user-select: text;
                }
                hr {
                    border: none;
                    border-top: 1px solid #ccc;
                    margin: 30px 0;
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
                    .info-box, p, table, h3, ul, li {
                        page-break-inside: avoid !important;
                    }
                }
            </style>
        </head>
        <body>
            <div class="header-container">
                <h1>گزارش اقدامات: ${personName}</h1>
                <img src="/fajrgam-b.png" alt="لوگو" />
            </div>
            ${overviewParagraph}
            <div id="printable-meeting-content">
                ${printableContent.innerHTML}
            </div>
        </body>
        </html>
    `);

    printWindow.document.close();

    setTimeout(() => {
        printWindow.print();
    }, 1000);
}
