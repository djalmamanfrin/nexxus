<script setup lang="ts">
import { Attachment } from '@/types/index.d.ts';

const props = defineProps<{
    attachments?: Attachment[];
}>();

</script>

<template>
    <span v-if="props.attachments?.length">
        {{ props.attachments[0].original_name }}
    </span>
    <span v-else>-</span>
</template>
