<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputSuccess from "@/Components/InputSuccess.vue";
import Modal from "@/Components/Modal.vue";
import moment from "moment";
import ModalRevision from "@/Partials/ModalRevision.vue";
import {defaultRevision} from "@/Utils/Examples.js";

export default {
    data: () => {
        return {
            revision: defaultRevision,
            revisionsList: [],
            showModal: false,
        }
    },
    components: {
        ModalRevision,
        Modal,
        InputSuccess,
        AuthenticatedLayout, Head, Link, PrimaryButton, InputError, TextInput, InputLabel
    },
    methods: {
        getRevisions(){ // Metódo para atualizar lista de revisãos
            axios
                .get(route('revision.all'))
                .then(response => {
                    this.revisionsList = response.data
                })
        },
        editRevision(revision){ // Prepara a edição de um revisão
            this.revision = {...revision}
            this.showModal = true
        },
        closeModal(){ // Fecha o modal e esvazia a revisão
            this.showModal = false
            this.revision = {...defaultRevision}
        }
    },
    created(){
        this.getRevisions()
    },
    computed: {
        formatedRevisionList()
        {
            return this.revisionsList.map(revision => {
                return {
                    ...revision,
                    info: `${revision.car.brand.name} ${revision.car.model} ${revision.car.year_of_manufacture}`,
                    date: moment(revision.review_day).format('DD/MM/YYYY')
                }
            })
        }
    }
}
</script>

<template>
    <Head title="Revisões" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Revisões</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-8 bg-white shadow">
                        <header class="flex justify-between">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">Lista de Revisões</h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Revisões atuais
                                </p>
                            </div>
                        </header>

                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-3">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Data da revisão
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Carro
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Proprietário
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="revision in formatedRevisionList" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{revision.date}}
                                        </th>
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{revision.info}}
                                        </th>
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{revision.car.owner.name}}
                                        </th>
                                        <td class="px-6 py-4 text-right flex justify-between">
                                            <button
                                                type="button"
                                                @click="editRevision(revision)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                                title="Editar Revisão"
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
        <ModalRevision
            :car="revision.car"
            :showModal="showModal"
            :closeModal="closeModal"
            :presetRevision="revision"
            @updateRecords="getRevisions"
        />
    </AuthenticatedLayout>
</template>
