import { router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useFilters } from '@/composables/useFilters';

export function useCrud(schema: any) {
    const open = ref(false);
    const selectedItem = ref<any>(null);

    const dataForm = useForm({ ...schema.form.initial });
    const fileForm = useForm({ attachment: null });

    const baseUrl = `/${schema.entity}`;

    const tabs = computed(() =>
        schema.tabs.map((tab: any) => {
            if (tab.name === 'file') {
                return { ...tab, form: fileForm };
            }
            return { ...tab, form: dataForm };
        }),
    );

    const { filters, search, clear } = useFilters(
        {
            search_by: schema.filters.search_by || null,
            status: schema.filters.status || null,
            is_active: schema.filters.is_active || null,
        },
        baseUrl,
    );

    function handleAction({ action, item }: any) {
        const map: any = {
            edit: handleEdit,
            delete: handleDelete,
        };

        map[action]?.(item);
    }

    function handleEdit(item: any) {
        selectedItem.value = item;

        let mapped = schema.form.map(item);

        dataForm.defaults({
            ...schema.form.initial,
            ...mapped,
        });

        dataForm.reset();
        fileForm.reset();

        open.value = true;
    }

    function handleDelete(item: any) {
        if (!confirm('Tem certeza que deseja excluir?')) return;

        router.delete(`${baseUrl}/${item.id}`, {
            preserveScroll: true,
        });
    }

    function handleSave() {
        const id = selectedItem.value?.id;
        if (!id) return;

        if (dataForm.isDirty) {
            dataForm.patch(`${baseUrl}/${id}`, {
                preserveState: true,
                onSuccess: () => {
                    open.value = false;
                    dataForm.reset();
                },
            });
        }

        if (fileForm.isDirty) {
            fileForm.post(`${baseUrl}/${id}/attachments`, {
                forceFormData: true,
                preserveState: true,
                onSuccess: () => {
                    open.value = false;
                    fileForm.reset();
                },
            });
        }
    }

    return {
        open,
        baseUrl,
        filters,
        search,
        clear,
        selectedItem,
        tabs,
        actions: schema.actions,
        handleAction,
        handleSave,
    };
}
