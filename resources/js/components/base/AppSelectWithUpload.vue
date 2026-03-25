<script setup lang="ts">
import { ref } from 'vue';
import { UploadCloudIcon } from 'lucide-vue-next';
import AppSelect from './AppSelect.vue';
import { SelectOption } from '@/types/select';
import Upload from '@/pages/attachments/Upload.vue';

interface Props {
    label?: string;
    modelValue?: string | number;
    url: string;
    width?: string;
}

const props = withDefaults(defineProps<Props>(), {
    width: 'w-full',
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
        <!-- Label + botão -->
        <div class="flex items-center justify-between">
            <label
                v-if="label"
                class="text-sm font-medium text-gray-600 dark:text-gray-300"
            >
                {{ label }}
            </label>

            <Upload
                @created="handleCreated"
                url="expenses"
                label="Upload"
                :btnIcon="UploadCloudIcon"
                btnVariant="link"
                class="text-xs"
            />
        </div>

        <!-- Select -->
        <AppSelect
            :modelValue="modelValue"
            :url="url"
            :options="options.length ? options : undefined"
            @update:modelValue="emit('update:modelValue', $event)"
            @selected="emit('selected', $event)"
        />
    </div>
</template>
