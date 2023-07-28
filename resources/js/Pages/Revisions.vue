<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputSuccess from "@/Components/InputSuccess.vue";
import Modal from "@/Components/Modal.vue";

export default {
    data: () => {
        return {
            revision: {name: ''},
            errors: {},
            alert: "",
            revisionsList: [],
            showModal: false,
        }
    },
    components: {
        Modal,
        InputSuccess,
        AuthenticatedLayout, Head, Link, PrimaryButton, InputError, TextInput, InputLabel
    },
    methods: {
        sendRevision(){ // Metodo para envio de marca

            this.clearAlerts()

            // Verifica se é uma edição ou inserção
            const request = this.revision.id
                ? axios
                    .patch(
                        route('revision.update', {revision: this.revision.id}),
                        this.revision
                    )
                : axios
                    .post(
                        route('revision.store'),
                        this.revision
                    )

            // Processa a requisição
            request
                .then(response => {
                    this.alert = response.data
                    setTimeout(this.closeModal, 2000)
                })
                .catch(erro => {
                    this.errors = erro.response.data.errors
                })
                .finally(() => {
                    this.getRevisions()
                })
        },
        getRevisions(){ // Metódo para atualizar lista de marcas
            axios
                .get(route('revision.all'))
                .then(response => {
                    this.revisionsList = response.data
                })
        },
        editRevision(revision){ // Prepara a edição de um marca
            this.clearAlerts()
            this.revision = {...revision}
            this.showModal = true
        },
        closeModal(){ // Fecha o modal e esvazia o marca
            this.showModal = false
            this.revision = {name: ""}
        },
        clearAlerts(){ // Esvazia os alertas
            this.alert = ""
            this.errors = {}
        }
    },
    created(){
        this.getRevisions()
    }
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Marcas</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-8 bg-white shadow">
                        <header class="flex justify-between">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">Lista de Marcas</h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Marcas atuais
                                </p>
                            </div>
                            <div>
                                <PrimaryButton @click="showModal = true" type="button">
                                    + Marca
                                </PrimaryButton>
                            </div>
                        </header>

                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-3">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Nome
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Revisões feitas
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="revision in revisionsList" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{revision.name}}
                                        </th>
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            0
                                        </th>
                                        <td class="px-6 py-4 text-right flex justify-between">
                                            <button
                                                type="button"
                                                @click="editRevision(revision)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                                title="Editar Marca"
                                            >
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <Modal :show="showModal">
            <div class="p-4 sm:p-8 bg-white shadow">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Cadastro de Marcas</h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Preencha o formulário e cadastre uma nova marca
                    </p>
                </header>
                <form @submit.prevent="sendRevision" class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="name" value="Nome" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="revision.name"
                            required
                            autofocus
                        />

                        <InputError class="mt-2" :message="errors.name" />
                    </div>

                    <div>
                        <InputSuccess :message="alert" />
                    </div>

                    <div class="flex items-center gap-4">
                        <PrimaryButton
                            type="button"
                            @click="closeModal"
                            class="bg-red-700 hover:bg-red-600">
                            Cancelar
                        </PrimaryButton>

                        <PrimaryButton :disabled="revision.processing">Cadastrar</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
