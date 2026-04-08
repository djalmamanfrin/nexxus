export type FilterMeta<T = any> = {
    label: string;
    format?: (value: T) => string;
    clear?: (value: T) => string;
};
