<script setup>

const props = defineProps({
    type: {
        type: String,
        default: 'button'
    },
    label: {
        type: String,
        required: true
    },
    variant: {
        type: String,
        default: 'primary'
    },
    icon: {
        type: Object,
        default: null
    },
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
    <button
        :type="type"
        :class="[
            'flex items-center justify-center gap-2 px-4',
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
