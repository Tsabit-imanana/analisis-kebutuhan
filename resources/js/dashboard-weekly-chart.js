window.renderWeeklyChart = function(canvasId, pending, confirmed) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Belum Dikonfirmasi', 'Sudah Dikonfirmasi'],
            datasets: [{
                data: [pending, confirmed],
                backgroundColor: [
                    '#e5e7eb',
                    '#000000'
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
                    position: 'bottom',
                    labels: {
                        font: {
                            family: "'Inter', sans-serif",
                            size: 14
                        },
                        color: '#374151',
                        padding: 20
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.raw + ' Log';
                            return label;
                        }
                    }
                }
            }
        }
    });
};
