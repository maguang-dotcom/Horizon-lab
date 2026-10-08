import Chart from 'chart.js/auto';

const chartDataElement = document.getElementById('facility-dashboard-chart-data');

if (!chartDataElement) {
    throw new Error('Facility dashboard chart data is missing.');
}

const { chartData, totalRequests, serviceTypeBreakdown } = JSON.parse(chartDataElement.textContent);

Chart.defaults.font.family = "'Inter', ui-sans-serif, system-ui, sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.color = '#64748B';
Chart.defaults.borderColor = '#EEF1F6';

const bar = (label, key, color) => ({
    label,
    data: chartData[key],
    backgroundColor: color,
    maxBarThickness: 34,
});

new Chart(document.getElementById('requestsOverviewChart'), {
    type: 'bar',
    data: {
        labels: chartData.months,
        datasets: [
            bar('Maintenance', 'maintenance', '#1F5EE8'),
            bar('Calibration', 'calibration', '#4207c1'),
            bar('Repair', 'repair', '#0b1bf9'),
            bar('Other', 'other', '#2e1b7b'),
        ],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: { stacked: true, grid: { display: false } },
            y: { stacked: true, beginAtZero: true, ticks: { precision: 0 } },
        },
        plugins: {
            legend: {
                position: 'top',
                align: 'start',
                labels: { boxWidth: 8, boxHeight: 8, usePointStyle: true },
            },
        },
    },
});

const centerText = {
    id: 'centerText',
    afterDraw(chart) {
        const { ctx, chartArea: { left, right, top, bottom } } = chart;
        const x = (left + right) / 2;
        const y = (top + bottom) / 2;
        ctx.save();
        ctx.textAlign = 'center';
        ctx.fillStyle = '#0F1B3D';
        ctx.font = '700 26px ' + Chart.defaults.font.family;
        ctx.fillText(totalRequests, x, y + 2);
        ctx.fillStyle = '#64748B';
        ctx.font = '12px ' + Chart.defaults.font.family;
        ctx.fillText('Total Requests', x, y + 20);
        ctx.restore();
    },
};

new Chart(document.getElementById('serviceTypeChart'), {
    type: 'doughnut',
    data: {
        labels: serviceTypeBreakdown.map(({ label }) => label),
        datasets: [{
            data: serviceTypeBreakdown.map(({ count }) => count),
            backgroundColor: serviceTypeBreakdown.map(({ color }) => color),
            borderColor: '#fff',
            borderWidth: 2,
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '62%',
        plugins: { legend: { display: false } },
    },
    plugins: [centerText],
});
