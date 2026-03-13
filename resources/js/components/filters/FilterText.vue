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
    <div class="flex flex-col gap-1">
        <label
            v-if="label"
            class="text-xs font-medium text-gray-600"
        >
            {{ label }}
        </label>
        <div class="relative">
            <component
                v-if="icon"
                :is="icon"
                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
            />
            <AppInput
                v-model="localValue"
                :placeholder="placeholder"
                :width="width"
                :class="icon ? 'pl-10' : ''"
            />
        </div>
    </div>
</template>
