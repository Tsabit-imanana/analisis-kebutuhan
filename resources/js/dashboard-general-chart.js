window.renderGeneralChart = function(canvasId, users, divisions, tasks, logs) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    new Chart(ctx, {
        type: 'polarArea',
        data: {
            labels: ['Pengguna', 'Divisi', 'Total Task', 'Weekly Log'],
            datasets: [{
                data: [users, divisions, tasks, logs],
                backgroundColor: [
                    'rgba(0, 0, 0, 0.8)',
                    'rgba(75, 85, 99, 0.8)',
                    'rgba(156, 163, 175, 0.8)',
                    'rgba(229, 231, 235, 0.8)'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        font: {
                            family: "'Inter', sans-serif",
                            size: 13
                        },
                        color: '#374151',
                        padding: 16
                    }
                }
            },
            scales: {
                r: {
                    ticks: {
                        display: false
                    },
                    grid: {
                        color: '#f3f4f6'
                    }
                }
            }
        }
    });
};
