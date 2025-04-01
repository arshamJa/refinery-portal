// this is the Column-Chart
function getMeetings(year, month, status) {
    if (yearDataMeeting[year] && yearDataMeeting[year][status] && yearDataMeeting[year][status][month]) {
        return yearDataMeeting[year][status][month];
    }
    return 0;
}
function generateMeetingData(years) {
    const result = {};
    years.forEach(year => {
        result[year] = {
            cancelled: [],
            notCancelled: [],
            pending: [],
        };
        let startMonth = currentMonthMeeting === 0 ? 1 : 7;
        let endMonth = currentMonthMeeting === 0 ? 6 : 12;
        for (let month = startMonth; month <= endMonth; month++) {
            if (currentMonthMeeting === 0) {
                result[year].cancelled.push(getMeetings(year, month, 'cancelled'));
                result[year].notCancelled.push(getMeetings(year, month, 'notCancelled'));
                result[year].pending.push(getMeetings(year, month, 'pending'));
            } else {
                result[year].cancelled.push(getMeetings(year, month, 'cancelled'));
                result[year].notCancelled.push(getMeetings(year, month, 'notCancelled'));
                result[year].pending.push(getMeetings(year, month, 'pending'));
            }

        }
    });
    return result;
}
const yearsMeeting = Object.keys(yearDataMeeting).map(Number);
let processedYearDataMeeting = generateMeetingData(yearsMeeting);
const optionsMeeting = {
    series: [
        {
            name: "لغو شده",
            color: "#F05252",
            data: processedYearDataMeeting[currentYearMeeting].cancelled,
        },
        {
            name: "انجام شده",
            color: "#31C48D",
            data: processedYearDataMeeting[currentYearMeeting].notCancelled,
        },
        {
            name: "در انتظار",
            color: "#FFA500",
            data: processedYearDataMeeting[currentYearMeeting].pending,
        },
    ],
    chart: {
        type: "bar",
        height: "320px",
        fontFamily: "Inter, sans-serif",
        toolbar: {
            show: false,
        },
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: "70%",
            borderRadiusApplication: "end",
            borderRadius: 8,
        },
    },
    tooltip: {
        shared: true,
        intersect: false,
        formatter: function (value) {
            return value
        }
    },
    states: {
        hover: {
            filter: {
                type: "darken",
                value: 1,
            },
        },
    },
    stroke: {
        show: true,
        width: 0,
        colors: ["transparent"],
    },
    grid: {
        show: false,
        strokeDashArray: 4,
        padding: {
            left: 2,
            right: 2,
            top: -14
        },
    },
    dataLabels: {
        enabled: false,
    },
    legend: {
        show: false,
    },
    xaxis: {
        floating: false,
        labels: {
            show: true,
            style: {
                fontFamily: "Inter, sans-serif",
                cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
            }
        },
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        },
        categories: generateCategoriesMeeting(currentMonthMeeting),
    },
    yaxis: {
        show: false,
    },
    fill: {
        opacity: 1,
    },
};
function generateCategoriesMeeting(month) {
    const months1 = ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور"];
    const months2 = ["مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"];
    if (month === 0) {
        return months1;
    } else {
        return months2;
    }
}
if (document.getElementById("column-chart") && typeof ApexCharts !== 'undefined') {
    const chart = new ApexCharts(document.getElementById("column-chart"), optionsMeeting);
    chart.render();

    function updateChartData() {
        processedYearDataMeeting = generateMeetingData(yearsMeeting);
        optionsMeeting.series[0].data = processedYearDataMeeting[currentYearMeeting].cancelled;
        optionsMeeting.series[1].data = processedYearDataMeeting[currentYearMeeting].notCancelled;
        optionsMeeting.series[2].data = processedYearDataMeeting[currentYearMeeting].pending;
        optionsMeeting.xaxis.categories = generateCategoriesMeeting(currentMonthMeeting); // Corrected line
        chart.updateOptions(optionsMeeting);
    }

    document.getElementById("yearSelectMeeting").addEventListener("change", function () {
        currentYearMeeting = parseInt(this.value);
        updateChartData();
    });

    document.getElementById("monthSelectMeeting").addEventListener("change", function () {
        currentMonthMeeting = parseInt(this.value);
        updateChartData();
    });

}
// the end of Column-Chart


