<script setup>
import { inject, onMounted } from 'vue'

const props = defineProps({
    name: String,
    label: String
})

const { activeTab, setTab } = inject('drawerTabs')

onMounted(() => {
    if (!activeTab.value) {
        setTab(props.name)
    }
})
</script>

<template>
    <button
        @click="setTab(name)"
        :class="[
      'px-4 py-2 text-sm font-medium transition-colors',
      activeTab === name
        ? 'border-b-2 border-blue-500 text-blue-600 dark:text-blue-400'
        : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'
    ]"
    >
        {{ label }}
    </button>
</template>
