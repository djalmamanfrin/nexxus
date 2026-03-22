<script setup>
import { inject } from 'vue'

const props = defineProps({
    name: String
})

const { activeTab } = inject('drawerTabs')
</script>

<template>
    <div v-show="activeTab === name" class="p-4">
        <slot />
    </div>
</template>
