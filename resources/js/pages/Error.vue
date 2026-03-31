<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import AppButton from '@/components/AppButton.vue';

const props = defineProps({
    status: Number,
});

const title = {
    500: 'Erro interno',
    404: 'Página não encontrada',
    403: 'Acesso negado',
    503: 'Serviço indisponível',
};

const description = {
    500: 'Se o problema persistir, entre em contato com o suporte informando o que você estava fazendo no momento do erro.',
    404: 'A página que você procura não existe ou foi removida.',
    403: 'Você não tem permissão para acessar esta página.',
    503: 'Serviço temporariamente indisponível. Tente novamente em instantes.',
};

function goBack() {
    window.location.reload();
}
</script>

<template>
    <div
        class="flex h-screen flex-col items-center justify-center gap-6 px-4 text-center"
    >
        <h1 class="text-5xl font-bold">{{ status }}</h1>

        <div>
            <h2 class="text-xl font-medium">{{ title[status] }}</h2>
            <p class="mt-2 max-w-md text-gray-500">
                {{ description[status] }}
            </p>
        </div>

        <div class="mt-4 flex gap-3">
            <AppButton label="Tentar novamente" @click="goBack" />
        </div>
    </div>
</template>
