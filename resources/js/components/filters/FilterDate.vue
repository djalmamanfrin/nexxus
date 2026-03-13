<script setup>
import { inject, ref } from 'vue'
import AppInput from "@/components/AppInput.vue";

const filters = inject('filters')
const inputRef = ref(null)

const props = defineProps({
    name: String,
    width: String,
})

function openPicker() {
    inputRef.value?.showPicker?.()
}
</script>

<template>
    <AppInput
        ref="inputRef"
        type="date"
        v-model="filters[name]"
        :class="['form-input cursor-pointer', width]"
        @click="openPicker"
    />
</template>
