<script setup lang="ts">
import AppInput from '@/components/base/AppInput.vue';
import AppTextarea from '@/components/base/AppTextarea.vue';
import AppSwitch from '@/components/base/AppSwitch.vue';

defineProps<{
    form: any;
}>();
</script>

<template>
    <AppInput v-model="form.name" :error="form.errors.name" label="Nome" />
    <AppInput v-model="form.code" :error="form.errors.code" label="Código" />
    <AppSwitch v-model="form.is_active" label="Ativo" />
    <AppTextarea
        v-model="form.description"
        :error="form.errors.description"
        label="Descrição"
        placeholder="Uma descrição que ajuda a identificar o centro de custo"
    />
</template>
