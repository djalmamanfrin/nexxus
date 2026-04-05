<script setup lang="ts">
import { ref } from 'vue';
import { UploadCloudIcon } from 'lucide-vue-next';
import AppSelect from './AppSelect.vue';
import { SelectOption } from '@/types/select';
import AppUploadModal from '@/components/base/AppUploadModal.vue';
import AppLabel from '@/components/base/AppLabel.vue';

interface Props {
    label?: string;
    modelValue?: string | number;
    url: string;
    width?: string;
    required?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    width: 'w-full',
    required: true,
});

const emit = defineEmits(['update:modelValue', 'created', 'selected']);

const options = ref<SelectOption[]>([]);

const handleCreated = (item: SelectOption) => {
    console.log('handleCreated', item);
    options.value.push(item);

    emit('update:modelValue', item.value);
    emit('created', item);
};
</script>

<template>
    <div class="flex w-full flex-col" :class="width">
        <div class="flex items-center justify-between">
            <AppLabel :label="label" :required="required" />

            <AppUploadModal
                @created="handleCreated"
                url="expenses"
                label="Upload"
                :btnIcon="UploadCloudIcon"
                btnVariant="link"
                class="text-xs"
            />
        </div>

        <AppSelect
            :modelValue="modelValue"
            :url="url"
            :options="options.length ? options : undefined"
            @update:modelValue="emit('update:modelValue', $event)"
            @selected="emit('selected', $event)"
            :required="required"
        />
    </div>
</template>
