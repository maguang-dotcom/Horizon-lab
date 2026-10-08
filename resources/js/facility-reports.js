import Chart from 'chart.js/auto';

const dataElement = document.getElementById('facility-report-data');

if (dataElement) {
    const report = JSON.parse(dataElement.textContent);
    const palette = ['#1F5EE8', '#4207c1', '#0E6E5F', '#C97B3D', '#B3432B', '#64748B'];

    Chart.defaults.font.family = "'Inter', ui-sans-serif, system-ui, sans-serif";
    Chart.defaults.color = '#64748B';
    Chart.defaults.borderColor = '#EEF1F6';

    const axes = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { grid: { display: false } } },
    };

    const draw = (id, config) => {
        const canvas = document.getElementById(id);

        if (canvas) {
            new Chart(canvas, config);
        }
    };

    draw('reportTypeChart', {
        type: 'doughnut',
        data: {
            labels: report.byType.labels,
            datasets: [{ data: report.byType.counts, backgroundColor: palette, borderColor: '#fff', borderWidth: 2 }],
        },
        options: { responsive: true, maintainAspectRatio: false, cutout: '60%', plugins: { legend: { position: 'bottom' } } },
    });

    draw('reportMonthlyChart', {
        type: 'line',
        data: {
            labels: report.monthly.labels,
            datasets: [{ data: report.monthly.counts, borderColor: '#1F5EE8', backgroundColor: 'rgba(31,94,232,.12)', fill: true, tension: 0.3 }],
        },
        options: axes,
    });

    draw('reportUrgencyChart', {
        type: 'bar',
        data: {
            labels: report.byUrgency.labels,
            datasets: [{ data: report.byUrgency.counts, backgroundColor: ['#64748B', '#1F5EE8', '#C97B3D', '#B3432B'], maxBarThickness: 40 }],
        },
        options: axes,
    });

    draw('reportStatusChart', {
        type: 'bar',
        data: {
            labels: report.byStatus.labels,
            datasets: [{ data: report.byStatus.counts, backgroundColor: '#0E6E5F', maxBarThickness: 40 }],
        },
        options: axes,
    });
}
