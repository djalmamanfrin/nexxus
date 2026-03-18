<script setup>
import {
    Dialog,
    DialogHeader,
    DialogTitle,
    DialogContent,
} from '@/components/ui/dialog/index.ts';

defineProps({
    open: Boolean,
    title: String,
});

const emit = defineEmits(['update:open']);
</script>

<template>
    <Dialog :open="open" @update:open="(val) => emit('update:open', val)">
        <DialogContent class="w-full max-w-lg md:max-w-3xl lg:max-w-5xl">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
            </DialogHeader>

            <slot />
        </DialogContent>
    </Dialog>
</template>
