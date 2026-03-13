<script setup>
const props = defineProps({
    filters: Object
})
</script>

<template>
    <div class="flex flex-wrap gap-2">
        <template
            v-for="(value, key) in filters"
            :key="key"
        >
            <span
              v-if="value"
              class="px-2 py-1 text-xs bg-gray-200 rounded"
            >
                {{ key }}: {{ value }}
            </span>
        </template>
    </div>
</template>
