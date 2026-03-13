<script setup>

import { debounce } from 'lodash'
import { inject, ref, watch } from 'vue'
import AppInput from "@/components/AppInput.vue";

const filters = inject('filters')

const props = defineProps({
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
        default: 'w-64'
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

</script>

<template>
    <div class="relative">
        <component
            v-if="icon"
            :is="icon"
            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
        />
        <AppInput
            v-model="localValue"
            :placeholder="placeholder"
            :class="['form-input', width, icon ? 'pl-10' : 'pl-3']"
        />
    </div>
</template>
