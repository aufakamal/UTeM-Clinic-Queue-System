const dailyChart = document.getElementById("dailyChart");

if (dailyChart)
{
    new Chart(dailyChart, {
        type: "line",
        data: {
            labels: dailyLabels,
            datasets: [
                {
                    label: "Prescriptions Dispensed",
                    data: dailyValues,
                    borderColor: "#2563EB",
                    backgroundColor: "rgba(37, 99, 235, 0.12)",
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: "bottom"
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}