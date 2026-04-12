<script setup lang="ts">
defineProps<{
    title: string;
}>();
</script>

<template>
    <div class="flex flex-col gap-2 text-sm">
        <span class="text-xs font-medium tracking-wide text-gray-400 uppercase">
            {{ title }}
        </span>

        <slot />
    </div>
</template>
