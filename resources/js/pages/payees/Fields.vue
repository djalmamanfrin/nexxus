<script setup lang="ts">
import { watch } from 'vue';
import AppInput from '@/components/base/AppInput.vue';
import AppSwitch from '@/components/base/AppSwitch.vue';
import AppInputDocument from '@/components/base/AppInputDocument.vue';

const props = defineProps<{
    form: any;
}>();

watch(
    () => props.form.is_pix_document,
    (value) => {
        if (value) {
            props.form.pix_key = props.form.document;
            props.form.pix_key_type = props.form.document_type;
        } else {
            // TODO: caso o usuario ative e desative na sequencia o valor
            //  anterior n é restabelecido provavelmente pq aqui seto null
            props.form.pix_key = null;
            props.form.pix_key_type = null;
        }
    },
);

watch(
    () => props.form.document,
    () => {
        if (props.form.is_pix_document) {
            props.form.pix_key = props.form.document;
            props.form.pix_key_type = props.form.document_type;
        }
    },
);
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
        :label="
            form.is_pix_document
                ? 'Documento como PIX ativo'
                : 'Usar documento como PIX'
        "
    />
    <AppInput
        v-model="form.pix_key"
        label="Chave pix"
        :error="form.errors.pix_key"
        :disabled="form.is_pix_document"
    />
</template>
