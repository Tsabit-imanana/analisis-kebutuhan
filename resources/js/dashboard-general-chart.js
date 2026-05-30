window.renderGeneralChart = function(canvasId, first, second, third, fourth) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    const isRoleDistribution = typeof third === 'undefined';
    const labels = isRoleDistribution
        ? ['SPV', 'Employee']
        : ['Pengguna', 'Divisi', 'Total Task', 'Weekly Log'];
    const data = isRoleDistribution
        ? [first, second]
        : [first, second, third, fourth];
    const colors = isRoleDistribution
        ? ['rgba(0, 0, 0, 0.8)', 'rgba(156, 163, 175, 0.8)']
        : [
            'rgba(0, 0, 0, 0.8)',
            'rgba(75, 85, 99, 0.8)',
            'rgba(156, 163, 175, 0.8)',
            'rgba(229, 231, 235, 0.8)'
        ];

    new Chart(ctx, {
        type: 'polarArea',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: colors,
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
