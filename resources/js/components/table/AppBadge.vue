<script setup>
const props = defineProps({
    label: String,
    color: String,
});

const map = {
    yellow: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
    green: 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
    red: 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
    gray: 'bg-gray-100 text-gray-700 dark:bg-gray-900/40 dark:text-gray-300',
};
</script>

<template>
    <span
        :class="[
            'rounded px-2 py-1 text-xs',
            map[color] || 'bg-gray-200 text-gray-800',
        ]"
    >
        {{ label }}
    </span>
</template>
