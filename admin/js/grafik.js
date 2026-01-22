const ctx = document.getElementById('grafikPendapatan').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: LABELS,
        datasets: [
            {
                label: 'Pendapatan (Rp)',
                data: DATA_PENDAPATAN,
                borderWidth: 1
            },
            {
                label: 'Jumlah Transaksi',
                data: DATA_TRANSAKSI,
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        if (context.dataset.label === 'Pendapatan (Rp)') {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                        return context.raw + ' transaksi';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
