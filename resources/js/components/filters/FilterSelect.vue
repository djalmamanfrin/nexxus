<script setup>
import { inject } from 'vue'
import AppSelect from "@/components/base/AppSelect.vue";

const filters = inject('filters')

const props = defineProps({
    label: String,
    name: String,
    options: Array,
    width: String
})
</script>

<template>
    <AppSelect
        v-model="filters[name]"
        :label="label"
        :options="options"
        :width="width"
    />
</template>
