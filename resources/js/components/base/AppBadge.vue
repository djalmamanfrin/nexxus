<script setup>
const props = defineProps({
    label: String,
    color: String,
});

const map = {
    yellow: 'bg-yellow-100 text-yellow-800',
    green: 'bg-green-100 text-green-800',
    red: 'bg-red-100 text-red-800',
};
</script>

<template>
    <span
        :class="[
            'rounded px-2 py-1 text-xs',
            map[color] || 'bg-gray-100 text-gray-800',
        ]"
    >
        {{ label }}
    </span>
</template>
