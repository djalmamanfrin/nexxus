<script setup lang="ts">
import AppInput from '@/components/base/AppInput.vue';
import AppInputMoney from '@/components/base/AppInputMoney.vue';
import AppInputDate from '@/components/base/AppInputDate.vue';
import AppTextarea from '@/components/base/AppTextarea.vue';

defineProps<{
    form: any;
}>();
</script>

<template>
    <AppInput v-model="form.code" :error="form.errors.code" label="Código" />
    <AppInputMoney
        v-model="form.budget"
        :error="form.errors.budget"
        label="Orçamento"
    />
    <AppInputDate
        v-model="form.start_date"
        :error="form.errors.start_date"
        label="Início em"
    />
    <AppInputDate
        v-model="form.expected_end_date"
        :error="form.errors.expected_end_date"
        label="Expectativa de término em"
    />
    <AppTextarea
        v-model="form.description"
        :error="form.errors.description"
        label="Descrição"
        placeholder="Uma descrição que ajuda a identificar o centro de custo"
    />
</template>
