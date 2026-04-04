<script setup lang="ts">
import { watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppButton from '@/components/AppButton.vue';
import { Loader2, Save } from 'lucide-vue-next';

interface Props {
    url: string;
    method?: 'post' | 'patch';
    initialData?: Record<string, any>;
}

const props = withDefaults(defineProps<Props>(), {
    method: 'post',
    initialData: () => ({}),
});

const emit = defineEmits(['success', 'created']);

const form = useForm({
    ...props.initialData,
});

const page = usePage();
watch(
    () => page.props.flash?.created,
    (created) => {
        if (created) {
            emit('created', created);
        }
    },
);

const submit = () => {
    form[props.method](props.url, {
        onSuccess: () => {
            emit('success');
            form.reset();
        },
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4">
        <slot name="fields" :form="form" />
        <div class="flex justify-end">
            <AppButton
                type="submit"
                :label="form.processing ? 'Salvando...' : 'Salvar'"
                :disabled="form.processing"
                :icon="form.processing ? Loader2 : Save"
                variant="success"
            />
        </div>
    </form>
</template>
