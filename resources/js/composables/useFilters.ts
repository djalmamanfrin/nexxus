import { reactive } from 'vue'
import { debounce } from 'lodash'
import { router } from '@inertiajs/vue3'

export function useFilters<T extends Record<string, any>>(
    initialFilters: T,
    url: string,
    delay = 400
) {

    const filters = reactive({ ...initialFilters }) as T

    function activeFilters(): Record<string, any> {
        return Object.fromEntries(
            Object.entries(filters).filter(([_, value]) =>
                value !== '' && value !== null && value !== undefined
            )
        )
    }

    const search = debounce(() => {
        router.get(url, activeFilters() as Record<string, any>, {
            preserveState: true,
            preserveScroll: true,
        })
    }, delay)

    function clear() {
        Object.keys(filters).forEach(key => {
            filters[key as keyof T] = '' as T[keyof T]
        })

        search()
    }

    return {
        filters,
        search,
        clear
    }
}
