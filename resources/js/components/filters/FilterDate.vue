<script setup>
import { inject, ref } from 'vue'
import AppDate from "@/components/base/AppDate.vue";

const filters = inject('filters')
const inputRef = ref(null)

const props = defineProps({
    label: String,
    name: String,
    width: String,
})

function openPicker() {
    inputRef.value?.showPicker?.()
}
</script>

<template>
    <AppDate
        v-model="filters[name]"
        :label="label"
        @click="openPicker"
    />
</template>
