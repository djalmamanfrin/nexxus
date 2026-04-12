<script setup lang="ts">
defineProps<{
    title: string;
}>();
</script>

<template>
    <div class="flex flex-col">
        <!-- 🏷️ HEADER -->
        <div
            class="mb-3 rounded-lg uppercase border border-gray-200 bg-gray-50 px-3 py-2 text-center text-sm font-semibold text-gray-700 shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:text-gray-200"
        >
            {{ title }}
        </div>

        <div class="flex flex-col">
            <slot />
        </div>
    </div>
</template>
