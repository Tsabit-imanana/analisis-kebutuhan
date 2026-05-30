window.renderFinanceChart = function(canvasId, totalRealisasi, sisaAnggaran, persentase) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Total Realisasi', 'Sisa Anggaran'],
            datasets: [{
                data: [totalRealisasi, sisaAnggaran],
                backgroundColor: [
                    '#000000',
                    '#e5e7eb'
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
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
                                label += ': Rp ';
                            }
                            label += new Intl.NumberFormat('id-ID').format(context.raw);

                            if(context.label === 'Total Realisasi') {
                                label += ' (' + persentase + '%)';
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
};
