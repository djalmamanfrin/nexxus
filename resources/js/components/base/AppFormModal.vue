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
});

const emit = defineEmits(['update:open']);
</script>

<template>
    <Dialog :open="open" @update:open="(val) => emit('update:open', val)">
        <DialogContent class="w-full max-w-lg md:max-w-2xl lg:max-w-4xl">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
            </DialogHeader>
            <DialogDescription>
                Preencha os dados abaixo para inserir um novo pagamento
            </DialogDescription>

            <slot />
        </DialogContent>
    </Dialog>
</template>
