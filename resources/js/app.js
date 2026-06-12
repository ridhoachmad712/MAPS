// Tabler (termasuk Bootstrap 5 bundle: dropdown, collapse, modal, dsb.)
import '@tabler/core/dist/js/tabler.min.js';

// Chart.js dibundel lokal — skrip per halaman memakai window.Chart
// di dalam DOMContentLoaded (modul ini selesai dieksekusi lebih dulu).
import Chart from 'chart.js/auto';

window.Chart = Chart;
