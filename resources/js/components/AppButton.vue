<script setup>

import { Link } from '@inertiajs/vue3'

const props = defineProps({
    type: {
        type: String,
        default: 'button'
    },
    label: {
        type: String,
        required: true
    },
    href: String,
    variant: {
        type: String,
        default: 'primary'
    },
    icon: Function,
    iconPosition: {
        type: String,
        default: 'left'
    },
    fullWidth: {
        type: Boolean,
        default: false
    }
})

</script>

<template>
    <Link
        v-if="href"
        :href="href"
        :class="[
            'btn flex items-center justify-center gap-2 px-4 py-2',
            `btn-${variant}`,
            { 'w-full': fullWidth }
        ]"
    >
        <component
            v-if="icon && iconPosition === 'left'"
            :is="icon"
            class="w-4 h-4"
        />
            <span>{{ label }}</span>
        <component
            v-if="icon && iconPosition === 'right'"
            :is="icon"
            class="w-4 h-4"
        />
    </Link>

    <button
        v-else
        :type="type"
        :class="[
            'flex items-center justify-center gap-2 px-4 py-2',
            `btn-${variant}`,
            { 'w-full': fullWidth }
        ]"
    >
        <component
            v-if="icon && iconPosition === 'left'"
            :is="icon"
            class="w-4 h-4"
        />
            <span>{{ label }}</span>
        <component
            v-if="icon && iconPosition === 'right'"
            :is="icon"
            class="w-4 h-4"
        />

    </button>
</template>
