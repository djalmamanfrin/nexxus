<script setup>
import {
    Dialog,
    DialogHeader,
    DialogTitle,
    DialogContent,
    DialogDescription,
} from '@/components/ui/dialog/index.ts';

defineProps({
    open: Boolean,
    title: String,
    description: String,
});

const emit = defineEmits(['update:open']);
</script>

<template>
    <Dialog :open="open" @update:open="(val) => emit('update:open', val)">
        <DialogContent class="w-auto w-full max-w-[90vw]">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription> {{description}} </DialogDescription>
            </DialogHeader>

            <slot />
        </DialogContent>
    </Dialog>
</template>
