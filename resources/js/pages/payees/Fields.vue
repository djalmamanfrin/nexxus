<script setup lang="ts">
import AppInput from '@/components/base/AppInput.vue';
import AppSwitch from '@/components/base/AppSwitch.vue';
import AppInputDocument from '@/components/base/AppInputDocument.vue';

defineProps<{
    form: any;
}>();
</script>

<template>
    <AppInput v-model="form.name" :error="form.errors.name" label="Name" />
    <AppInputDocument
        label="CNPJ/CPF"
        v-model="form.document"
        :error="form.errors.document"
    />
    <AppSwitch
        v-model="form.is_pix_document"
        :error="form.errors.is_pix_document"
        label="Usar documento como PIX"
    />
    <div v-if="!form.is_pix_document">
        <AppInput
            v-model="form.pix_key"
            label="Chave pix"
            :error="form.errors.pix_key"
        />
    </div>
</template>
