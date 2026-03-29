<script setup lang="ts">
import AppLabel from '@/components/base/AppLabel.vue';
import { formatDateTime } from '@/lib/date';

defineProps<{
    item: any;
}>();
</script>

<template>
    <div v-if="item" class="space-y-4">
        <div class="grid grid-cols-1 gap-4">
            <slot />
        </div>

        <div class="flex items-center justify-between pt-2">
            <AppLabel label="Criado em:" />
            <p class="text-sm text-gray-500">
                {{ formatDateTime(item.created_at) }}
            </p>
        </div>
    </div>
</template>
