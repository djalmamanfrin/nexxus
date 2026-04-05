import { theme } from './theme';

export const defaultOptions = {
    responsive: true,
    maintainAspectRatio: false,

    plugins: {
        legend: {
            position: 'top',
            labels: {
                color: document.documentElement.classList.contains('dark')
                    ? '#e5e7eb'
                    : '#374151',
                font: {
                    size: 12,
                    weight: '600',
                },
                usePointStyle: true,
                pointStyle: 'circle',
            },
        },

        tooltip: {
            ...theme.tooltip,
        },
    },

    scales: {
        x: {
            ticks: {
                color: theme.text.secondary,
                font: {
                    size: 11,
                },
            },
            grid: {
                color: theme.grid.color,
                drawBorder: false,
            },
        },

        y: {
            ticks: {
                color: theme.text.secondary,
                font: {
                    size: 11,
                },
            },
            grid: {
                color: theme.grid.color,
                drawBorder: false,
            },
        },
    },
};
