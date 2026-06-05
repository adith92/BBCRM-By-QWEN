import '../css/app.css';

import Alpine from 'alpinejs';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);
Chart.defaults.font.family = 'Inter, ui-sans-serif, system-ui, sans-serif';
Chart.defaults.font.size = 12;
Chart.defaults.color = '#64748b';

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.store('theme', {
    dark: true,
    init() {
        const stored = localStorage.getItem('theme');
        this.dark = stored ? stored === 'dark' : true;
        document.documentElement.classList.toggle('dark', this.dark);
    },
    toggle() {
        this.dark = !this.dark;
        localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', this.dark);
    },
});

Alpine.start();
