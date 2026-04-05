<script setup lang="ts">
import { cn } from '@/lib/utils';
import * as icons from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    name: string;
    class?: string;
    size?: number | string;
    color?: string;
    strokeWidth?: number | string;
}

const props = withDefaults(defineProps<Props>(), {
    class: '',
    size: 16,
    strokeWidth: 2,
});

const className = computed(() => cn('h-4 w-4', props.class));
const iconComponents = icons as Record<string, any>;

const normalizeIconName = (name: string) =>
    name
        .split('-')
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join('');

const icon = computed(() => {
  if (!props.name) return null;

  const normalizedIconName = normalizeIconName(props.name);
  const iconComponent = iconComponents[normalizedIconName] || null;

  if (!iconComponent) {
    console.warn(`Icon "${normalizedIconName}" not found`);
  }

  return iconComponent;
});
</script>

<template>
    <component
        v-if="icon"
        :is="icon"
        :class="className"
        :size="size"
        :stroke-width="strokeWidth"
        :color="color"
    />
</template>
