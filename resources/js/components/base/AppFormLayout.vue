<script setup lang="ts">
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
            <slot name="footer" />
        </div>
    </div>
</template>