// this is the Radial-Chart
const userCountElement = document.getElementById('user-count');
const systemCountElement = document.getElementById('system-count');
const departmentCountElement = document.getElementById('department-count');
const getChartOptions = () => {
    return {
        series: [users, organizations, departments],
        colors: ["#1C64F2", "#16BDCA", "#FDBA8C"],
        chart: {
            height: "350px",
            width: "100%",
            type: "radialBar",
            sparkline: {
                enabled: true,
            },
        },
        plotOptions: {
            radialBar: {
                track: {
                    background: '#E5E7EB',
                },
                dataLabels: {
                    show: false,
                },
                hollow: {
                    margin: 0,
                    size: "32%",
                }
            },
        },
        grid: {
            show: false,
            strokeDashArray: 4,
            padding: {
                left: 2,
                right: 2,
                top: -23,
                bottom: -20,
            },
        },
        labels: ["کاربر", "سامانه", "دپارتمان"],
        legend: {
            show: true,
            position: "bottom",
            fontFamily: "Inter, sans-serif",
        },
        tooltip: {
            enabled: true,
            x: {
                show: false,
            },
        },
        yaxis: {
            show: false,
            labels: {
                formatter: function (value) {
                    return value;
                }
            }
        }
    }
}
if (document.getElementById("radial-chart") && typeof ApexCharts !== 'undefined') {
    const chart = new ApexCharts(document.querySelector("#radial-chart"), getChartOptions());
    chart.render();
}
// the end of Radial-Chart


// this is the Bar-Chart
function getTasks(year, month, status) {
    if (yearData[year] && yearData[year][status] && yearData[year][status][month]) {
        return yearData[year][status][month];
    }
    return 0;
}
function generateYearData(years) {
    const result = {};
    years.forEach(year => {
        result[year] = {
            done: [],
            notDone: [],
            delayed: [],
        };
        let startMonth = currentMonth === 0 ? 1 : 7; // Determine start month
        let endMonth = currentMonth === 0 ? 6 : 12; // Determine end month
        for (let month = startMonth; month <= endMonth; month++) {
            result[year].done.push(getTasks(year, month, 'done'));
            result[year].notDone.push(getTasks(year, month, 'notDone'));
            result[year].delayed.push(getTasks(year, month, 'delayed'));

        }
    });
    return result;
}
const years = Object.keys(yearData).map(Number);
let processedYearData = generateYearData(years);
const options = {
    series: [
        {
            name: "اقدامات انجام شده",
            color: "#31C48D",
            data: processedYearData[currentYear].done,
        },
        {
            name: "اقدامات انجام نشده",
            data: processedYearData[currentYear].notDone,
            color: "#F05252",
        },
        {
            name: "اقدامات انجام شده با تاخیر",
            data: processedYearData[currentYear].delayed,
            color: "#e11ab2",
        }
    ],
    chart: {
        sparkline: {
            enabled: false,
        },
        type: "bar",
        width: "100%",
        height: 400,
        toolbar: {
            show: false,
        }
    },
    fill: {
        opacity: 1,
    },
    plotOptions: {
        bar: {
            horizontal: true,
            columnWidth: "100%",
            borderRadiusApplication: "end",
            borderRadius: 6,
            dataLabels: {
                position: "top",
            },
        },
    },
    legend: {
        show: true,
        position: "bottom",
        fontSize: "16px",
    },
    dataLabels: {
        enabled: false,
    },
    tooltip: {
        shared: true,
        intersect: false,
        formatter: function (value) {
            return value
        }
    },
    xaxis: {
        labels: {
            show: true,
            style: {
                fontFamily: "Inter, sans-serif",
                cssClass: 'text-sm font-normal fill-gray-600 dark:fill-gray-400'
            },
            formatter: function (value) {
                return value
            }
        },
        categories: generateCategories(currentYear, currentMonth),
        axisTicks: {
            show: false,
        },
        axisBorder: {
            show: false,
        },
    },
    yaxis: {
        labels: {
            show: true,
            style: {
                fontFamily: "Inter, sans-serif",
                cssClass: 'text-sm font-normal fill-gray-900 dark:fill-gray-400'
            },
            // offsetX: -35
        }
    },
    grid: {
        show: true,
        strokeDashArray: 4,
        padding: {
            left: 2,
            right: 2,
            top: -20
        },
    },
    fill: {
        opacity: 1,
    }
};
function generateCategories(year, month) {
    const months1 = ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور"];
    const months2 = ["مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"];

    if (month === 0) {
        return months1;
    } else {
        return months2;
    }
}
if (document.getElementById("bar-chart") && typeof ApexCharts !== 'undefined') {
    const chart = new ApexCharts(document.getElementById("bar-chart"), options);
    chart.render();

    document.getElementById("yearSelect").addEventListener("change", function () {
        currentYear = parseInt(this.value);
        processedYearData = generateYearData(years); // Regenerate data
        options.xaxis.categories = generateCategories(currentYear, currentMonth);
        options.series[0].data = processedYearData[currentYear].done;
        options.series[1].data = processedYearData[currentYear].notDone;
        options.series[2].data = processedYearData[currentYear].delayed;

        chart.updateOptions(options);
    });
    document.getElementById("monthSelect").addEventListener("change", function () {
        currentMonth = parseInt(this.value);
        processedYearData = generateYearData(years); // Regenerate data
        options.xaxis.categories = generateCategories(currentYear, currentMonth);
        options.series[0].data = processedYearData[currentYear].done;
        options.series[1].data = processedYearData[currentYear].notDone;
        options.series[2].data = processedYearData[currentYear].delayed;

        chart.updateOptions(options);
    });
}
