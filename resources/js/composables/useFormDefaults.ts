import { watch } from 'vue';

export function useFormDefaults<T>(
    itemRef: () => T | null,
    form: any,
    mapFn: (item: T) => Record<string, any>,
) {
    watch(
        itemRef,
        (item) => {
            if (!item) return;

            form.defaults(mapFn(item));
            form.reset();
        },
        { immediate: true },
    );
}
