<template>
    <div v-if="open" class="fixed inset-0 z-50 flex">

        <!-- Overlay -->
        <transition
            enter-active-class="transition-opacity duration-700 ease-out delay-500"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-300 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="open"
                class="fixed inset-0 bg-black/40 backdrop-blur-sm"
                @click="close"
            />
        </transition>

        <!-- Drawer -->
        <transition
            enter-active-class="transition-transform duration-[1000ms] ease-[cubic-bezier(0.16,1,0.3,1)]"
            enter-from-class="translate-x-full scale-x-90"
            enter-to-class="translate-x-0 scale-x-100"
            leave-active-class="transition-transform duration-500 ease-in"
            leave-from-class="translate-x-0 scale-x-100"
            leave-to-class="translate-x-full scale-x-90"
        >
            <div
                v-if="open"
                class="relative h-full w-full max-w-2xl bg-white shadow-2xl ml-auto origin-right"
            >
                <!-- Header -->
                <div class="flex items-center justify-between border-b p-4">
                    <h2 class="font-semibold text-lg">
                        <slot name="title" />
                    </h2>

                    <button @click="close">✕</button>
                </div>

                <!-- Content -->
                <div class="h-full overflow-y-auto p-4">
                    <slot />
                </div>
            </div>
        </transition>

    </div>
</template>

<script setup>
const props = defineProps({
    open: Boolean
})

const emit = defineEmits(['close'])

const close = () => emit('close')
</script>
