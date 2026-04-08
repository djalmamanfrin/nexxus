<script setup lang="ts">
import AppInputMoney from '@/components/base/AppInputMoney.vue';
import AppInputDate from '@/components/base/AppInputDate.vue';
import AppSelect from '@/components/base/AppSelect.vue';

defineProps<{
    form: any;
}>();
</script>

<template>
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
</template>
