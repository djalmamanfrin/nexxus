<script setup lang="ts">
import Icon from '@/components/Icon.vue';

defineProps<{
    icon?: string;
    tooltip?: string;
}>();
</script>

<template>
    <div v-if="tooltip" class="group/icon relative cursor-pointer">
        <Icon v-if="icon" :name="icon" />

        <div
            class="pointer-events-none absolute right-0 top-6 z-10 w-56 scale-95 rounded-lg bg-gray-900 p-2 text-xs text-white opacity-0 shadow-lg transition-all duration-200 group-hover/icon:scale-100 group-hover/icon:opacity-100"
        >
            {{ tooltip }}
        </div>
    </div>
</template>
