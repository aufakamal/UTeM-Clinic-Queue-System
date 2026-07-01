new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: reportData.illnessLabels,
        datasets: [{
            data: reportData.illnessData,
            backgroundColor: ['#3b82f6', '#ef4444', '#22c55e', '#a855f7', '#9ca3af']
        }]
    }
});

new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: reportData.monthlyLabels,
        datasets: [{
            label: 'Appointments',
            data: reportData.monthlyData,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.15)',
            fill: true,
            tension: 0.4
        }]
    }
});