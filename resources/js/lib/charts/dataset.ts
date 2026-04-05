import { theme } from './theme';

export function createDataset(label, data, color = 'primary') {
    return {
        label,
        data,
        backgroundColor: theme.colors[color],
        borderColor: theme.borders[color],
        borderWidth: 2,
        borderRadius: 6,
        hoverBackgroundColor: theme.colors[color].replace('0.8', '1'),
    };
}
