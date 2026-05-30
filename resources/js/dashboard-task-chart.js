window.renderTaskChart = function(canvasId, todo, onProgress, submitted, accepted, rejected) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: ['Todo', 'On Progress', 'Submitted', 'Accepted', 'Rejected'],
            datasets: [{
                label: 'Jumlah Task',
                data: [todo, onProgress, submitted, accepted, rejected],
                backgroundColor: 'rgba(0, 0, 0, 0.2)',
                borderColor: '#000000',
                pointBackgroundColor: '#000000',
                pointBorderColor: '#ffffff',
                pointHoverBackgroundColor: '#ffffff',
                pointHoverBorderColor: '#000000',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    pointLabels: {
                        font: {
                            family: "'Inter', sans-serif",
                            size: 13,
                            weight: '500'
                        },
                        color: '#374151'
                    },
                    ticks: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' Task';
                        }
                    }
                }
            }
        }
    });
};
