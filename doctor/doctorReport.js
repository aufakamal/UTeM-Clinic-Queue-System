new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: ['Flu','Fever','Hypertension','Diabetes','Others'],
        datasets: [{
            data: [30.5,25.2,18.7,12.4,13.2],
            backgroundColor: ['#3b82f6','#ef4444','#22c55e','#a855f7','#9ca3af']
        }]
    }
});

new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [{
            data: [112,128,145,178,215,245],
            borderColor: '#3b82f6',
            fill: true,
            tension: 0.4
        }]
    }
});