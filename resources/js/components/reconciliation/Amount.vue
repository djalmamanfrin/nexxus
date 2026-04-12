<script setup lang="ts">
defineProps<{
    amount: string;
}>();
</script>

<template>
    <div class="flex flex-col items-center text-center">
        <span class="text-xs tracking-wide text-gray-400 uppercase">
            Valor
        </span>

        <span class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
            {{ amount }}
        </span>
    </div>
</template>

<style scoped></style>
