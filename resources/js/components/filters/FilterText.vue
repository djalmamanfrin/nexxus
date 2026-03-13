<script setup>

import { debounce } from 'lodash'
import { inject, ref, watch, onUnmounted } from 'vue'
import AppInput from "@/components/base/AppInput.vue";

const filters = inject('filters')

const props = defineProps({
    label: String,
    name: String,
    placeholder: String,
    debounce: {
        default: 400
    },
    icon: {
        type: Object,
        default: null
    },
    width: {
        default: 'w-96'
    }
})

const localValue = ref(filters[props.name] || '')

const updateFilter = debounce((value) => {
    filters[props.name] = value
}, props.debounce)

watch(localValue, (value) => {
    updateFilter(value)
})

watch(
    () => filters[props.name],
    (value) => {
        if (value !== localValue.value) {
            localValue.value = value
        }
    }
)

onUnmounted(() => {
    updateFilter.cancel()
})

</script>

<template>
    <AppInput
        :label="label"
        v-model="localValue"
        :placeholder="placeholder"
        :icon="icon"
        :width="width"
    />
</template>
